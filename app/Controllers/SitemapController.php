<?php

namespace App\Controllers;
use App\Models\SitemapModel;
use Lori\Config;
use Lori\Base;

class SitemapController extends \MainController
{
    public function index()
    {
        $spaces = SitemapModel::getSpacesSitemap();
        $posts  = SitemapModel::getPostsSitemap();
        
        $data = [
            'url'       => Config::get(Config::PARAM_URL),
            'spaces'    => $spaces,
            'posts'     => $posts,
        ];

        includeCachedTemplate(PR_VIEW_DIR . '/sitemap/sitemap', ['data' => $data]);
    }
 
    public function feed()
    {
        $space_id  = \Request::getInt('id');
        
        $space = SitemapModel::getSpaceId($space_id);
        Base::PageError404($space);
        
        $posts  = SitemapModel::getPostsFeed($space_id);
        
        $data = [
            'url'       => Config::get(Config::PARAM_URL),
            'posts'     => $posts,
            'space'     => $space,
        ];

        includeCachedTemplate(PR_VIEW_DIR . '/sitemap/rss-feed', ['data' => $data]);
    }
 
}
