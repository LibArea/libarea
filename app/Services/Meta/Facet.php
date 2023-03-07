<?php

declare(strict_types=1);

namespace App\Services\Meta;

use Hleb\Constructor\Handlers\Request;
use Meta, Img;

class Facet
{
    public static function metadata($sheet, $facet, $type = 'topic', $topic = '')
    {
        switch ($sheet) {
            case 'questions':
                $url    = url($type . '.questions', ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' . __('app.questions');
                $description = __('meta.feed_facet_questions_desc') . $facet['facet_description'];
                break;
            case 'posts':
                $url    = url($type . '.posts', ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' . __('app.posts');
                $description = __('meta.feed_facet_posts_desc') . $facet['facet_description'];
                break;
            case 'recommend':
                $url    =  url($type . '.recommend', ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' .  __('app.rec_posts');
                $description  = __('meta.feed_facet_rec_posts_desc', ['name' => $facet['facet_seo_title']]) . $facet['facet_description'];
                break;
            case 'info':
                $url    = url($type . '.info', ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' . __('app.info');
                $description = __('meta.facet_info_desc', ['name' => $facet['facet_seo_title']]) . $facet['facet_description'];
                break;
            case 'writers':
                $url    = url($type . '.writers', ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' . __('app.writers');
                $description = __('meta.facet_writers_desc', ['name' => $facet['facet_seo_title']]) . $facet['facet_description'];
                break;
            case 'blog.topics':
                $url    = url($type . '.topics', ['slug' => $facet['facet_slug'], 'tslug' => $topic['facet_slug']]);
                $title  = $topic['facet_seo_title'] . ' — ' . $facet['facet_seo_title'];
                $description = __('meta.facet_topics_desc', ['name' => $topic['facet_seo_title']]) . $facet['facet_description'];
                break;
            default: // facet.feed 
                $url    = url($type, ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' .  __('app.feed');
                $description = __('meta.feed_facet_desc') . $facet['facet_description'];
        }

        $meta = [
            'og'        => true,
            'imgurl'    => Img::PATH['facets_logo'] . $facet['facet_img'],
            'url'       =>  $url,
        ];

        Request::getResources()->addBottomScript('/assets/js/dialog/dialog.js');
        Request::getResources()->addBottomScript('/assets/js/share/goodshare.min.js');

        return Meta::get($title, $description, $meta);
    }
}
