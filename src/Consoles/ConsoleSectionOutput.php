<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Lum\Lubye\Consoles;

use Lum\Lubye\Consoles\Formatters\OutputFormatter;

/**
 * @author Pierre du Plessis <pdples@gmail.com>
 * @author Gabriel Ostrolucký <gabriel.ostrolucky@gmail.com>
 */
class ConsoleSectionOutput extends StreamOutput {
    private $content=[];
    private $lines=0;
    private $sections;
    private $terminal;

    /**
     * ConsoleSectionOutput constructor.
     *
     * @param resource $stream
     * @param ConsoleSectionOutput[] $sections
     * @param int $verbosity
     * @param bool $decorated
     * @param OutputFormatter $formatter
     */
    public function __construct($stream, array &$sections, int $verbosity, bool $decorated,
        OutputFormatter $formatter) {
        parent::__construct($stream, $verbosity, $decorated, $formatter);
        array_unshift($sections, $this);
        $this->sections= &$sections;
        $this->terminal=new Terminal;
    }

    /**
     * Clears previous output for this section.
     *
     * @param int $lines Number of lines to clear. If null, then the entire output of this section is cleared
     */
    public function clear(int $lines=null) {
        if (empty($this->content) || !$this->isDecorated()) {
            return;
        }
        if ($lines) {
            array_splice($this->content, -($lines *
                2)); // Multiply lines by 2 to cater for each new line added between content
        } else {
            $lines=$this->lines;
            $this->content=[];
        }
        $this->lines-=$lines;
        parent::doWrite($this->popStreamContentUntilCurrentSection($lines), false);
    }

    /**
     * Overwrites the previous output with a new message.
     *
     * @param array|string $message
     */
    public function overwrite($message) {
        $this->clear();
        $this->writeln($message);
    }

    /**
     * @return string
     */
    public function getContent() : string {
        return implode('', $this->content);
    }

    /**
     * @internal
     *
     * @param string $input
     */
    public function addContent(string $input) {
        foreach (explode(PHP_EOL, $input) as $lineContent) {
            $this->lines+=ceil($this->getDisplayLength($lineContent) / $this->terminal->getWidth()) ?: 1;
            $this->content[]=$lineContent;
            $this->content[]=PHP_EOL;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doWrite($message, $newline) {
        if (!$this->isDecorated()) {
            parent::doWrite($message, $newline);
            return;
        }
        $erasedContent=$this->popStreamContentUntilCurrentSection();
        $this->addContent($message);
        parent::doWrite($message, true);
        parent::doWrite($erasedContent, false);
    }

    /**
     * At initial stage, cursor is at the end of stream output. This method makes cursor crawl upwards until it hits
     * current section. Then it erases content it crawled through. Optionally, it erases part of current section too.
     *
     * @param int $numberOfLinesToClearFromCurrentSection
     *
     * @return string
     */
    private function popStreamContentUntilCurrentSection(int $numberOfLinesToClearFromCurrentSection=0) : string {
        $numberOfLinesToClear=$numberOfLinesToClearFromCurrentSection;
        $erasedContent=[];
        foreach ($this->sections as $section) {
            if ($section === $this) {
                break;
            }
            $numberOfLinesToClear+=$section->lines;
            $erasedContent[]=$section->getContent();
        }
        if ($numberOfLinesToClear > 0) {
            // move cursor up n lines
            parent::doWrite(sprintf("\x1b[%dA", $numberOfLinesToClear), false);
            // erase to end of screen
            parent::doWrite("\x1b[0J", false);
        }
        return implode('', array_reverse($erasedContent));
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function getDisplayLength(string $text) : string {
        $str=static::removeDecoration($this->getFormatter(), str_replace("\t", '        ', $text));
        if (false === $encoding=mb_detect_encoding($str, null, true)) {
            return \strlen($str);
        }
        return mb_strwidth($str, $encoding);
    }

    /**
     * @param OutputFormatter $formatter
     * @param string $string
     *
     * @return mixed|string
     */
    private static function removeDecoration(OutputFormatter $formatter, string $string) {
        $isDecorated=$formatter->isDecorated();
        $formatter->setDecorated(false);
        // remove <...> formatting
        $string=$formatter->format($string);
        // remove already formatted characters
        $string=preg_replace("/\033\[[^m]*m/", '', $string);
        $formatter->setDecorated($isDecorated);
        return $string;
    }
}
