<?php
namespace Lum\Lubye;

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;

/**
 * Class Env
 *
 * @package Lum\Lumbye
 */
class Env {
    /**
     * Indicates if the putenv adapter is enabled.
     *
     * @var bool
     */
    protected static $putEnv=true;
    /**
     * The environment factory instance.
     *
     * @var Dotenv|null
     */
    protected static $dotEnv;
    /**
     * The environment variables instance.
     *
     * @var |null
     */
    protected static $variables;

    /**
     * Disable the putenv adapter.
     *
     * @return void
     */
    public static function disablePutEnv() {
        static::$putEnv=false;
        static::$dotEnv=null;
        static::$variables=null;
    }

    /**
     * Get the environment factory instance.
     *
     * @param string|array $paths
     * @param string|array|null $names
     * @param bool $isImmutable
     *
     * @return Dotenv
     */
    public static function getDotEnvInstance($paths, $names=null, $isImmutable=false) : Dotenv {
        if (null === static::$dotEnv) {
            $adapters=array_merge([
                new EnvConstAdapter,
            ], static::$putEnv ? [new PutenvAdapter] : []);
            $repository=RepositoryBuilder::create()->withReaders($adapters)->withWriters($adapters);
            if ($isImmutable) {
                $repository=$repository->immutable();
            }
            static::$dotEnv=Dotenv::create($repository->make(), $paths, $names);
        }
        return static::$dotEnv;
    }

    /**
     * Gets the value of an environment variable.
     *
     * @param  string $key
     * @param null|mixed $default
     *
     * @return null|mixed
     */
    public static function get(string $key, $default=null) {
        return $_ENV[$key] ?? $default;
    }
}
