<?php

declare(strict_types=1);

/*
 * Global "helper" functions.
 *
 * Глобальные «вспомогательные» функции.
 */

if (!function_exists('__')) {
    // @param  string|null $key
    function __(string $key = null, array $params = [])
    {
        if (is_null($key)) {
            return $key;
        }

        return Translate::get($key, $params);
    }
}

function url(string $key = null, array $params = [])
{
    if (is_null($key)) {
        return $key;
    }

    return hleb_get_by_name($key, $params);
}

function config(string $key = null)
{
    if (is_null($key)) {
        return $key;
    }

    return Config::get($key);
}

function is_current($url)
{
    if ($url == Request::getUri()) return true;

    /* $segments = explode('/', Request::getUri());
    foreach ($segments as $key => $segment) {
      if ($url == '/' . $segment) return 'active';
    } */
    
    return false;
}