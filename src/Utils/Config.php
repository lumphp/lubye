<?php
namespace Lum\Lubye\Utils;

use InvalidArgumentException;
use RuntimeException;

/**
 * Class Config 配置
 *
 * @package Lum\Lubye\Utils
 */
final class Config {
    /**
     * @var Path $path
     */
    private static $path;
    protected static $hash;
    protected static $instance=[];
    protected $configItem;

    /**
     * 防止clone
     */
    private function __clone() {
    }

    /**
     * 载入配置文件并返回对象
     *
     * @param string $name
     * @param bool $isOriginal 是否原样返回
     *
     * @return mixed|object
     */
    private static function loadConfigFile(string $name, bool $isOriginal=false) {
        $file_name=sprintf('%s%s.php', static::$path->getResourcePath(), str_replace('.', DIRECTORY_SEPARATOR, $name));
        if (!file_exists($file_name)) {
            //die('config failed:not found ' . $file_name);
            throw new InvalidArgumentException('not found config file!');
        }
        $result=require($file_name);
        if (!$result) {
            //            return NULL;
            throw new RuntimeException('no config item!');
        }
        if ($isOriginal) {
            return $result;
        }
        return (object)$result;
    }

    /**
     * 载入配置项
     *
     * @param string $name
     * @param bool $is_original 是否原样返回
     *
     * @return Config
     */
    private function loadItem($name, $is_original=false) {
        if (!isset($this->configItem->$name)) {
            return null;
        }
        if ($is_original) {
            return $this->configItem->$name;
        }
        if (!is_object($this->configItem->$name) && false === is_array($this->configItem->$name)) {
            return $this->configItem->$name;
        }
        return new Config((object)$this->configItem->$name, static::$path);
    }

    /**
     * 构造
     *
     * @param $config
     * @param null|Path $path
     */
    public function __construct($config, Path $path) {
        $configItem=$config;
        if (!static::$path) {
            static::$path=$path;
        }
        if (is_string($config)) {
            $configItem=self::loadConfigFile($config, false);
        }
        $this->configItem=$configItem;
    }

    /**
     * 自动获取配置项设定值(魔术方法)
     *
     * @param $name
     *
     * @return Config
     */
    public function __get($name) {
        return $this->loadItem($name);
    }

    /**
     * 获取配置项设定值
     *
     * @param string $name
     * @param bool $isOriginal 是否返回实例
     *
     * @return Config|string|int
     */
    public function get($name, $isOriginal=false) {
        return $this->loadItem($name, $isOriginal);
    }

    /**
     * 获取配置的hash
     *
     * @return mixed
     */
    public function getHashKey() {
        if (self::$hash) {
            return self::$hash;
        }
        self::$hash=md5(json_encode($this->configItem));
        return self::$hash;
    }

    /**
     * 设置配置信息
     *
     * @param string $name
     * @param Config $obj
     *
     * @return bool|null
     */
    public function set($name='', Config $obj) {
        if (!$name) {
            return null;
        }
        $this->$name=$obj;
        return true;
    }
}