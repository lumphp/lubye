<?php
namespace Lum\Lubye\Consoles;

use Lum\Lubye\Consoles\Formatters\OutputFormatter;
use Lum\Lubye\Consoles\Formatters\OutputDefaultFormatter;

/**
 * Base class for output classes.
 * There are five levels of verbosity
 *         normal: no option passed (normal output)
 *         verbose: -v (more output)
 *         very verbose: -vv (highly extended output)
 *         debug: -vvv (all debug output)
 *         quiet: -q (no output)
 */
abstract class AbstractOutput implements Output {
    private $verbosity;
    private $formatter;

    /**
     * @param int $verbosity The verbosity level (one of the VERBOSITY constants in OutputInterface)
     * @param bool $decorated Whether to decorate messages
     * @param OutputFormatter|null $formatter Output Formatters instance (null to use default OutputFormatter)
     */
    public function __construct(?int $verbosity=self::VERBOSITY_NORMAL, bool $decorated=false,
        ?OutputFormatter $formatter=null) {
        $this->verbosity=null === $verbosity ? self::VERBOSITY_NORMAL : $verbosity;
        $this->formatter=$formatter ?: new OutputDefaultFormatter;
        $this->formatter->setDecorated($decorated);
    }

    /**
     * {@inheritdoc}
     */
    public function setFormatter(OutputFormatter $formatter) {
        $this->formatter=$formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormatter() : OutputFormatter {
        return $this->formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function setDecorated(bool $decorated) : void {
        $this->formatter->setDecorated($decorated);
    }

    /**
     * {@inheritdoc}
     */
    public function isDecorated() : bool {
        return $this->formatter->isDecorated();
    }

    /**
     * {@inheritdoc}
     */
    public function setVerbosity(int $level) {
        $this->verbosity=$level;
    }

    /**
     * {@inheritdoc}
     */
    public function getVerbosity() : int {
        return $this->verbosity;
    }

    /**
     * {@inheritdoc}
     */
    public function isQuiet() : bool {
        return self::VERBOSITY_QUIET === $this->verbosity;
    }

    /**
     * {@inheritdoc}
     */
    public function isVerbose() : bool {
        return self::VERBOSITY_VERBOSE <= $this->verbosity;
    }

    /**
     * {@inheritdoc}
     */
    public function isVeryVerbose() : bool {
        return self::VERBOSITY_VERY_VERBOSE <= $this->verbosity;
    }

    /**
     * {@inheritdoc}
     */
    public function isDebug() : bool {
        return self::VERBOSITY_DEBUG <= $this->verbosity;
    }

    /**
     * {@inheritdoc}
     */
    public function writeln($messages, $options=self::OUTPUT_NORMAL) {
        $this->write($messages, true, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function write($messages, $newline=false, $options=self::OUTPUT_NORMAL) {
        if (!is_iterable($messages)) {
            $messages=[$messages];
        }
        $types=self::OUTPUT_NORMAL | self::OUTPUT_RAW | self::OUTPUT_PLAIN;
        $type=$types & $options ?: self::OUTPUT_NORMAL;
        $verbosities=self::VERBOSITY_QUIET | self::VERBOSITY_NORMAL | self::VERBOSITY_VERBOSE |
            self::VERBOSITY_VERY_VERBOSE | self::VERBOSITY_DEBUG;
        $verbosity=$verbosities & $options ?: self::VERBOSITY_NORMAL;
        if ($verbosity > $this->getVerbosity()) {
            return;
        }
        foreach ($messages as $message) {
            switch ($type) {
            case Output::OUTPUT_NORMAL:
                $message=$this->formatter->format($message);
                break;
            case Output::OUTPUT_RAW:
                break;
            case Output::OUTPUT_PLAIN:
                $message=strip_tags($this->formatter->format($message));
                break;
            }
            $this->doWrite($message, $newline);
        }
    }

    /**
     * Writes a message to the output.
     *
     * @param string $message A message to write to the output
     * @param bool $newline Whether to add a newline or not
     */
    abstract protected function doWrite($message, $newline);
}
