<?php
namespace Lum\Lubye\Consoles\Formatters;
/**
 * Formatter style interface for defining styles.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 * @package Lum\Lubye\Consoles\Formatters
 */
interface OutputFormatterStyle {
    /**
     * Sets style foreground color.
     *
     * @param string|null $color The color name
     */
    public function setForeground(?string $color=null);

    /**
     * Sets style background color.
     *
     * @param string $color The color name
     */
    public function setBackground(?string $color=null);

    /**
     * Sets some specific style option.
     *
     * @param string $option The option name
     */
    public function setOption(string $option);

    /**
     * remove some specific style option.
     *
     * @param string $option The option name
     */
    public function removeOption(string $option);

    /**
     * Sets multiple style options at once.
     *
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options) : void;

    /**
     * Applies the style to a given text.
     *
     * @param string $text The text to style
     *
     * @return string
     */
    public function apply(string $text);
}
