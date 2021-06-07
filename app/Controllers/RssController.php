<?php

namespace App\Controllers;
use App\Models\RssModel;
use Lori\Config;
use Lori\Base;

class RssController extends \MainController
{
    public function index()
    {
        $spaces = RssModel::getSpacesSitemap();
        $posts  = RssModel::getPostsSitemap();
        
        $data = [
            'url'       => Config::get(Config::PARAM_URL),
            'spaces'    => $spaces,
            'posts'     => $posts,
        ];

        includeCachedTemplate(PR_VIEW_DIR . '/rss/sitemap', ['data' => $data]);
    }
 
    public function turboFeed()
    {
        $space_id   = \Request::getInt('id');
        $space      = RssModel::getSpaceId($space_id);
        Base::PageError404($space);
        
        $posts  = RssModel::getPostsFeed($space_id);
        
        print_r($posts);
        $result = Array();
        foreach($posts as $ind => $row) {
            $row['post_content']  = Base::text($row['post_content'], 'md');
            $result[$ind]           = $row;
        }

        $data = [
            'url'       => Config::get(Config::PARAM_URL),
            'posts'     => $result,
        ];

        includeCachedTemplate(PR_VIEW_DIR . '/rss/turbo-feed', ['data' => $data, 'space' => $space]);
    }
}
