<?php

class Html
{
    // Blog, topic or category
    public static function facets($facet, $type, $url, $css, $choice = true)
    {
        if (!$facet) {
            return '';
        }

        if (!is_array($facet)) {
            $facet = preg_split('/(@)/', $facet);
        }

        $result = [];
        foreach (array_chunk($facet, 3) as $ind => $row) {
            if ($row[0] == $type) {
                if ($type == 'category') {
                    $result[] = '<a class="' . $css . '" href="' . getUrlByName($url, ['cat' => $choice, 'slug' => $row[1]]) . '">' . $row[2] . '</a>';
                } else {
                    $result[] = '<a class="' . $css . '" href="' . getUrlByName($url, ['slug' => $row[1]]) . '">' . $row[2] . '</a>';
                }
            }
        }

        return implode($result);
    }

    // Blog, topic or category
    public static function addPost($facet, $user_id)
    {
        $url_add = getUrlByName('content.add', ['type' => 'post']);
        if (!empty($facet)) {
            if ($facet['facet_user_id'] == $user_id || $facet['facet_type'] == 'topic') {
                $url_add = $url_add . '/' . $facet['facet_id'];
            }
        }

        $html  = '<a title="' . sprintf(Translate::get('add.option'), Translate::get('post')) . '" 
                    href="' . $url_add . '" class="sky">
                    <i class="bi-plus-lg text-xl"></i>
                  </a>';

        return $html;
    }

    // User's Cover art or thumbnails
    public static function image($file, $alt, $style, $type, $size)
    {
        if ($type == 'post') {
            $img = $size == 'thumbnails' ? PATH_POSTS_THUMB . $file : PATH_POSTS_COVER . $file;
        } elseif ($type == 'logo') {
            $img = $size == 'small' ? PATH_FACETS_SMALL_LOGOS . $file : PATH_FACETS_LOGOS . $file;
        } else {
            $img = $size == 'small' ? PATH_USERS_SMALL_AVATARS . $file : PATH_USERS_AVATARS . $file;
        }

        $img = '<img class="' . $style . '" src="' . $img . '" title="' . $alt . '" alt="' . $alt . '">';

        return $img;
    }

    // Icons, screenshots associated with the site
    public static function websiteImage($domain, $type, $alt, $css = '')
    {
        $path = PATH_FAVICONS;
        $w_h = 'favicons';
        if ($type == 'thumbs') {
            $path  = PATH_THUMBS;
            $w_h = 'w200 h200';
        }

        if (file_exists(HLEB_PUBLIC_DIR . $path . $domain . '.png')) {
            $img = '<img class="' . $css . '" src="' . $path . $domain . '.png" title="' . $alt . '" alt="' . $alt . '">';
            return $img;
        }

        $img = '<img class="mr5 ' . $w_h . $css . '" src="' . $path . 'no-link.png" title="' . $alt . '" alt="' . $alt . '">';

        return $img;
    }

    // Cover of users, blog 
    public static function coverUrl($file, $type)
    {
        if ($type == 'blog') {
            return PATH_BLOGS_COVER . $file;
        }

        return PATH_USERS_COVER . $file;
    }

    // Localization of dates and events....
    public static function langDate($string)
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

    // Getting a piece of text
    public static function fragment($content, $maxlen = '20')
    {
        $text = explode("\n", $content);
        $words = preg_split('#[\s\r\n]+#um', $text[0]);

        if ($maxlen < count($words)) {
            $words = array_slice($words, 0, $maxlen);
            return join(' ', $words) . '...';
        }

        return $text[0];
    }

    // Voting for posts, replies, comments and sites
    public static function votes($user_id, $content, $type, $ind, $css = '', $block = '')
    {
        $html  = '';
        $count = '';
        if ($content[$type . '_votes'] > 0) {
            $count = $content[$type . '_votes'];
        }

        if ($user_id > 0) {
            if ($content['votes_' . $type . '_user_id'] || $user_id == $content[$type . '_user_id']) {
                $html .= '<div class="voters sky flex ' . $block . ' center">
                            <div class="up-id bi-heart ' . $css . '"></div>
                            <div class="score">
                                ' . $count . '
                            </div></div>';
            } else {
                $num_count = empty($count) ? 0 : $count;
                $html .= '<div id="up' . $content[$type . '_id'] . '" class="voters-' . $ind . '  flex ' . $block . ' center gray-600">
                            <div data-ind="' . $ind . '" data-id="' . $content[$type . '_id'] . '" data-count="' . $num_count . '" data-type="' . $type . '" class="up-id bi-heart ' . $css . '"></div>
                            <div class="score">
                                ' . $count . '
                            </div></div>';
            }
        } else {
            $html .= '<div class="voters flex ' . $block . ' center gray-600">
                        <div class="up-id bi-heart ' . $css . ' click-no-auth"></div>
                        <div class="score">
                             ' . $count . '                
                        </div></div>';
        }

        return $html;
    }

