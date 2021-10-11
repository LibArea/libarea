<?php

class Config
{
    static protected $path  = HLEB_SEARCH_DBASE_CONFIG_FILE;

    static protected $cache = [];

    public static function arr($name)
    {
        $file = false !== strpos($name, '.') ? strstr($name, '.', true) : $name;
        if (!is_file(static::$path.'/'.$file.'.php')) {
            exit('Нет файла: '.static::$path.'/'.$file.'.php');
        }
        
        if (!is_array($data = include static::$path.'/'.$file.'.php')) {
            exit('Это не массив: '.static::$path.'/'.$file.'.php');
        }

        return $data;
    }

    public static function get($name, $default = null)
    {
        $file = false !== strpos($name, '.') ? strstr($name, '.', true) : $name;

        static::$path = realpath(static::$path);
      
        if (!static::$path) {
            exit('Пути к конфигу не существует');
        }

        if (!is_file(static::$path.'/'.$file.'.php'))  {
            return $default;
        }

        if (!array_key_exists(static::$path.'/'.$file.'.php', static::$cache)) {
            $data = include static::$path.'/'.$file.'.php';

            if (!is_array($data)) {
                exit('This is not an array: '.static::$path.'/'.$file.'.php');
            }

            static::$cache[static::$path.'/'.$file.'.php'] = new ConfigSheaf($data);
        }

        if ($name === $file) {
            return static::$cache[static::$path.'/'.$file.'.php'];
        }

        return static::$cache[static::$path.'/'.$file.'.php']->get(ltrim(strstr($name, '.'), '.'), $default);
    }

}