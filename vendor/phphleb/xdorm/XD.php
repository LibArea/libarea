<?php
/**
 * @author  Foma Tuturov <fomiash@yandex.ru>
 */

namespace XdORM;

use XdORM\Shell\XdHelper;

class XD
{
    private static $key = null;

    private function __construct(){}

    public function __clone(){}

    public static function __callStatic($method, $args)
    {
        $inst = self::hd_create_helper();

        $inst->_create_str($method, $args);

        return $inst;
    }

    private static function hd_create_key(): string
    {
        if (empty(self::$key)) {
            try {
                self::$key = bin2hex(random_bytes(10));
            } catch (\Exception $ex) {
                self::$key = str_shuffle(md5(rand()));
            }
        }
        return self::$key;
    }

    public static function checkKey(string $key)
    {
        return $key === self::$key;
    }

    private static function hd_create_helper(){

        $inst = new XdHelper;

        $inst->_set_key(self::hd_create_key());

        return $inst;
    }

    public static function addArray(array $array): XdHelper
    {
        $inst = self::hd_create_helper();

        $inst->addArray($array);

        return $inst;
    }

    /*
     * Adding an array of arguments to the request.
     *
     * Добавление массива аргументов к запросу.
     */
    public static function setList(array $array): XdHelper
    {
        return self::addArray($array);
    }

}

