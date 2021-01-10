<?php
namespace Lum\Lubye\Consoles\Formatters;
/**
 * Wrap Output Formatter
 *
 * @package Lum\Lubye\Consoles\Formatters
 */
interface OutputFormatterWrapper extends OutputFormatter {
    /**
     * Formats a message according to the given styles, wrapping at `$width` (0 means no wrapping).
     *
     * @param string $message
     * @param int $width
     *
     * @return mixed
     */
    public function formatAndWrap(string $message, int $width);
}
