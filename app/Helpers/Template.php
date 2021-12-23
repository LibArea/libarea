<?php

//declare(strict_types = 1);

// Topic for posts
function html_topic($topic, $slug, $css)
{
    if (!$topic) {
        return '';
    }

    if (!is_array($topic)) {
        $topic = preg_split('/(@)/', $topic);
    }

    $result = [];
    foreach (array_chunk($topic, 3) as $ind => $row) {
        if ($row[0] == 'topic') {
            $result[] = '<a class="' . $css . '" href="' . getUrlByName($slug, ['slug' => $row[1]]) . '">' . $row[2] . '</a>';
        }
    }

    return implode($result);
}

function html_blog($topic, $slug, $css)
{
    if (!$topic) {
        return '';
    }

    if (!is_array($topic)) {
        $topic = preg_split('/(@)/', $topic);
    }

    $result = [];
    foreach (array_chunk($topic, 3) as $ind => $row) {

        if ($row[0] == 'blog') {
            $result[] = '<a class="' . $css . ' blue" href="' . getUrlByName('blog', ['slug' => $row[1]]) . '">' . $row[2] . '</a>';
        }
    }
    return implode($result);
}

// Topic or Blog logo img
function facet_logo_img($file, $size, $alt, $style)
{
    $src = AG_PATH_FACETS_LOGOS . $file;
    if ($size == 'small') {
        $src = AG_PATH_FACETS_SMALL_LOGOS . $file;
    }

    $img = '<img class="' . $style . '" src="' . $src . '" alt="' . $alt . '">';

    return $img;
}

// User's Avatar
function user_avatar_img($file, $size, $alt, $style)
{
    $src = AG_PATH_USERS_AVATARS . $file;
    if ($size == 'small') {
        $src = AG_PATH_USERS_SMALL_AVATARS . $file;
    }

    $img = '<img class="' . $style . '" src="' . $src . '" alt="' . $alt . '">';

    return $img;
}

// Cover of users, blog 
function cover_url($file, $type)
{
    if ($type == 'blog') {
        return AG_PATH_BLOGS_COVER . $file;
    }

    return AG_PATH_USERS_COVER . $file;
}

// User's Cover art or thumbnails
function post_img($file, $alt, $style, $type, $attributes = '')
{
    $src = AG_PATH_POSTS_COVER . $file;
    if ($type == 'thumbnails') {
        $src = AG_PATH_POSTS_THUMB . $file;
    }

    if ($attributes) {
        $attributes = 'layer-src="' . $src . '"';
    }

    $img = '<img class="' . $style . '" ' . $attributes . ' src="' . $src . '" alt="' . $alt . '">';

    return $img;
}

function favicon_img($link_id, $alt)
{
    if (file_exists(HLEB_PUBLIC_DIR . AG_PATH_FAVICONS . $link_id . '.png')) {
        $img = '<img class="mr5 w18 h18" src="' . AG_PATH_FAVICONS . $link_id . '.png" alt="' . $alt . '">';
        return $img;
    }

    $img = '<img class="mr5 w18 h18" src="' . AG_PATH_FAVICONS . 'no-link.png" alt="' . $alt . '">';
    return $img;
}

function thumbs_img($url_domain, $alt, $css)
{
    if (file_exists(HLEB_PUBLIC_DIR . AG_PATH_THUMBS . $url_domain . '.png')) {
        $img = '<img class="' . $css . '" src="' . AG_PATH_THUMBS . $url_domain . '.png" alt="' . $alt . '">';
        return $img;
    }

    $img = '<img class="mr5 w200" src="' . AG_PATH_THUMBS . 'default.png" alt="' . $alt . '">';
    return $img;
}

// Localization of dates and events....
function lang_date($string)
{
    $months = Translate::get('months');

    //Разбиваем дату в массив
    $a = preg_split('/[^\d]/', $string);
    $today = date('Ymd');  //20210421

    if (($a[0] . $a[1] . $a[2]) == $today) {
        //Если сегодня
        return (Translate::get('today') . ' ' . $a[3] . ':' . $a[4]);
    } else {
        $b = explode('-', date("Y-m-d"));
        $tom = date('Ymd', mktime(0, 0, 0, $b[1], $b[2] - 1, $b[0]));
        if (($a[0] . $a[1] . $a[2]) == $tom) {
            //Если вчера
            return (Translate::get('yesterday') . ' ' . $a[3] . ':' . $a[4]);
        } else {
            //Если позже
            $mm = intval($a[1]);
            return ($a[2] . " " . $months[$mm] . " " . $a[0] . " " . $a[3] . ":" . $a[4]);
        }
    }
}

