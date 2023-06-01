<?php

declare(strict_types=1);

namespace App\Services\Meta;

use Hleb\Constructor\Handlers\Request;
use Meta, Img;

class Post
{
    public static function metadata($content)
    {
        $meta = [
            'published_time' => $content['post_date'],
            'type'      => 'article',
            'og'        => true,
            'imgurl'    => self::images($content),
            'url'       => post_slug($content['post_id'], $content['post_slug']),
        ];

        Request::getResources()->addBottomScript('/assets/js/share/goodshare.min.js');
        Request::getResources()->addBottomScript('/assets/js/dialog/dialog.js');

        if ($content['post_is_deleted'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        $description  = (fragment($content['post_content'], 250) == '') ? strip_tags($content['post_title']) : fragment($content['post_content'], 250);

        return Meta::get(htmlEncode(strip_tags($content['post_title'])), htmlEncode($description), $meta);
    }

    public static function images($content)
    {
        $content_img  = config('meta.img_path');

        if ($content['post_content_img']) {
            $content_img  = Img::PATH['posts_cover'] . $content['post_content_img'];
        } elseif ($content['post_thumb_img']) {
            $content_img  = Img::PATH['posts_thumb'] . $content['post_thumb_img'];
        }

        return $content_img;
    }
}
