<?php

use App\Exception\ConfigException;

class Configuration
{
    private static $path  = HLEB_SEARCH_DBASE_CONFIG_FILE;

    private static $cache = [];

    public static function get($name, $default = null)
    {
        $file = false !== strpos($name, '.') ? strstr($name, '.', true) : $name;

        $key = ltrim(strstr($name, '.'), '.');

        self::$path = realpath(self::$path);

        if (!self::$path) {
            echo 'Config path does not exist';
            exit;
        }

        if (!is_file(self::$path . '/' . $file . '.php')) {
            throw ConfigException::NotFoundException($file);
        }

        if (!array_key_exists(self::$path . '/' . $file . '.php', self::$cache)) {

            $data = include self::$path . '/' . $file . '.php';

            if (!is_array($data)) {
                echo ('This is not an array: ' . self::$path . '/' . $file . '.php');
            }

            self::$cache[self::$path . '/' . $file . '.php'] = $data;
        }

        if ($name === $file) {
            return self::$cache[self::$path . '/' . $file . '.php'];
        }

        if (array_key_exists($key, self::$cache[self::$path . '/' . $file . '.php'])) {
            return self::$cache[self::$path . '/' . $file . '.php'][$key];
        }
        return $default;
    }
}
