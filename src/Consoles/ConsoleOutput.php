<?php
namespace Lum\Lubye\Consoles;

use Lum\Lubye\Output;

/**
 * Interface ConsoleOutput
 *
 * @package Lum\Lubye\Consoles
 */
interface ConsoleOutput extends Output {
    /**
     * Gets the Output for errors.
     *
     * @return Output
     */
    public function getErrorOutput() : Output;

    /**
     * @param Output $error
     */
    public function setErrorOutput(Output $error) : void;
}
