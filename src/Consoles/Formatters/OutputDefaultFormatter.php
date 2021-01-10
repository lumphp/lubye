<?php
namespace Lum\Lubye\Consoles\Formatters;
/**
 * Formatter class for Consoles output.
 */
class OutputDefaultFormatter implements OutputFormatterWrapper {
    private $decorated;
    private $styles=[];
    private $styleStack;

    /**
     * Escapes "<" special char in given text.
     *
     * @param string $text Text to escape
     *
     * @return string Escaped text
     */
    public static function escape($text) {
        $text=preg_replace('/([^\\\\]?)</', '$1\\<', $text);
        return self::escapeTrailingBackslash($text);
    }

    /**
     * Escapes trailing "\" in given text.
     *
     * @internal
     *
     * @param string $text
     *
     * @return string
     */
    public static function escapeTrailingBackslash(string $text) : string {
        if ('\\' === substr($text, -1)) {
            $len=\strlen($text);
            $text=rtrim($text, '\\');
            $text=str_replace("\0", '', $text);
            $text.=str_repeat("\0", $len - \strlen($text));
        }
        return $text;
    }

    /**
     * Initializes Consoles output Formatters.
     *
     * @param bool $decorated
     * @param OutputFormatterStyle[] $styles Array of "name => FormatterStyle" instances
     */
    public function __construct(bool $decorated=false, array $styles=[]) {
        $this->decorated=$decorated;
        $this->setStyle('error', new OutputFormatterDefaultStyle('white', 'red'));
        $this->setStyle('info', new OutputFormatterDefaultStyle('green'));
        $this->setStyle('comment', new OutputFormatterDefaultStyle('yellow'));
        $this->setStyle('question', new OutputFormatterDefaultStyle('black', 'cyan'));
        foreach ($styles as $name=>$style) {
            $this->setStyle($name, $style);
        }
        $this->styleStack=new OutputFormatterStyleStack;
    }

    /**
     * {@inheritdoc}
     */
    public function setDecorated(bool $decorated) : void {
        $this->decorated=$decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function isDecorated() : bool {
        return $this->decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function setStyle(string $name, OutputFormatterStyle $style) : void {
        $this->styles[strtolower($name)]=$style;
    }

    /**
     * {@inheritdoc}
     */
    public function hasStyle(string $name) : bool {
        return isset($this->styles[strtolower($name)]);
    }

    /**
     * {@inheritdoc}
     */
    public function getStyle(string $name) : ?OutputFormatterStyle {
        if (!$this->hasStyle($name)) {
            return null;
        }
        return $this->styles[strtolower($name)];
    }

    /**
     * {@inheritdoc}
     * @param string $message
     *
     * @return null|string
     */
    public function format(string $message) : string {
        return $this->formatAndWrap($message, 0);
    }

    /**
     * {@inheritdoc}
     */
    /**
     * @param string $message
     * @param int $width
     *
     * @return null|string
     */
    public function formatAndWrap(string $message, int $width) {
        $offset=0;
        $output='';
        $tagRegex='[a-z][^<>]*+';
        $currentLineLength=0;
        preg_match_all("#<(($tagRegex) | /($tagRegex)?)>#ix", $message, $matches, PREG_OFFSET_CAPTURE);
        foreach ($matches[0] as $i=>$match) {
            $pos=$match[1];
            $text=$match[0];
            if (0 != $pos && '\\' == $message[$pos - 1]) {
                continue;
            }
            // add the text up to the next tag
            $output.=$this->applyCurrentStyle(substr($message, $offset, $pos -
                $offset), $output, $width, $currentLineLength);
            $offset=$pos + \strlen($text);
            // opening tag?
            if ($open='/' != $text[1]) {
                $tag=$matches[1][$i][0];
            } else {
                $tag=isset($matches[3][$i][0]) ? $matches[3][$i][0] : '';
            }
            if (!$open && !$tag) {
                // </>
                $this->styleStack->pop();
            } elseif (null === $style=$this->createStyleFromString($tag)) {
                $output.=$this->applyCurrentStyle($text, $output, $width, $currentLineLength);
            } elseif ($open) {
                $this->styleStack->push($style);
            } else {
                $this->styleStack->pop($style);
            }
        }
        $output.=$this->applyCurrentStyle(substr($message, $offset), $output, $width, $currentLineLength);
        if (false !== strpos($output, "\0")) {
            return strtr($output, ["\0"=>'\\', '\\<'=>'<']);
        }
        return str_replace('\\<', '<', $output);
    }

    /**
     * @return OutputFormatterStyleStack
     */
    public function getStyleStack() {
        return $this->styleStack;
    }

    /**
     * Tries to create new style instance from string.
     *
     * @param string $string
     *
     * @return null|OutputFormatterStyle
     */
    private function createStyleFromString(string $string) : ?OutputFormatterStyle {
        if (isset($this->styles[$string])) {
            return $this->styles[$string];
        }
        if (!preg_match_all('/([^=]+)=([^;]+)(;|$)/', $string, $matches, PREG_SET_ORDER)) {
            return null;
        }
        $style=new OutputFormatterDefaultStyle;
        foreach ($matches as $match) {
            array_shift($match);
            $match[0]=strtolower($match[0]);
            if ('fg' == $match[0]) {
                $style->setForeground(strtolower($match[1]));
            } elseif ('bg' == $match[0]) {
                $style->setBackground(strtolower($match[1]));
            } elseif ('href' === $match[0]) {
                $style->setHref($match[1]);
            } elseif ('options' === $match[0]) {
                preg_match_all('([^,;]+)', strtolower($match[1]), $options);
                $options=array_shift($options);
                foreach ($options as $option) {
                    $style->setOption($option);
                }
            } else {
                return null;
            }
        }
        return $style;
    }

    /**
     * Applies current style from stack to text, if must be applied.
     *
     * @param string $text
     * @param string $current
     * @param int $width
     * @param int $currentLineLength
     *
     * @return string
     */
    private function applyCurrentStyle(string $text, string $current, int $width,
        int &$currentLineLength) : string {
        if ('' === $text) {
            return '';
        }
        if (!$width) {
            return $this->isDecorated() ? $this->styleStack->getCurrent()->apply($text) : $text;
        }
        if (!$currentLineLength && '' !== $current) {
            $text=ltrim($text);
        }
        if ($currentLineLength) {
            $prefix=substr($text, 0, $i=$width - $currentLineLength) . "\n";
            $text=substr($text, $i);
        } else {
            $prefix='';
        }
        preg_match('~(\\n)$~', $text, $matches);
        $text=$prefix . preg_replace('~([^\\n]{' . $width . '})\\ *~', "\$1\n", $text);
        $text=rtrim($text, "\n") . ($matches[1] ?? '');
        if (!$currentLineLength && '' !== $current && "\n" !== substr($current, -1)) {
            $text="\n" . $text;
        }
        $lines=explode("\n", $text);
        foreach ($lines as $line) {
            $currentLineLength+=\strlen($line);
            if ($width <= $currentLineLength) {
                $currentLineLength=0;
            }
        }
        if ($this->isDecorated()) {
            foreach ($lines as $i=>$line) {
                $lines[$i]=$this->styleStack->getCurrent()->apply($line);
            }
        }
        return implode("\n", $lines);
    }
}