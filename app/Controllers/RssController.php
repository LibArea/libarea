<?php

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\RssModel;

class RssController extends Controller
{
    // Route::get('/sitemap.xml')
    public function index()
    {
        insertCacheTemplate(
            'default/content/rss/sitemap',
            [
                'data' => [
                    'topics'    => RssModel::getTopicsSitemap(),
                    'posts'     => RssModel::getPostsSitemap(),
                ]
            ]
        );
    }

    // Route::get('/turbo-feed/topic/{slug}')
    public function turboFeed()
    {
        $topic_slug = Request::param('slug')->asString();
        $topic      = RssModel::getTopicSlug($topic_slug);
        notEmptyOrView404($topic);

        $posts  = RssModel::getPostsFeed($topic_slug);

        insertCacheTemplate(
            'default/content/rss/turbo-feed',
            [
                'data' => [
                    'posts' => self::posts($posts),
                ],
                'topic' => $topic,
            ]
        );
    }

    // Route::get('/rss-feed/topic/{slug}')
    public function rssFeed()
    {
        $topic_slug = Request::param('slug')->asString();
        $topic      = RssModel::getTopicSlug($topic_slug);
        notEmptyOrView404($topic);

        $posts  = RssModel::getPostsFeed($topic_slug);

        insertCacheTemplate(
            'default/content/rss/rss-feed',
            [
                'data'  => [
                    'posts'     => self::posts($posts),
                ],
                'topic' => $topic,
            ]
        );
    }

    // Route::get('/rss/posts')->controller...
    public static function postsAll()
    {
        $posts  = RssModel::getPosts();

        insertCacheTemplate(
            'default/content/rss/posts',
            [
                'data'  => [
                    'posts'     => self::posts($posts),
                ]
            ]
        );
    }

    public static function posts($posts)
    {
        $result = [];
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content']  = markdown($text[0], 'line');
            $result[$ind]         = $row;
        }

        return $result;
    }
}
