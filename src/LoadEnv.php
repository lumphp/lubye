<?php
namespace Lum\Lubye;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use Lum\Lumbye\Console\ConsoleDefaultOutput;

/**
 * Class LoadEnv
 *
 * @package Lum\Lubye\app
 */
class LoadEnv {
    /**
     * @var string 默认环境
     */
    protected $defaultEnv='production';
    /**
     * The directory containing the environment file.
     *
     * @var string
     */
    protected $filePath;
    /**
     * The name of the environment file.
     *
     * @var string|null
     */
    protected $fileName;
    /**
     * @var array
     */
    protected static $supportedEnv=['local', 'test', 'production', 'dev', 'preview'];

    /**
     * Create a new loads environment variables instance.
     *
     * @param  string $path
     *
     * @return void
     */
    public function __construct($path) {
        $this->filePath=$path;
        $this->init();
    }

    /**
     * Setup the environment variables.
     * If no environment file exists, we continue silently.
     */
    public function bootstrap() : void {
        try {
            $this->createDotEnv()->safeLoad();
        } catch(InvalidFileException $e) {
            $this->writeErrorAndDie([
                'The environment file is invalid!',
                $e->getMessage(),
            ]);
        }
    }

    /**
     * Create a dot env instance.
     *
     * @return Dotenv
     */
    protected function createDotEnv() {
        return Env::getDotEnvInstance($this->filePath, $this->fileName);
    }

    protected function init() : void {
        $env=get_cfg_var('env');
        $env=empty($env) ? $this->defaultEnv : $env;
        if (false === in_array($env, static::$supportedEnv)) {
            $this->writeErrorAndDie('unset env variable or unsupport env!');
        }
        $this->fileName=sprintf('.env.%s', $env);
    }

    /**
     * Write the error information to the screen and exit.
     *
     * @param  string[] $errors
     *
     * @return void
     */
    protected function writeErrorAndDie(array $errors) {
        $output=(new ConsoleDefaultOutput)->getErrorOutput();
        foreach ($errors as $error) {
            $output->writeln($error);
        }
        die(1);
    }
}