// @param array $words: array('пост', 'поста', 'постов')
function num_word($value, $words, $show = true)
{
    $num = (int)$value % 100;
    if ($num > 19) {
        $num = $num % 10;
    }

    $out = ($show) ? (int)$value . ' ' : '';
    switch ($num) {
        case 1:
            $out .= $words[0];
            break;
        case 2:
        case 3:
        case 4:
            $out .= $words[1];
            break;
        default:
            $out .= $words[2];
            break;
    }

    return $out;
}

function pagination($pNum, $pagesCount, $sheet, $other)
{
    if ($pNum > $pagesCount) {
        return null;
    }

    $other = empty($other) ? '' : $other;
    $first = empty($other) ? '/' : $other;

    if ($sheet == 'all' || $sheet == 'top') {
        $page  = $other . '/' . $sheet;
        $first = $other . '/' . $sheet;
    } else {
        $page = $other . '';
    }

    $html = '<div class="mt5 mb5 gray">';

    if ($pNum != 1) {
        if (($pNum - 1) == 1) {
            $html .= '<a class="pr5 mr5" href="' . $first . '"><< ' . ($pNum - 1) . '</a>';
        } else {
            $html .= '<a class="pr5 mr5" href="' . $page . '/page/' . ($pNum - 1) . '"><< ' . ($pNum - 1) . '</a>';
        }
    }

    if ($pagesCount > $pNum) {
        $html .= '<span class="bg-red-500 pt5 pr10 pb5 pl10 white ml5 mr5 size-15">' . ($pNum) . '</span>';
    }

    if ($pagesCount > $pNum) {
        if ($pagesCount > $pNum + 1) {
            $html .= '<a class="p5" href="' . $page . '/page/' . ($pNum + 1) . '"> ' . ($pNum + 1) . ' </a>';
        }

        if ($pagesCount > $pNum + 2) {
            $html .= '<a class="p5" href="' . $page . '/page/' . ($pNum + 2) . '"> ' . ($pNum + 2) . '</a>';
        }

        if ($pagesCount > $pNum + 3) {
            $html .= '...';
        }

        $html .= '<a class="p5 ml5 size-15 lowercase gray-light" href="' . $page . '/page/' . ($pNum + 1) . '">' . Translate::get('page') . ' ' . ($pNum + 1) . ' >></a>';
    }

    $html .= '</div>';

    return $html;
}

function breadcrumb($path_home, $title_home, $path_intermediate, $title_intermediate, $title_page)
{
    $html = '<ul class="flex flex-row gap-2 list-none items-center size-13 p0 mt0 mb15">';
    $html .= '<li><a class="gray" title="' . $title_home . '" href="' . $path_home . '">' . $title_home . '</a><li class="gray-light-2">/</li>';

    if ($path_intermediate) {
        $html .= '<li><a class="gray" title="' . $title_intermediate . '" href="' . $path_intermediate . '">' . $title_intermediate . '</a></li><li class="gray-light-2">/</li>';
    }

    $html .= '<li><span class="red">' . $title_page . '</span></li></ul>';

    return $html;
}

function votes($user_id, $content, $type, $css = '', $block = '')
{
    $html  = '';
    $count = '';
    if ($content[$type . '_votes'] > 0) {
        $count = $content[$type . '_votes'];
    }

    if ($user_id > 0) {
        if ($content['votes_' . $type . '_user_id'] || $user_id == $content[$type . '_user_id']) {
            $html .= '<div class="voters blue flex ' . $block . ' gray-light-2 center">
                        <div class="up-id bi bi-heart ' . $css . '"></div>
                        <div class="score">
                            ' . $count . '
                        </div></div>';
        } else {
            $num_count = empty($count) ? 0 : $count;
            $html .= '<div id="up' . $content[$type . '_id'] . '" class="voters blue-hover flex ' . $block . ' center gray-light-2">
                        <div data-id="' . $content[$type . '_id'] . '" data-count="' . $num_count . '" data-type="' . $type . '" class="up-id bi bi-heart ' . $css . '"></div>
                        <div class="score">
                            ' . $count . '
                        </div></div>';
        }
    } else {
        $html .= '<div class="voters blue-hover flex ' . $block . ' center gray-light-2">
                    <div class="up-id bi bi-heart ' . $css . ' click-no-auth"></div>
                    <div class="score">
                         ' . $count . '                
                    </div></div>';
    }

    return $html;
}

