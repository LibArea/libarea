<?php

declare(strict_types=1);

/*
 * Global "helper" functions.
 *
 * Глобальные «вспомогательные» функции.
 */

// @param  string|null $key
function __(string $key = null, array $params = [])
{
    if ($key === null) {
        return $key;
    }

    return Translate::get($key, $params);
}

function url(string $key = null, array $params = [])
{
    if ($key === null) {
        return $key;
    }

    return hleb_get_by_name($key, $params);
}

function config(string $key = null)
{
    if ($key === null) {
        return $key;
    }

    return Configuration::get($key);
}

function is_current($url)
{
    if ($url == Request::getUri()) return true;

    return false;
}

function insert(string $name, array $params = [])
{
    return App\Controllers\Controller::insert($name, $params);
}

function component(string $name, array $params = [])
{
    return App\Controllers\Controller::insert('/_block/form/components/' . $name, $params);
}

function is_return(string $text, string $status, $redirect = '/')
{
    Msg::add($text, $status);
    redirect($redirect);
}
