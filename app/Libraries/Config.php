<?php

class Config
{
    // Название запрашиваемого значения
    const SITE_NAME    = "site.name";

    private static array $data;

    // Пример получения значения
    public static function get(string $name): string
    {
        if (is_null(self::$data)) {
            // Получение в массив из JSON файла конфигурации
            self::$data = json_decode(CONFIG_FILE_PATH, true);
        }
        return self::$data[$name];
    }
}
