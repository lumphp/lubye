<?php
namespace Lum\Lubye\Utils;

/**
 * Class Registry
 *
 * @package Lum\Lubye\Utils
 */
final class Registry {
    private static $instance;
    protected      $containers = [];

    private function __clone() {
    }

    private function __construct() {
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function remove($key) {
        unset($this->containers[$key]);
    }

    public function set($key, $value) {
        $this->containers[$key] = $value;
    }

    public function get($key) {
        return isset($this->containers[$key]) ? $this->containers[$key] : NULL;
    }
}