<?php

namespace App\Optional;

use Hleb\Main\Insert\BaseSingleton;

class Data extends BaseSingleton
{
    private static $data = null;

    private static $values = [];

    public static function setData(array $data)
    {
        if (is_null(self::$data)) {
            self::$data = $data;
        }
    }

    public static function getData()
    {
        return self::$data ?? [];
    }

    public static function setValue(string $name, $value)
    {
        if (!isset(self::$values[$name])) {
            self::$values[$name] = $value;
        }
    }

    public static function getValue(string $name)
    {
        return self::$values[$name] ?? null;
    }
}
