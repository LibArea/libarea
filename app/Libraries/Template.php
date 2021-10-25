<?php

// Localization
function lang($text)
{ 
    // print_r('1');
    if (isset(FILE_LANG[$text])) {
        return FILE_LANG[$text];
    }  
    return $text;
}

// Topic for posts
function html_topic($topic, $slug, $css)
{
    if (!$topic) {
        return '';
    }

    if (!is_array($topic)) {
        $topic = preg_split('/(@)/', $topic);
    }

    $result = array();
    foreach (array_chunk($topic, 2) as $ind => $row) {
        $result[] = '<a class="' . $css . '" href="' . getUrlByName($slug, ['slug' => $row[0]]) . '">' . $row[1] . '</a>';
    }
    return implode($result);
}

// Topic logo img
function topic_logo_img($file, $size, $alt, $style)
{
    $src = AG_PATH_TOPICS_LOGOS . $file;
    if ($size == 'small') {
        $src = AG_PATH_TOPICS_SMALL_LOGOS . $file;
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

// User's Cover art
function user_cover_url($file)
{
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
        $img = '<img class="mr5 w18 h18" src="'. AG_PATH_FAVICONS . $link_id . '.png" alt="' . $alt . '">';
        return $img;
    }

    $img = '<img class="mr5 w18 h18" src="'. AG_PATH_FAVICONS . 'no-link.png" alt="' . $alt . '">';
    return $img;
}

function thumbs_img($url_domain, $alt)
{
    if (file_exists(HLEB_PUBLIC_DIR . AG_PATH_THUMBS . $url_domain . '.png')) {
        $img = '<img class="mr5 w200 box-shadow" src="'. AG_PATH_THUMBS . $url_domain . '.png" alt="' . $alt . '">';
        return $img;
    }

    $img = '<img class="mr5 w200" src="'. AG_PATH_THUMBS . 'default.png" alt="' . $alt . '">';
    return $img;
}

// Localization of dates and events....
function lang_date($string)
{
    $monn = array(
        '',
        lang('january'),
        lang('february'),
        lang('martha'),
        lang('april'),
        lang('may'),
        lang('june'),
        lang('july'),
        lang('august'),
        lang('september'),
        lang('october'),
        lang('november'),
        lang('december')
    );
    //Разбиваем дату в массив
    $a = preg_split('/[^\d]/', $string);

    $today = date('Ymd');  //20210421
    if (($a[0] . $a[1] . $a[2]) == $today) {
        //Если сегодня
        return (lang('today') . ' ' . $a[3] . ':' . $a[4]);
    } else {
        $b = explode('-', date("Y-m-d"));
        $tom = date('Ymd', mktime(0, 0, 0, $b[1], $b[2] - 1, $b[0]));
        if (($a[0] . $a[1] . $a[2]) == $tom) {
            //Если вчера
            return (lang('yesterday') . ' ' . $a[3] . ':' . $a[4]);
        } else {
            //Если позже
            $mm = intval($a[1]);
            return ($a[2] . " " . $monn[$mm] . " " . $a[0] . " " . $a[3] . ":" . $a[4]);
        }
    }
}

// Declensions
function word_form($num, $form_for_1, $form_for_2, $form_for_5)
{
    $num = abs($num) % 100;
    $num_x = $num % 10;
    if ($num > 10 && $num < 20)   // отрезок [11;19]
        return $form_for_5;
    if ($num_x > 1 && $num_x < 5) //  2,3,4
        return $form_for_2;
    if ($num_x == 1)              // оканчивается на 1
        return $form_for_1;
    return $form_for_5;
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
        $html .= '<span class="bg-red-700 pt5 pr10 pb5 pl10 white ml5 mr5 size-15">' . ($pNum) . '</span>';
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

        $html .= '<a class="p5 ml5 size-15 lowercase gray-light" href="' . $page . '/page/' . ($pNum + 1) . '">' . lang('page') . ' ' . ($pNum + 1) . ' >></a>';
    }

    $html .= '</div>';

    return $html;
}

function breadcrumb($path_home, $title_home, $path_intermediate, $title_intermediate, $title_page)
{
    $html = '<ul class="breadcrumb size-14 p0 mt0 mb15">';
    $html .= '<li class="breadcrumb-item inline m0 pt5 pr0 pn5 pl0 gray">
                <a title="' . $title_home . '" href="' . $path_home . '">' . $title_home . '</a>
              </li>';

    if ($path_intermediate) {
        $html .= '<li class="breadcrumb-item inline m0 pt5 pr0 pn5 pl0 gray">
                    <a title="' . $title_intermediate . '" href="' . $path_intermediate . '">' . $title_intermediate . '</a>
                  </li>';
    }

    $html .= '<li class="breadcrumb-item inline m0 pt5 pr0 pn5 pl0 gray">
                <span class="red">' . $title_page . '</span>
                </li>
                </ul>';
            //  <h1>' . $title_page . '</h1>';

    return $html;
}

function votes($user_id, $content, $type)
{
    $html  = '';
    $count = '';
    if ($content[$type . '_votes'] > 0) {
        $count = $content[$type . '_votes'];
    }

    if ($user_id > 0) {
        if ($content['votes_' . $type . '_user_id'] || $user_id == $content[$type . '_user_id']) {
            $html .= '<div class="voters active flex gray-light-2 flex-center">
                        <div class="up-id bi bi-heart"></div>
                        <div class="score ml5">
                            ' . $count . '
                        </div></div>';
        } else {
            $num_count = empty($count) ? 0 : $count;
            $html .= '<div id="up' . $content[$type . '_id'] . '" class="voters flex flex-center gray-light-2">
                        <div data-id="' . $content[$type . '_id'] . '" data-count="' . $num_count . '" data-type="' . $type . '" class="up-id bi bi-heart"></div>
                        <div class="score ml5">
                            ' . $count . '
                        </div></div>';
        }
    } else {
        $html .= '<div class="voters flex flex-center gray-light-2">
                    <div class="up-id bi bi-heart click-no-auth"></div>
                    <div class="score ml5">
                         ' . $count . '                
                    </div></div>';
    }

    return $html;
}

function favorite_post($user_id, $post_id, $favorite_tid)
{
    $html  = '';
    if ($user_id > 0) {
        $blue = $favorite_tid ? 'blue' : '';
        $html .= '<span id="favorite_'. $post_id .'" class="add-favorite '. $blue .' gray-light feed-icon" data-id="' . $post_id . '" data-type="post"><i class="bi bi-bookmark middle"></i></span>';
    } else {
        $html .= '<span class="click-no-auth gray-light-2 feed-icon">
                    <i class="bi bi-bookmark middle"></i>
                        </span>'; 
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
    $class = ($class == 'error') ? 2 : 1;
    $_SESSION['msg'][] = array($msg, $class);
}