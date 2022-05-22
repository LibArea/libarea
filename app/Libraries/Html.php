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
        foreach (array_chunk($facet, 3) as $row) {
            if ($row[0] == $type) {
                if ($type == 'category') {
                    $result[] = '<a class="' . $css . '" href="' . url($url, ['grouping' => $choice, 'slug' => $row[1]]) . '">' . $row[2] . '</a>';
                } else {
                    $result[] = '<a class="' . $css . '" href="' . url($url, ['slug' => $row[1]]) . '">' . $row[2] . '</a>';
                }
            }
        }

        return implode($result);
    }

    // Blog, topic or category
    public static function addPost($facet)
    {
        $url_add = url('content.add', ['type' => 'post']);
        if (!empty($facet)) {
            if ($facet['facet_user_id'] == UserData::getUserId() || $facet['facet_type'] == 'topic') {
                $url_add = $url_add . '/' . $facet['facet_id'];
            }
        }

        $html  = '<a title="' . __('app.add_post') . '" 
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
        $months = __('app.months');

        //Разбиваем дату в массив
        $a = preg_split('/[^\d]/', $string);
        $today = date('Ymd');  //20210421

        if (($a[0] . $a[1] . $a[2]) == $today) {
            //Если сегодня
            return (__('app.today') . ' ' . $a[3] . ':' . $a[4]);
        } else {
            $b = explode('-', date("Y-m-d"));
            $tom = date('Ymd', mktime(0, 0, 0, $b[1], $b[2] - 1, $b[0]));
            if (($a[0] . $a[1] . $a[2]) == $tom) {
                //Если вчера
                return (__('app.yesterday') . ' ' . $a[3] . ':' . $a[4]);
            } else {
                //Если позже
                $mm = intval($a[1]);
                return ($a[2] . " " . $months[$mm] . " " . $a[0] . " " . $a[3] . ":" . $a[4]);
            }
        }
    }

    // Voting for posts, replies, comments and sites
    public static function votes($content, $type, $ind, $css = 'bi-heart', $block = '')
    {
        $html  = '';
        $count = '';
        if ($content[$type . '_votes'] > 0) {
            $count = $content[$type . '_votes'];
        }

        if (UserData::getAccount()) {
            if ($content['votes_' . $type . '_user_id'] || UserData::getUserId() == $content[$type . '_user_id']) {
                $html .= '<div class="voters sky flex ' . $block . ' center">
                            <div class="up-id ' . $css . '"></div>
                            <div class="score">
                                ' . $count . '
                            </div></div>';
            } else {
                $num_count = empty($count) ? 0 : $count;
                $html .= '<div id="up' . $content[$type . '_id'] . '" class="voters-' . $ind . '  flex ' . $block . ' center gray-600">
                            <div data-ind="' . $ind . '" data-id="' . $content[$type . '_id'] . '" data-count="' . $num_count . '" data-type="' . $type . '" class="up-id ' . $css . '"></div>
                            <div class="score">
                                ' . $count . '
                            </div></div>';
            }
        } else {
            $html .= '<div class="voters flex ' . $block . ' center gray-600">
                        <div class="up-id ' . $css . ' click-no-auth"></div>
                        <div class="score">
                             ' . $count . '                
                        </div></div>';
        }

        return $html;
    }

    // Add/remove from favorites
    public static function favorite($content_id, $type, $tid, $ind, $css = '')
    {
        $html  = '';
        if (UserData::getAccount()) {
            $blue = $tid ? 'sky' : 'gray-600';
            $my   = $tid ? 'bi-bookmark-dash' : 'bi-bookmark-plus';
            $html .= '<span id="favorite_' . $content_id . '" class="add-favorite fav-' . $ind . ' ' . $blue . ' ' . $css . '" data-ind="' . $ind . '" data-id="' . $content_id . '" data-type="' . $type . '"><i class="' . $my . '"></i></span>';
        } else {
            $html .= '<span class="click-no-auth gray-600 ' . $css . '">
                        <i class="bi-bookmark-plus"></i>
                            </span>';
        }

        return $html;
    }

    // Subscription: groups, blogs, posts, directory
    public static function signed($arr)
    {
        $html  = '';
        if (UserData::getAccount()) {
            if ($arr['content_user_id'] != UserData::getUserId()) {
                if ($arr['state']) {
                    $html .= '<div data-id="' . $arr['id'] . '" data-type="' . $arr['type'] . '" class="focus-id yes">' . __('app.unsubscribe') . '</div>';
                } else {
                    $html .= '<div data-id="' . $arr['id'] . '" data-type="' . $arr['type'] . '" class="focus-id no">+ ' . __('app.read') . '</div>';
                }
            }
        } else {
            $html .= '<a href="' . url('login') . '"><div class="focus-id no">+ ' . __('app.read') . '</div></a>';
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
                $html .= '<a class="pr5 mr5" href="' . $page . '/' . ($pNum - 1) . '.html"><< ' . ($pNum - 1) . '</a>';
            }
        }

        if ($pagesCount > $pNum) {
            $html .= '<span class="bg-green pt5 pr10 pb5 pl10 white ml5 mr5">' . ($pNum) . '</span>';
        }

        if ($pagesCount > $pNum) {
            if ($pagesCount > $pNum + 1) {
                $html .= '<a class="p5" href="' . $page . '/' . ($pNum + 1) . '.html"> ' . ($pNum + 1) . ' </a>';
            }

            if ($pagesCount > $pNum + 2) {
                $html .= '<a class="p5" href="' . $page . '/' . ($pNum + 2) . '.html"> ' . ($pNum + 2) . '</a>';
            }

            if ($pagesCount > $pNum + 3) {
                $html .= '...';
            }

            $html .= '<a class="p5 ml5 lowercase gray-600" href="' . $page . '/' . ($pNum + 1) . '.html">' . __('app.page') . ' ' . ($pNum + 1) . ' >></a>';
        }

        $html .= '</p>';

        return $html;
    }

    // Getting a piece of text
    public static function fragment($str, $lenght = 100, $end = '...', $charset = 'UTF-8', $token = '~')
    {
        $str = strip_tags($str);
        if (mb_strlen($str, $charset) >= $lenght) {
            $wrap = wordwrap($str, $lenght, $token);
            $str_cut = mb_substr($wrap, 0, mb_strpos($wrap, $token, 0, $charset), $charset);
            return $str_cut .= $end;
        } else {
            return $str;
        }
    }


    public static function getMsg()
    {
        if (isset($_SESSION['msg'])) {
            $msg = $_SESSION['msg'];
        } else {
            $msg = false;
        }

        unset($_SESSION['msg']);

        $html = '';
        if ($msg) {
            if ($msg['status'] == 'error') :
                $html .= "Notiflix.Notify.failure('" . $msg['msg'] . "')";
            else :
                $html .= "Notiflix.Notify.info('" . $msg['msg'] . "')";
            endif;
        }

        return $html;
    }

    public static function addMsg($msg, $status)
    {
        $_SESSION['msg'] = ['msg' => $msg, 'status' =>  $status ?? 'error'];
    }

    public static function pageError404($variable)
    {
        if (!$variable) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        return true;
    }

    public static function pageRedirection($variable, $redirect = '/')
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

    public static function formatToHuman($number)
    {
        if ($number < 1000) {
            return sprintf('%d', $number);
        }

        if ($number < 1000000) {
            $number = $number / 1000;
            return $newVal = number_format($number, 1) . 'k';
        }

        if ($number >= 1000000 && $number < 1000000000) {
            $number = $number / 1000000;
            return $newVal = number_format($number, 1) . 'M';
        }

        if ($number >= 1000000000 && $number < 1000000000000) {
            $number = $number / 1000000000;
            return $newVal = number_format($number, 1) . 'B';
        }

        return sprintf('%d%s', floor($number / 1000000000000), 'T+');
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
