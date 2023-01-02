<?php

declare(strict_types=1);

use Hleb\Constructor\Handlers\Request;

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

function post_slug(int $id, string $slug = '')
{   
    if (config('meta.slug_post') == false) {
        return hleb_get_by_name('post_id', ['id' => $id]);
    }
    
    return hleb_get_by_name('post', ['id' => $id, 'slug' => $slug]);
}

function config(string $key = null)
{
    if ($key === null) {
        return $key;
    }

    return Configuration::get($key);
}

function setting(string $key = null)
{
    if ($key === null) {
        return $key;
    }

    return \Modules\Admin\App\Setting::get($key);
}

function is_current($url)
{
    $uri = Request::getUri();
    if ($url == $uri) return true;
    
    $a = explode('?', $uri);  
    if ($url == $a[0]) return true;
 
    return false;
}

function insert(string $name, array $params = [])
{
    return \App\Controllers\Controller::insert($name, $params);
}

function markdown(string $content, string $type = 'text')
{
    return \App\Services\Parser\Content::text($content, $type);
}

function fragment(string $content, int $limit = 0)
{
    return \App\Services\Parser\Filter::noHTML($content, $limit);
}

function is_return(string $text, string $status, string $redirect = '/')
{
    Msg::add($text, $status);
    redirect($redirect);
}

function hook_action(string $name, array $params = [])
{
    return App\Hook\Hook::action($name, $params);
}

function hook_filter(string $name, string $data, array $params = [])
{
    return App\Hook\Hook::filter($name, $data, $params);
}

// mixed $params (c PHP 8.0)
function notEmptyOrView404($params)
{
    if (empty($params)) {
        include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
        hl_preliminary_exit();
    }
    return true;
}
