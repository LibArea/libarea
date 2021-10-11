<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\RssModel;
use Content, Config, Base;

class RssController extends MainController
{
    public function index()
    {
        $spaces = RssModel::getSpacesSitemap();
        $posts  = RssModel::getPostsSitemap();

        $data = [
            'url'       => Config::get('meta.url'),
            'spaces'    => $spaces,
            'posts'     => $posts,
        ];

        includeCachedTemplate('/rss/sitemap', ['data' => $data]);
    }

    public function turboFeed()
    {
        $space_id   = Request::getInt('id');
        $space      = RssModel::getSpaceId($space_id);
        Base::PageError404($space);

        $posts  = RssModel::getPostsFeed($space_id);
        $result = array();
        foreach ($posts as $ind => $row) {
            $row['post_content']  = Content::text($row['post_content'], 'text');
            $result[$ind]         = $row;
        }

        $data = [
            'url'       => Config::get('meta.url'),
            'posts'     => $result,
        ];

        includeCachedTemplate('/rss/turbo-feed', ['data' => $data, 'space' => $space]);
    }
}
