<?php

declare(strict_types=1);

use Hleb\Static\Request;
use App\Bootstrap\Services\User\UserData;

class Html
{
    // Blog, topic or category
    public static function facets($facet, $type, $css, $sort = 'all')
    {
        $facets = preg_split('/(@)/', (string)$facet ?? false);

        $result = [];
        foreach (array_chunk($facets, 3) as $row) {
            if ($row[0] == $type) {
                if ($type === 'category') {
                    $result[] = '<a class="' . $css . '" href="' . url($type, ['sort' => $sort, 'slug' => $row[1]]) . '">' . $row[2] . '</a>';
                } else {
                    $result[] = '<a class="' . $css . '" href="' . url($type, ['slug' => $row[1]]) . '">' . $row[2] . '</a>';
                }
            }
        }

        return implode($result);
    }

    // Blog, topic or category
    public static function facets_blog($blog_slug, $facet, $css)
    {
        $facets = preg_split('/(@)/', $facet ?? false);

        $result = [];
        foreach (array_chunk($facets, 3) as $row) {
            if ($row[0] === 'topic') {
                $result[] = '<a class="' . $css . '" href="' . url('blog.topic', ['slug' => $blog_slug, 'tslug' => $row[1]]) . '">' . $row[2] . '</a>';
            }
        }

        return implode($result);
    }

    // Blog, topic or category
    public static function addPost($facet_id)
    {
        $url_add = url('post.form.add');
        if (!empty($facet_id)) {
            $url_add = $url_add . '/' . $facet_id;
        }

        return '<a title="' . __('app.add_post') . '" href="' . $url_add . '" class="blue"><svg class="icon large icon-bold"><use xlink:href="/assets/svg/icons.svg#write"></use></svg></a>';
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
        $tom = date('Ymd', mktime(0, 0, 0, (int)$b[1], (int)$b[2] - 1, (int)$b[0]));
        if (($a[0] . $a[1] . $a[2]) == $tom) {
            //Если вчера
            return (__('app.yesterday') . ' ' . $a[3] . ':' . $a[4]);
        }

        //Если позже
        $later = (int)$a[1];
        if (date("Y") == $a[0]) {
            return ((int)$a[2] . " " . $months[$later]); // без года
        }
        return ((int)$a[2] . " " . $months[$later] . " " . $a[0]);
    }

    // Voting for posts, replies, answer and sites
    public static function votes($content, $type, $icon = 'heart')
    {
        $count = $content[$type . '_votes'] > 0 ?  $content[$type . '_votes'] : '';

        $html = '<div class="flex gap-sm gray-600"><div class="up-id click-no-auth"><svg class="icon"><use xlink:href="/assets/svg/icons.svg#' . $icon . '"></use></svg></div><div class="score"> ' . $count . '</div></div>';

        if (UserData::checkActiveUser()) {
            if (UserData::getUserId() == $content[$type . '_user_id']) {
                $html = '<div class="active flex gap-sm"><div class="up-id"><svg class="icon"><use xlink:href="/assets/svg/icons.svg#' . $icon . '"></use></svg></div><div class="score">' . $count . '</div></div>';
            } else {
                $active = $content['votes_' . $type . '_user_id'] ? 'active ' : '';
                $html = '<div id="up' . $content[$type . '_id'] . '" class="' . $active . 'flex gap-sm gray-600"><div data-id="' . $content[$type . '_id'] . '" data-type="' . $type . '" class="up-id pointer"><svg class="icon"><use xlink:href="/assets/svg/icons.svg#' . $icon . '"></use></svg></div><div class="score">' . $count . '</div></div>';
            }
        }

        return $html;
    }

    // Add/remove from favorites
    public static function favorite($content_id, $type, $tid, $heading = '')
    {
        $head = ($heading == 'heading') ? __('app.save') : '';
        $html = '<div class="click-no-auth gray-600"><svg class="icon"><use xlink:href="/assets/svg/icons.svg#bookmark"></use></svg>' . $head . '</div>';
        if (UserData::checkActiveUser()) {
            $active = $tid ? 'active' : 'gray-600';
            $html = '<div id="favorite_' . $content_id . '" class="add-favorite pointer ' . $active . '" data-id="' . $content_id . '" data-type="' . $type . '"><svg class="icon"><use xlink:href="/assets/svg/icons.svg#bookmark"></use></svg></i>' . $head . '</div>';
        }

        return $html;
    }

    // Subscription: groups, blogs, posts, directory
    public static function signed($arr)
    {
        if (UserData::checkActiveUser()) {
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
    public static function pagination($pNum, $pagesCount, $sheet, $other, $sign = '?', $sort = null)
    {
        if ($pNum > $pagesCount) {
            return null;
        }

        $other = empty($other) ? '' : $other;
        $first = empty($other) ? '/' : $other;

        $page = $other . '';
        if (in_array($sheet, ['all', 'questions', 'posts'])) {
            $page  = $other . '/' . $sheet;
        }

        $html = '<div class="flex gap">';

        if ($pNum != 1) {
            if (($pNum - 1) == 1) {
                $html .= '<a class="p5" href="' . $first . $sort . '"><< ' . ($pNum - 1) . '</a>';
            } else {
                $html .= '<a class="p5" href="' . $page . $sign . 'page=' . ($pNum - 1) . $sort . '"><< ' . ($pNum - 1) . '</a>';
            }
        }

        if ($pagesCount > $pNum) {
            $html .= '<div class="bg-blue p5-10 white">' . ($pNum) . '</div>';
        }

        if ($pagesCount > $pNum) {
            if ($pagesCount > $pNum + 1) {
                $html .= '<a class="p5" href="' . $page . $sign . 'page=' . ($pNum + 1) . $sort . '"> ' . ($pNum + 1) . ' </a>';
            }

            if ($pagesCount > $pNum + 2) {
                $html .= '<a class="p5" href="' . $page . $sign . 'page=' . ($pNum + 2) . $sort . '"> ' . ($pNum + 2) . '</a>';
            }

            if ($pagesCount > $pNum + 3) {
                $html .= '...';
            }

            $html .= '<a class="p5 lowercase gray-600" href="' . $page . $sign . 'page=' . ($pNum + 1) . $sort . '">' . __('app.page') . ' ' . ($pNum + 1) . ' >></a>';
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

    public static function breadcrumb($list)
    {
        $html = '<ul itemscope itemtype="https://schema.org/BreadcrumbList" class="breadcrumbs">';

        end($list);
        $last_item_key   = key($list);

        $show_last = true;

        foreach ($list as $key => $item) :
            if ($key != $last_item_key) :

                $html .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                if (!empty($item['link'])) :
                    $html .= '<a itemprop="item" href="' . $item['link'] . '"><span itemprop="name">' . $item['name'] . '</span></a>';
                else :
                    $html .= '<span itemprop="name">' . $item['name'] . '</span>';
                endif;
                $html .= '<meta itemprop="position" content="' . $key + 1 . '">';
                $html .= '</li>';
            else :

                // Отобразим последний элемент 
                $html .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="active">
				<span itemprop="name"> ' . $item['name'] . ' </span>
				<meta itemprop="position" content="' . $key + 1 . '">
			  </li>';
            endif;
        endforeach;

        $html .= '</ul>';

        return $html;
    }

    public static function pageNumber()
    {
        $page = Request::get('page')->value();
        $pageNumber = (int)$page ?? null;

        return $pageNumber <= 1 ? 1 : $pageNumber;
    }
}