function favorite_post($user_id, $post_id, $favorite_tid, $css = '')
{
    $html  = '';
    if ($user_id > 0) {
        $blue = $favorite_tid ? 'blue' : '';
        $my   = $favorite_tid ? 'bi bi-bookmark-dash' : 'bi-bookmark-plus';
        $html .= '<span id="favorite_' . $post_id . '" class="add-favorite ' . $blue . ' ' . $css . ' gray-light feed-icon" data-id="' . $post_id . '" data-type="post"><i class="bi ' . $my . ' middle"></i></span>';
    } else {
        $html .= '<span class="click-no-auth gray-light-2 feed-icon ' . $css . '">
                    <i class="bi bi-bookmark-plus middle"></i>
                        </span>';
    }

    return $html;
}

function tabs_nav($user_id, $sheet, array $pages = [])
{
    $html = '';
    foreach ($pages as $key => $page) {
        if (empty($page['auth']) != false || $user_id > 0) {
            if ($page['id'] == $sheet) {
                $html .= '<li class="blue ml30 mb-mr-5 mb-ml-10">
                    <i class="' .  $page['icon'] . ' mr5"></i>
                    <span class="mb-size-13">' . $page['content'] . '</span></li>';
            } else {
                $html .= '<li class="ml30 mb-mr-5 mb-ml-10">
                    <a class="gray" href="' . $page['url'] . '">
                    <i class="' . $page['icon'] . ' mr5"></i>
                    <span class="mb-size-13">' . $page['content'] . '</span></a></li>';
            }
        }
    }

    return $html;
}

// Проверка доступа
// $content
// $type -  post / answer / comment
// $after - есть ли ответы
// $stop_time - разрешенное время
function accessСheck($content, $type, $uid, $after, $stop_time)
{
    if (!$content) {
        return false;
    }

    // Доступ получает только автор и админ
    if ($content[$type . '_user_id'] != $uid['user_id'] && $uid['user_trust_level'] != 5) {
        return false;
    }

    // Запретим удаление если есть ответ
    // И если прошло 30 минут
    if ($uid['user_trust_level'] != 5) {

        if ($after > 0) {
            if ($content[$type . '_after'] > 0) {
                return false;
            }
        }

        if ($stop_time > 0) {
            $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($content[$type . '_date']);
            $time = floor($diff / 60);

            if ($time > $stop_time) {
                return false;
            }
        }
    }

    return true;
}

function add_post($facet, $user_id)
{
    $url_add = getUrlByName('post.add');
    if (!empty($facet)) {
        if ($facet['facet_user_id'] == $user_id || $facet['facet_type'] == 'topic') {
            $url_add = $url_add . '/' . $facet['facet_id'];
        }
    }

    $html  = '<a title="' . Translate::get('add post') . '" href="' . $url_add . '" class="blue center p10">
                <i class="bi bi-plus-lg size-18"></i>
              </a>';

    return $html;
}

// Обрезка текста по словам
function cutWords($content, $maxlen)
{
    $words = preg_split('#[\s\r\n]+#um', $content);
    if ($maxlen < count($words)) {
        $words = array_slice($words, 0, $maxlen);
    }
    $code_match = array('>', '*', '!', '~', '`', '[ADD:');
    $words      = str_replace($code_match, '', $words);
    return join(' ', $words);
}

function fragment($content, $maxlen = '20')
{
    $text = explode("\n", $content);
    $words = preg_split('#[\s\r\n]+#um', $text[0]);

    if ($maxlen < count($words)) {
        $words = array_slice($words, 0, $maxlen);
        return join(' ', $words) . '...';
    }

    return $text[0];
}

function no_content($text, $icon)
{
    $html  = '<div class="mt10 mb10 pt10 pr15 pb10 pl15 bg-yellow-100 gray">
                <i class="' . $icon . ' middle mr5"></i>
                <span class="middle">' . $text . '...</span>
              </div>';

    return $html;
}

function sumbit($text)
{
    $html  = '<button type="submit" class="btn btn-primary">' . $text . '</button>';

    return $html;
}

function getMsg()
{
    if (isset($_SESSION['msg'])) {
        $msg = $_SESSION['msg'];
    } else {
        $msg = false;
    }

    clearMsg();
    return $msg;
}

function clearMsg()
{
    unset($_SESSION['msg']);
}

function addMsg($msg, $class)
{
    $class = ($class == 'error') ? 'error' : 'success';
    $_SESSION['msg'][] = array($msg, $class);
}


function pageError404($variable)
{
    if (!$variable) {
        include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
        hl_preliminary_exit();
    }
    return true;
}

function pageRedirection($variable, $redirect)
{
    if (!$variable) {
        redirect($redirect);
    }
    return true;
}

// Создать случайную строку
function randomString($type, int $len = 8)
{
    if ($type = 'crypto') {
        return bin2hex(random_bytes($len / 2));
    }

    // sha1
    return sha1(uniqid((string) mt_rand(), true));
}
