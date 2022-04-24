<?php

declare(strict_types=1);

/*
 * Global "helper" functions.
 *
 * Глобальные «вспомогательные» функции.
 */

if (! function_exists('__')) 
{
    // @param  string|null $key
    function __(string $key = null, array $params = []) {
        if (is_null($key)) {
            return $key;
        }

        return Translate::get($key, $params);
    }
}

if (! function_exists('url')) 
{
    // @param  string|null $key
    function url(string $key = null, array $params = []) {
        if (is_null($key)) {
            return $key;
        }

        return getUrlByName($key, $params);
    }
}