    // Add/remove from favorites
    public static function favorite($user_id, $content_id, $type, $tid, $ind, $css = '')
    {
        $html  = '';
        if ($user_id > 0) {
            $blue = $tid ? 'sky' : 'gray-600';
            $my   = $tid ? 'bi-bookmark-dash' : 'bi-bookmark-plus';
            $html .= '<span id="favorite_' . $content_id . '" class="add-favorite fav-' . $ind . ' ' . $blue . ' ' . $css . '" data-ind="' . $ind . '" data-id="' . $content_id . '" data-type="' . $type . '"><i class="' . $my . ' middle"></i></span>';
        } else {
            $html .= '<span class="click-no-auth gray-600 ' . $css . '">
                        <i class="bi-bookmark-plus middle"></i>
                            </span>';
        }

        return $html;
    }

    // Subscription: groups, blogs, posts, directory
    public static function signed($arr)
    {
        $html  = '';
        if ($arr['user_id'] > 0) {
            if ($arr['content_user_id'] != $arr['user_id']) {
                if ($arr['state']) {
                    $html .= '<div data-id="' . $arr['id'] . '" data-type="' . $arr['type'] . '" class="focus-id yes">' . Translate::get('unsubscribe') . '</div>';
                } else {
                    $html .= '<div data-id="' . $arr['id'] . '" data-type="' . $arr['type'] . '" class="focus-id no">+ ' . Translate::get('read') . '</div>';
                }
            }
        } else {
            $html .= '<a href="' . getUrlByName('login') . '"><div class="focus-id no">+ ' . Translate::get('read') . '</div></a>';
        }

        return $html;
    }

    // Page pagination
    public static function pagination($pNum, $pagesCount, $sheet, $other)
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

        $html = '<p class="gray">';

        if ($pNum != 1) {
            if (($pNum - 1) == 1) {
                $html .= '<a class="pr5 mr5" href="' . $first . '"><< ' . ($pNum - 1) . '</a>';
            } else {
                $html .= '<a class="pr5 mr5" href="' . $page . '/page/' . ($pNum - 1) . '"><< ' . ($pNum - 1) . '</a>';
            }
        }

        if ($pagesCount > $pNum) {
            $html .= '<span class="bg-green pt5 pr10 pb5 pl10 white ml5 mr5">' . ($pNum) . '</span>';
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

            $html .= '<a class="p5 ml5 lowercase gray-600" href="' . $page . '/page/' . ($pNum + 1) . '">' . Translate::get('page') . ' ' . ($pNum + 1) . ' >></a>';
        }

        $html .= '</p>';

        return $html;
    }

    // Trimming text by words
    public static function cutWords($content, $maxlen)
    {
        $words = preg_split('#[\s\r\n]+#um', $content);
        if ($maxlen < count($words)) {
            $words = array_slice($words, 0, $maxlen);
        }
        $code_match = array('>', '*', '!', '~', '`', '[ADD:');
        $words      = str_replace($code_match, '', $words);
        return join(' ', $words);
    }

    public static function getMsg()
    {
        if (isset($_SESSION['msg'])) {
            $msg = $_SESSION['msg'];
        } else {
            $msg = false;
        }

        unset($_SESSION['msg']);

        return $msg;
    }

    public static function addMsg($msg, $class)
    {
        $class = ($class == 'error') ? 'error' : 'success';
        $_SESSION['msg'][] = array($msg, $class);
    }

    public static function pageError404($variable)
    {
        if (!$variable) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        return true;
    }

    public static function pageRedirection($variable, $redirect)
    {
        if (!$variable) {
            redirect($redirect);
        }
        return true;
    }

    // If 2 weeks have not passed since registration, then the nickname is green
    public static function loginColor($time)
    {
        $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($time);
        $tm = floor($diff / 60);

        if ($tm > 20160) {
            return false;
        }
        return true;
    }

    // Проверка доступа
    // $content
    // $type -  post / answer / comment
    // $after - есть ли ответы
    // $stop_time - разрешенное время
    public static function accessСheck($content, $type, $user, $after, $stop_time)
    {
        if (!$content) {
            return false;
        }

        // Доступ получает только автор и админ
        if ($content[$type . '_user_id'] != $user['id'] && !UserData::checkAdmin()) {
            return false;
        }

        // Запретим удаление если есть ответ
        // И если прошло 30 минут
        if (!UserData::checkAdmin()) {

            if ($after > 0) {
                if ($content[$type . '_after'] > 0) {
                    return false;
                }
            }

            if ($stop_time > 0) {
                $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($content['date']);
                $time = floor($diff / 60);

                if ($time > $stop_time) {
                    return false;
                }
            }
        }

        return true;
    }

    // @param array $words: array('пост', 'поста', 'постов')
    public static function numWord($value, $words, $show = true)
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

    // Line length
    public static function getStrlen($str)
    {
        return mb_strlen($str, "utf-8");
    }

    // Create random string
    public static function randomString($type, int $len = 8)
    {
        if ($type = 'crypto') {
            return bin2hex(random_bytes($len / 2));
        }

        // sha1
        return sha1(uniqid((string) mt_rand(), true));
    }

    // To be added
    public static function sumbit($text)
    {
        return '<button type="submit" name="action" class="btn btn-primary" value="submit">' . $text . '</button>';
    }

    // To be deleted
    public static function remove($text)
    {
        return '<button type="submit" name="action" class="btn btn-outline-primary right mr10" value="delete">' . $text . '</button>';
    }
}
