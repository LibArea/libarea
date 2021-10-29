<?php

class Translate
{
    protected static $lang = "ru";

    private static $langData = [];

    public static function setLang(string $type)
    {
        self::$lang = $type;
    }

    public static function getLang()
    {
        return self::$lang;
    }

    public static function get(string $name, $lang = null)
    {
        if (empty($lang)) $lang = self::$lang;

        $data = self::load($lang);

        if (!empty($data[$lang][$name])) {
            return $data[$lang][$name];
        };
        return $name;
    }

    // Подгружает языковые файлы
    public static function load(string $lang)
    {
        if (isset(self::$langData[$lang])) {
            return self::$langData;
        }
        require_once __DIR__ . '/../Language/' . $lang . '.php';
        self::$langData[$lang] = $data;
        return self::$langData;
    }
}
