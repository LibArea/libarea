<?php

class Html
{
    // Blog, topic or category
    public static function addPost($facet)
    {
        $url_add = url('content.add', ['type' => 'post']);
        if (!empty($facet)) {
            if ($facet['facet_user_id'] == UserData::getUserId() || $facet['facet_type'] == 'topic') {
                $url_add = $url_add . '/' . $facet['facet_id'];
            }
        }

        return '<a title="' . __('app.add_post') . '" href="' . $url_add . '" class="sky"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#plus"></use></svg></a>';
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
        }

        $b = explode('-', date("Y-m-d"));
        $tom = date('Ymd', mktime(0, 0, 0, $b[1], $b[2] - 1, $b[0]));
        if (($a[0] . $a[1] . $a[2]) == $tom) {
            //Если вчера
            return (__('app.yesterday') . ' ' . $a[3] . ':' . $a[4]);
        }

        //Если позже
        $mm = intval($a[1]);
        if (date("Y") == $a[0]) {
            return ((int)$a[2] . " " . $months[$mm]); // без года
        }
        return ((int)$a[2] . " " . $months[$mm] . " " . $a[0]);
    }

    // Voting for posts, replies, comments and sites
    public static function votes($content, $type, $icon = 'heart')
    {
        $count = $content[$type . '_votes'] > 0 ?  $content[$type . '_votes'] : '';

        $html = '<div class="flex gap-min gray-600"><div class="up-id click-no-auth"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#' . $icon . '"></use></svg></div><div class="score"> ' . $count . '</div></div>';

        if (UserData::getAccount()) {
            if ($content['votes_' . $type . '_user_id'] || UserData::getUserId() == $content[$type . '_user_id']) {
                $html = '<div class="active flex gap-min">
                            <div class="up-id"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#' . $icon . '"></use></svg></div><div class="score">' . $count . '</div></div>';
            } else {
                $num_count = empty($count) ? 0 : $count;
                $html = '<div id="up' . $content[$type . '_id'] . '" class="flex gap-min gray-600">
                            <div data-id="' . $content[$type . '_id'] . '" data-count="' . $num_count . '" data-type="' . $type . '" class="up-id pointer"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#' . $icon . '"></use></svg></div><div class="score">' . $count . '</div></div>';
            }
        }

        return $html;
    }

    // Add/remove from favorites
    public static function favorite($content_id, $type, $tid)
    {
        $html = '<a class="click-no-auth gray-600"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#bookmark"></use></svg></a>';
        if (UserData::getAccount()) {
            $active = $tid ? 'active' : 'gray-600';
            $html = '<a id="favorite_' . $content_id . '" class="add-favorite ' . $active . '" data-id="' . $content_id . '" data-type="' . $type . '"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#bookmark"></use></svg></i></a>';
        }

        return $html;
    }

    // Subscription: groups, blogs, posts, directory
    public static function signed($arr)
    {
        if (UserData::getAccount()) {
            if ($arr['content_user_id'] != UserData::getUserId()) {
                $html = '<span data-id="' . $arr['id'] . '" data-type="' . $arr['type'] . '" class="focus-id red">' . __('app.read') . '</span>';
                if ($arr['state']) {
                    $html = '<span data-id="' . $arr['id'] . '" data-type="' . $arr['type'] . '" class="focus-id">' . __('app.unsubscribe') . '</span>';
                }
            }
        } else {
            $html = '<a href="' . url('login') . '"><span class="focus-id red">' . __('app.read') . '</span></a>';
        }

        return $html ?? false;
    }

    // Page pagination
    public static function pagination($pNum, $pagesCount, $sheet, $other)
    {
        if ($pNum > $pagesCount) {
            return null;
        }

        $other = empty($other) ? '' : $other;
        $first = empty($other) ? '/' : $other;

        if (in_array($sheet, ['all', 'top', 'questions', 'posts'])) {
            $page  = $other . '/' . $sheet;
            $first = $other . '/' . $sheet;
        } else {
            $page = $other . '';
        }

        $html = '<div class="flex gap">';

        if ($pNum != 1) {
            if (($pNum - 1) == 1) {
                $html .= '<a class="p5" href="' . $first . '"><< ' . ($pNum - 1) . '</a>';
            } else {
                $html .= '<a class="p5" href="' . $page . '/' . ($pNum - 1) . '.html"><< ' . ($pNum - 1) . '</a>';
            }
        }

        if ($pagesCount > $pNum) {
            $html .= '<div class="bg-green p5-10 white">' . ($pNum) . '</div>';
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

            $html .= '<a class="p5 lowercase gray-600" href="' . $page . '/' . ($pNum + 1) . '.html">' . __('app.page') . ' ' . ($pNum + 1) . ' >></a>';
        }

        $html .= '</div>';

        return $html;
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
