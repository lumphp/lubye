<?php
namespace Lum\Lubye\Utils;
/**
 * Class Path
 *
 * @package rust
 */
final class Path {
    private const DEFAULT_RESOURCE_PATH='resource/';
    private const DEFAULT_CONFIG_PATH='config/';
    private const DEFAULT_LOGS_PATH='logs/';
    private const DEFAULT_CACHES_PATH='caches/';
    private const DEFAULT_ROUTES_PATH='routes/';
    private const DEFAULT_MIDDLEWARE_PATH='middleware/';
    private const DEFAULT_SRC_PATH='src/';
    private const DEFAULT_APP_PATH='src/app/';
    private static $resourcePath=null;
    private static $rootPath=null;
    private static $configPath=null;
    private static $logsPath=null;
    private static $cachesPath=null;
    private static $routesPath=null;
    private static $middlewarePath=null;
    private static $appPath=null;
    private static $srcPath=null;

    /**
     * Path constructor.
     *
     * @param string $rootPath
     * @param null|string $resourcePath
     */
    private function __construct(string $rootPath, ?string $resourcePath=null) {
        static::init($rootPath, $resourcePath);
    }

    /**
     * @param string $rootPath
     * @param null|string $resourcePath
     *
     * @return Path
     */
    public static function getInstance(string $rootPath, ?string $resourcePath=null) {
        return new self($rootPath, $resourcePath);
    }

    /**
     * @return string
     */
    public function getResourcePath() : string {
        return static::$resourcePath;
    }

    /**
     * @return string
     */
    public function getRootPath() : string {
        return static::$rootPath;
    }

    /**
     * @return string
     */
    public function getAppPath() : string {
        return static::$appPath;
    }

    /**
     * @return string
     */
    public function getConfigPath() : string {
        return static::$configPath;
    }

    /**
     * @param string $configPath
     */
    public function setConfigPath(string $configPath) {
        static::$configPath=$configPath;
    }

    /**
     * @return string
     */
    public function getCachePath() : string {
        return static::$cachesPath;
    }

    /**
     * @return string
     */
    public function getLogPath() : string {
        return static::$logsPath;
    }

    /**
     * @return string
     */
    public function getRoutesPath() : string {
        return static::$routesPath;
    }

    /**
     * @return string
     */
    public function getSourcePath() : string {
        return static::$srcPath;
    }

    /**
     * @return string
     */
    public function getMiddlewarePath() : string {
        return static::$middlewarePath;
    }

    /**
     * @param string $rootPath
     * @param null|string $resourcePath
     */
    private static function init(string $rootPath, ?string $resourcePath) {
        static::$rootPath=static::formatPath($rootPath);
        //set resource path
        static::$resourcePath=static::$rootPath . static::DEFAULT_RESOURCE_PATH;
        if (!empty($resourcePath)) {
            static::$resourcePath=static::$rootPath . static::formatPath($resourcePath);
        }
        static::$configPath=static::$resourcePath . static::DEFAULT_CONFIG_PATH;
        static::$logsPath=static::$resourcePath . static::DEFAULT_LOGS_PATH;
        static::$cachesPath=static::$resourcePath . static::DEFAULT_CACHES_PATH;
        static::$routesPath=static::$resourcePath . static::DEFAULT_ROUTES_PATH;
        static::$middlewarePath=static::$resourcePath . static::DEFAULT_MIDDLEWARE_PATH;
        static::$appPath=static::$rootPath . static::DEFAULT_APP_PATH;
        static::$srcPath=static::$rootPath . static::DEFAULT_SRC_PATH;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private static function formatPath(string $path) {
        if (DIRECTORY_SEPARATOR === substr($path, -1)) {
            return $path;
        }
        return $path . DIRECTORY_SEPARATOR;
    }
}