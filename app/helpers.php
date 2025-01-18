<?php

declare(strict_types=1);

use Hleb\Static\Request;
use Hleb\Static\Container;
use App\Bootstrap\Services\User\UserData;

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

function post_slug(int $id, string $slug = '')
{
    if (config('meta', 'slug_post') == false) {
        return url('post.id', ['id' => $id]);
    }

    return url('post', ['id' => $id, 'slug' => $slug]);
}

function is_current($url)
{
    $uri = Request::getUri()->getPath();

    if ($url == $uri) return true;

    $a = explode('?', $uri);
    if ($url == $a[0]) return true;

    return false;
}

function insert(string $hlTemplatePath, array $params = [])
{
	$params['container'] = Container::getContainer();
	
    extract($params);

    unset($params);

    $tpl_puth = UserData::getUserTheme() . DIRECTORY_SEPARATOR . trim($hlTemplatePath, '/\\');
    if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $tpl_puth . '.php')) {
        $tpl_puth = 'default' . $hlTemplatePath;
    }

    require TEMPLATES . DIRECTORY_SEPARATOR . $tpl_puth . '.php';
}

function render(string $name, array $data = [], string $part = 'base')
{
    closing();

    $body = UserData::getUserTheme() . DIRECTORY_SEPARATOR;
    $header = $body . '/global/' . $part . '-header';
    $footer = $body . '/global/' . $part . '-footer';

    if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $body . '/content/' . $name . '.php')) {
        $body = 'default';
    }

    if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $header . '.php')) {
        $header = 'default/global/' . $part . '-header';
    }

    if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $footer . '.php')) {
        $footer = 'default/global/' . $part . '-footer';
    }

    $data['topics_user'] = UserData::getUserFacets();

    echo view($header, $data);
    echo view($body . '/content/' . $name, $data);
    echo view($footer, $data);
}

function closing()
{
    if (config('general', '.site_disabled')  && !UserData::checkAdmin()) {
        insert('site-off');
        exit();
    }

    return true;
}

function markdown(string $content, string $type = 'text')
{
    return App\Content\Parser\Content::text($content, $type);
}

function fragment(string $content, int $limit = 0)
{
    return \App\Content\Parser\Filter::noHTML($content, $limit);
}

function notEmptyOrView404($params)
{
    if (empty($params)) {
        echo view('error', ['httpCode' => 404, 'message' => __('404.page_not') .' <br> '. __('404.page_removed')]);
        exit();
    }
    return true;
}

function host(string $url)
{
    $parse  =  parse_url($url);
    return $parse['host'] ?? false;
}

function htmlEncode($text)
{
    return htmlspecialchars($text ?? '', ENT_QUOTES);
}

function avatar($file, $alt, $style, $size)
{
    return Img::avatar($file, $alt, $style, $size);
}

function getUserAvatar()
{
	return UserData::getUserAvatar();
}

function langDate($time)
{
    return Html::langDate($time);
}

function breadcrumb($arrey)
{
    return Html::breadcrumb($arrey);
}

function pagination($pNum, $pagesCount, $sheet, $other, $sign = '?', $sort = null)
{
    return Html::pagination($pNum, $pagesCount, $sheet, $other, $sign, $sort);
}

function redirect(string $url): void
{
    $container = Container::getContainer();
    $container->redirect()->to($url, status: 303);
}
