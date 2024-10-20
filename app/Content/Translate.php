<?php

declare(strict_types=1);

/**
 * Upgraded version localization CodeIgniter 4 (MIT License)
 */

class Translate
{
    protected static array $language = [];

    protected static $locale;

    protected static $loadedFiles = [];

    protected static $replacementPattern = ['{', '}'];

    // Получим текущую локаль
    public static function getLang()
    {
        return static::$locale;
    }

    // Установим текущую локаль
    public static function setLang($locale)
    {
        static::$locale = $locale;
    }

    // Получаем перевод
    public static function get(string $line, array $args = [])
    {
        if (strpos($line, '.') === false) {
            return 'app.?';
        }

        // Разбираем имя файла и псевдоним.
        [$file, $parsedLine] = self::analyze($line, static::$locale);

        $output = self::receiving(static::$locale, $file, $parsedLine);

        // если все еще не нашел, попробуем английский
        if ($output === null) {
            [$file, $parsedLine] = self::analyze($line, 'en');
            $output = self::receiving('en', $file, $parsedLine);
        }

        $output ??= $line;

        if (!empty($args) && is_array($args)) {
            foreach ($args as $parameter => $replacement) {
                $output = str_replace(static::$replacementPattern[0] . $parameter . static::$replacementPattern[1], (string)$replacement, $output);
            }
        }

        return $output;
    }

    public static function receiving($locale, $file, $parsedLine)
    {
        $output = static::$language[$locale][$file][$parsedLine] ?? null;
        if ($output !== null) {
            return $output;
        }

        foreach (explode('.', $parsedLine) as $row) {
            if (!isset($current)) {
                $current = static::$language[$locale][$file] ?? null;
            }

            $output = $current[$row] ?? null;
            if (is_array($output)) {
                $current = $output;
            }
        }

        if ($output !== null) {
            return $output;
        }

        $row = current(explode('.', $parsedLine));
        $key = substr($parsedLine, strlen($row) + 1);

        return static::$language[$locale][$file][$row][$key] ?? null;
    }

    public static function analyze($line, $locale)
    {
        $file = substr($line, 0, strpos($line, '.'));
        $line = substr($line, strlen($file) + 1);

        if (!isset(static::$language[$locale][$file]) || !array_key_exists($line, static::$language[$locale][$file])) {
            self::lines($file, $locale);
        }

        return [$file, $line];
    }

    public static function lines($file, $locale, bool $return = false)
    {
        if (!array_key_exists($locale, static::$loadedFiles)) {
            static::$loadedFiles[$locale] = [];
        }

        if (in_array($file, static::$loadedFiles[$locale], true)) {
            // Не загружаем более 1 раза
            return [];
        }

        if (!array_key_exists($locale, static::$language)) {
            static::$language[$locale] = [];
        }

        if (!array_key_exists($file, static::$language[$locale])) {
            static::$language[$locale][$file] = [];
        }

        $path = HLEB_GLOBAL_DIR . '/app/' . "Languages/{$locale}/{$file}.php";

        $lang = self::requireFile($path);

        if ($return) {
            return $lang;
        }

        static::$loadedFiles[$locale][] = $file;

        // Объединить нашу строку
        static::$language[$locale][$file] = $lang;
    }

    // Загрузка файла
    public static function requireFile(string $path): array
    {
        $strings = [];
        if (file_exists($path)) {
            $strings[] = require $path;
        }

        if (isset($strings[1])) {
            $strings = array_replace_recursive(...$strings);
        } elseif (isset($strings[0])) {
            $strings = $strings[0];
        }

        return $strings;
    }
}
