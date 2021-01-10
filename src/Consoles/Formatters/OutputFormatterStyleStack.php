<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Lum\Lubye\Consoles\Formatters;

use InvalidArgumentException;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class OutputFormatterStyleStack {
    /**
     * @var OutputFormatterStyle[]
     */
    private $styles;
    /**
     * @var OutputFormatterDefaultStyle
     */
    private $emptyStyle;

    /**
     * OutputFormatterStyleStack constructor.
     *
     * @param OutputFormatterStyle|null $emptyStyle
     */
    public function __construct(?OutputFormatterStyle $emptyStyle=null) {
        $this->emptyStyle=$emptyStyle ?: new OutputFormatterDefaultStyle;
        $this->reset();
    }

    /**
     * Resets stack (ie. empty internal arrays).
     */
    public function reset() {
        $this->styles=[];
    }

    /**
     * Pushes a style in the stack.
     *
     * @param OutputFormatterStyle $style
     */
    public function push(OutputFormatterStyle $style) {
        $this->styles[]=$style;
    }

    /**
     * Pops a style from the stack.
     *
     * @param OutputFormatterStyle|null $style
     *
     * @return OutputFormatterStyle
     * @throws InvalidArgumentException When style tags incorrectly nested
     */
    public function pop(?OutputFormatterStyle $style=null) {
        if (empty($this->styles)) {
            return $this->emptyStyle;
        }
        if (null === $style) {
            return array_pop($this->styles);
        }
        foreach (array_reverse($this->styles, true) as $index=>$stackedStyle) {
            if ($style->apply('') === $stackedStyle->apply('')) {
                $this->styles=\array_slice($this->styles, 0, $index);
                return $stackedStyle;
            }
        }
        throw new InvalidArgumentException('Incorrectly nested style tag found.');
    }

    /**
     * Computes current style with stacks top codes.
     *
     * @return OutputFormatterStyle
     */
    public function getCurrent() {
        if (empty($this->styles)) {
            return $this->emptyStyle;
        }
        return $this->styles[\count($this->styles) - 1];
    }

    /**
     * @param OutputFormatterStyle $emptyStyle
     *
     * @return OutputFormatterStyleStack
     */
    public function setEmptyStyle(OutputFormatterStyle $emptyStyle) {
        $this->emptyStyle=$emptyStyle;
        return $this;
    }

    /**
     * @return OutputFormatterStyle
     */
    public function getEmptyStyle() {
        return $this->emptyStyle;
    }
}
