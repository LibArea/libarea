<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\RssModel;
use Content, Config, Base;

class RssController extends MainController
{
    // Route::get('/sitemap.xml')
    public function index()
    {
        $data = [
            'url'       => Config::get('meta.url'),
            'topics'    => RssModel::getTopicsSitemap(),
            'posts'     => RssModel::getPostsSitemap(),
        ];

        includeCachedTemplate('/rss/sitemap', ['data' => $data]);
    }

    // Route::get('/turbo-feed/topic/{id}')
    public function turboFeed()
    {
        $topic_slug = Request::get('slug');
        $topic      = RssModel::getTopicSlug($topic_slug);
        Base::PageError404($topic);

        $posts  = RssModel::getPostsFeed($topic_slug);
        $result = array();
        foreach ($posts as $ind => $row) {
            $row['post_content']  = Content::text($row['post_content'], 'text');
            $result[$ind]         = $row;
        }

        $data = [
            'url'       => Config::get('meta.url'),
            'posts'     => $result,
        ];

        includeCachedTemplate('/rss/turbo-feed', ['data' => $data, 'topic' => $topic]);
    }
}
