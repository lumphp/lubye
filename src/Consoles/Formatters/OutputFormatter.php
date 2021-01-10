<?php
namespace Lum\Lubye\Consoles\Formatters;
/**
 * Interface OutputFormatter
 *
 * @package Lum\Lubye\Consoles\Formatters
 */
interface OutputFormatter {
    /**
     * Sets the decorated flag.
     *
     * @param bool $decorated Whether to decorate the messages or not
     */
    public function setDecorated(bool $decorated) : void;

    /**
     * Gets the decorated flag.
     *
     * @return bool true if the output will decorate messages, false otherwise
     */
    public function isDecorated() : bool;

    /**
     * Sets style.
     *
     * @param string $name The style name
     * @param OutputFormatterStyle $style
     */
    public function setStyle(string $name, OutputFormatterStyle $style) : void;

    /**
     * Checks if output Formatters has style with specified name.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasStyle(string $name) : bool;

    /**
     * Gets style options from style with specified name.
     *
     * @param string $name
     *
     * @return null|OutputFormatterStyle
     */
    public function getStyle(string $name) : ?OutputFormatterStyle;

    /**
     * Formats a message according to the given styles.
     *
     * @param string $message The message to style
     *
     * @return string The styled message
     */
    public function format(string $message) : string;
}
