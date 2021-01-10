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
 * ConsoleOutput is the default class for all CLI output. It uses STDOUT and STDERR.
 * This class is a convenient wrapper around `StreamOutput` for both STDOUT and STDERR.
 *     $output = new ConsoleOutput();
 * This is equivalent to:
 *     $output = new StreamOutput(fopen('php://stdout', 'w'));
 *     $stdErr = new StreamOutput(fopen('php://stderr', 'w'));
 */
class ConsoleDefaultOutput extends StreamOutput {
    private $stderr;
    private $consoleSectionOutputs=[];

    /**
     * @param int $verbosity The verbosity level (one of the VERBOSITY constants in OutputInterface)
     * @param bool|null $decorated Whether to decorate messages (null for auto-guessing)
     * @param OutputFormatter|null $formatter Output Formatters instance (null to use default OutputFormatter)
     */
    public function __construct(int $verbosity=self::VERBOSITY_NORMAL, bool $decorated=null,
        ?OutputFormatter $formatter=null) {
        parent::__construct($this->openOutputStream(), $verbosity, $decorated, $formatter);
        $actualDecorated=$this->isDecorated();
        $this->stderr=new StreamOutput($this->openErrorStream(), $verbosity, $decorated, $this->getFormatter());
        if (null === $decorated) {
            $this->setDecorated($actualDecorated && $this->stderr->isDecorated());
        }
    }

    /**
     * Creates a new output section.
     */
    public function section() : ConsoleSectionOutput {
        return new ConsoleSectionOutput($this->getStream(), $this->consoleSectionOutputs, $this->getVerbosity(), $this->isDecorated(), $this->getFormatter());
    }

    /**
     * {@inheritdoc}
     */
    public function setDecorated(bool $decorated):void {
        parent::setDecorated($decorated);
        $this->stderr->setDecorated($decorated);
    }

    /**
     * {@inheritdoc}
     */
    public function setFormatter($formatter) {
        parent::setFormatter($formatter);
        $this->stderr->setFormatter($formatter);
    }

    /**
     * {@inheritdoc}
     */
    public function setVerbosity($level) {
        parent::setVerbosity($level);
        $this->stderr->setVerbosity($level);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorOutput() {
        return $this->stderr;
    }

    /**
     * {@inheritdoc}
     */
    public function setErrorOutput($error) {
        $this->stderr=$error;
    }

    /**
     * Returns true if current environment supports writing Consoles output to
     * STDOUT.
     *
     * @return bool
     */
    protected function hasStdoutSupport() {
        return false === $this->isRunningOS400();
    }

    /**
     * Returns true if current environment supports writing Consoles output to
     * STDERR.
     *
     * @return bool
     */
    protected function hasStderrSupport() {
        return false === $this->isRunningOS400();
    }

    /**
     * Checks if current executing environment is IBM iSeries (OS400), which
     * doesn't properly convert character-encodings between ASCII to EBCDIC.
     */
    private function isRunningOS400() : bool {
        $checks=[
            \function_exists('php_uname') ? php_uname('s') : '',
            getenv('OSTYPE'),
            PHP_OS,
        ];
        return false !== stripos(implode(';', $checks), 'OS400');
    }

    /**
     * @return resource
     */
    private function openOutputStream() {
        if (!$this->hasStdoutSupport()) {
            return fopen('php://output', 'w');
        }
        return @fopen('php://stdout', 'w') ?: fopen('php://output', 'w');
    }

    /**
     * @return resource
     */
    private function openErrorStream() {
        return fopen($this->hasStderrSupport() ? 'php://stderr' : 'php://output', 'w');
    }
}
