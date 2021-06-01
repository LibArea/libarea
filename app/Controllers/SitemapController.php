<?php

namespace App\Controllers;
use App\Models\SitemapModel;
use Lori\Config;

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

        return view(PR_VIEW_DIR . '/sitemap/sitemap', ['data' => $data]);
    }
 
    public function feed()
    {
        $posts  = SitemapModel::getPostsFeed();
        
        $data = [
            'url'       => Config::get(Config::PARAM_URL),
            'posts'     => $posts,
        ];

        return view(PR_VIEW_DIR . '/sitemap/rss-feed', ['data' => $data]);
    }
 
}
