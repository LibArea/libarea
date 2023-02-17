<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\RssModel;

class RssController extends Controller
{
    // Route::get('/sitemap.xml')
    public function index()
    {
        includeCachedTemplate(
            'default/content/rss/sitemap',
            [
                'data' => [
                    'url'       => config('meta.url'),
                    'topics'    => RssModel::getTopicsSitemap(),
                    'posts'     => RssModel::getPostsSitemap(),
                ]
            ]
        );
    }

    // Route::get('/turbo-feed/topic/{slug}')
    public function turboFeed()
    {
        $topic_slug = Request::get('slug');
        $topic      = RssModel::getTopicSlug($topic_slug);
        notEmptyOrView404($topic);

        includeCachedTemplate(
            'default/content/rss/turbo-feed',
            [
                'data' => [
                    'url'   => config('meta.url'),
                    'posts' => self::posts($topic_slug),
                ],
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

        includeCachedTemplate(
            'default/content/rss/rss-feed',
            [
                'data'  => [
                    'url'       => config('meta.url'),
                    'posts'     => self::posts($topic_slug),
                ],
                'topic' => $topic,
            ]
        );
    }

    public static function posts($slug)
    {
        $posts  = RssModel::getPostsFeed($slug);
        $result = [];
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content']  = markdown($text[0], 'line');
            $result[$ind]         = $row;
        }

        return $result;
    }
}
