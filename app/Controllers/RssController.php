<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\RssModel;
use Content;

class RssController extends Controller
{
    // Route::get('/sitemap.xml')
    public function index()
    {
        $data = [
            'url'       => config('meta.url'),
            'topics'    => RssModel::getTopicsSitemap(),
            'posts'     => RssModel::getPostsSitemap(),
        ];

        includeCachedTemplate('default/content/rss/sitemap', ['data' => $data]);
    }

    // Route::get('/turbo-feed/topic/{slug}')
    public function turboFeed()
    {
        $topic_slug = Request::get('slug');
        $topic      = RssModel::getTopicSlug($topic_slug);
        notEmptyOrView404($topic);

        $posts  = RssModel::getPostsFeed($topic_slug);
        $result = [];
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content']  = markdown($text[0], 'line');
            $result[$ind]         = $row;
        }

        $data = [
            'url'   => config('meta.url'),
            'posts' => $result,
        ];

        includeCachedTemplate(
            'default/content/rss/turbo-feed',
            [
                'data' => $data,
                'topic' => $topic,
            ]
        );
    }

    // Route::get('/rss-feed/topic/{slug}')
    public function rssFeed()
    {
        $topic_slug = Request::get('slug');
        $topic      = RssModel::getTopicSlug($topic_slug);
        notEmptyOrView404($topic);

        $posts  = RssModel::getPostsFeed($topic_slug);
        $result = [];
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content']  = markdown($text[0], 'line');
            $result[$ind]         = $row;
        }

        includeCachedTemplate(
            'default/content/rss/rss-feed',
            [
                'data'  => [
                    'url'       => config('meta.url'),
                    'posts'     => $result,
                ],
                'topic' => $topic,
            ]
        );
    }
}
