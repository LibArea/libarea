<?php

namespace Radjax\Src;

class Request
{
    private static $instance;

    private static $request = [];

    private static $close = false;


    private function __construct() {
    }

    public function __clone() {
    }

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public static function __callStatic($method, $args) {
        return call_user_func_array(array(self::instance(), $method), $args);
    }

    ///////////////////////////////////////////////////

    public static function get(string $name = null) {
        return empty($name) ? self::$request : (self::$request[$name] ?? null);
    }

    public static function add(string $name, string $value) {
        if (!self::$close) self::$request[$name] = is_numeric($value) ? floatval($value) : $value;
    }

    public static function addAll(array $data) {
        if (!self::$close) {
            foreach ($data as $name => $value) {
                self::add($name, $value);
            }
        }

        self::close();
    }

    public static function close() {
        self::$close = true;
    }

}

