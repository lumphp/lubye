<?php
namespace Lum\Lubye\Consoles;

use Lum\Lubye\Consoles\Formatters\OutputFormatter;

/**
 * Interface Output
 *
 * @package Lum\Lubye
 */
interface Output {
    const VERBOSITY_QUIET=16;
    const VERBOSITY_NORMAL=32;
    const VERBOSITY_VERBOSE=64;
    const VERBOSITY_VERY_VERBOSE=128;
    const VERBOSITY_DEBUG=256;
    const OUTPUT_NORMAL=1;
    const OUTPUT_RAW=2;
    const OUTPUT_PLAIN=4;

    /**
     * Writes a message to the output.
     *
     * @param string|iterable $messages The message as an iterable of strings or a single string
     * @param bool $newline Whether to add a newline
     * @param int $options a bit mask of options (one of the OUTPUT or VERBOSITY constants), 0 is considered the same as self::OUTPUT_NORMAL | self::VERBOSITY_NORMAL
     */
    public function write($messages, bool $newline=false, int $options=0);

    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param string|iterable $messages The message as an iterable of strings or a single string
     * @param int $options a bit mask of options (one of the OUTPUT or VERBOSITY constants), 0 is considered the same as self::OUTPUT_NORMAL | self::VERBOSITY_NORMAL
     */
    public function writeln(string $messages, int $options=0);

    /**
     * Sets the verbosity of the output.
     *
     * @param int $level The level of verbosity (one of the VERBOSITY constants)
     */
    public function setVerbosity(int $level);

    /**
     * Gets the current verbosity of the output.
     *
     * @return int The current level of verbosity (one of the VERBOSITY constants)
     */
    public function getVerbosity() : int;

    /**
     * Returns whether verbosity is quiet (-q).
     *
     * @return bool true if verbosity is set to VERBOSITY_QUIET, false otherwise
     */
    public function isQuiet() : bool;

    /**
     * Returns whether verbosity is verbose (-v).
     *
     * @return bool true if verbosity is set to VERBOSITY_VERBOSE, false otherwise
     */
    public function isVerbose() : bool;

    /**
     * Returns whether verbosity is very verbose (-vv).
     *
     * @return bool true if verbosity is set to VERBOSITY_VERY_VERBOSE, false otherwise
     */
    public function isVeryVerbose() : bool;

    /**
     * Returns whether verbosity is debug (-vvv).
     *
     * @return bool true if verbosity is set to VERBOSITY_DEBUG, false otherwise
     */
    public function isDebug() : bool;

    /**
     * Sets the decorated flag.
     *
     * @param bool $decorated Whether to decorate the messages
     */
    public function setDecorated(bool $decorated) : void;

    /**
     * Gets the decorated flag.
     *
     * @return bool true if the output will decorate messages, false otherwise
     */
    public function isDecorated() : bool;

    /**
     * set output Formatters
     *
     * @param OutputFormatter $formatter
     *
     * @return mixed
     */
    public function setFormatter(OutputFormatter $formatter);

    /**
     * Returns current output Formatters instance.
     *
     * @return OutputFormatter
     */
    public function getFormatter() : OutputFormatter;
}