<?php

// localization class
class Translate
{
    protected static $localesDir =  HLEB_GLOBAL_DIRECTORY . '/app/Language/';

    protected static $defaultLang = 'ru';

    protected static $loadedLocales = [];

    protected static $currentLang = '';

    protected static $replacementPattern = ['{', '}'];

    // Get the language used
    public static function getLang()
    {
        return self::$defaultLang;
    }

    // Returns the translation of a specific key from the current language locale.
    // Возвращает перевод определенного ключа из текущей языковой локали.
    public static function get($localeKey, $parameters = [])
    {
       // static::checkInitialization();
        static::checkLoaded();

        if (is_string($localeKey) && !empty(static::$loadedLocales[static::$currentLang][$localeKey])) {
            $text = static::$loadedLocales[static::$currentLang][$localeKey];

            if (!empty($parameters) && is_array($parameters)) {
                foreach ($parameters as $parameter => $replacement) {
                    $text = str_replace(static::$replacementPattern[0] . $parameter . static::$replacementPattern[1], $replacement, $text);
                }
            }

            return $text;
        }

        return null;
    }

    // Set by default, and after authorization of the participant
    public static function setLang($language)
    {
       // static::checkInitialization();
        static::$currentLang = (!empty($language) && is_string($language)) ? $language : static::getClientLang();
        static::checkLoaded();
    }

    // Define the user agent
    protected static function getClientLang()
    {
        return !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5) : null;
    }

    // Next, checking and loading...
    protected static function checkLoaded()
    {
        if (empty(static::$loadedLocales[static::$currentLang])) {
            static::checkLang();
            static::$loadedLocales[static::$currentLang] = require(static::$localesDir . '/' . static::$currentLang . '.php');
        }
    }

    protected static function checkDir()
    {
        if (!is_dir(static::$localesDir)) {
            throw new \Exception('Directory "' . static::$localesDir . '" not found!');
        }
    }

    protected static function checkLocale()
    {
        if (!file_exists(static::$localesDir . '/' . static::$defaultLang . '.php')) {
            throw new \Exception('Default language locale not found!');
        }
    }

    protected static function checkLang()
    {
        if (!file_exists(static::$localesDir . '/' . static::$currentLang . '.php')) {
            static::$currentLang = static::$defaultLang;
        }
    }
}
