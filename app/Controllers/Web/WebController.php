<?php

namespace App\Controllers\Web;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{WebModel, FeedModel, TopicModel};
use Content, Base, Translate;

class WebController extends MainController
{
    public function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = WebModel::getLinksAllCount();
        $links      = WebModel::getLinksAll($page, $limit, $uid['user_id']);

        $result = array();
        foreach ($links as $ind => $row) {
            $text = explode("\n", $row['link_content']);
            $row['link_content']    = Content::text($text[0], 'line');
            $result[$ind]           = $row;
        }

        $num = $page > 1 ? sprintf(Translate::get('page-number'), $page) : '';

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('web'),
        ];

        return view(
            '/web/links',
            [
                'meta'  => meta($m, Translate::get('domains-title'), Translate::get('domains-desc')),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => 'domains',
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'links'         => $result,
                ]
            ]
        );
    }

    // Посты по домену
    public function posts($sheet)
    {
        $domain     = Request::get('domain');
        $uid        = Base::getUid();
        $page       = Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;

        $link       = WebModel::getLinkOne($domain, $uid['user_id']);
        Base::PageError404($link);

        $link['link_content'] = Content::text($link['link_content'], 'line');

        $limit      = 25;
        $data       = ['link_url_domain' => $link['link_url_domain']];
        $posts      = FeedModel::feed($page, $limit, $uid, $sheet, 'link', $data);
        $pagesCount = FeedModel::feedCount($uid, $sheet, 'link', $data);

        $result = array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('domain', ['domain' => $domain]),
        ];

        return view(
            '/web/link',
            [
                'meta'  => meta($m, Translate::get('domain') . ': ' . $domain, Translate::get('domain-desc') . ': ' . $domain),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => 'domain',
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'posts'         => $result,
                    'domains'       => WebModel::getLinksTop($domain),
                    'link'          => $link
                ]
            ]
        );
    }

    public function sites($sheet)
    {
        $slug       = Request::get('slug');
        $uid        = Base::getUid();
        $page       = Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;

        $topic = TopicModel::getTopic($slug, 'slug');
        Base::PageError404($topic);

        $limit      = 25;
        $links      = WebModel::feedLink($page, $limit, $uid, $sheet, $topic['topic_slug']);
        $pagesCount = WebModel::feedLinkCount($topic['topic_slug']);

        $result = array();
        foreach ($links as $ind => $row) {
            // + данные
            $result[$ind]   = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('web.topic', ['slug' => $topic['topic_slug']]),
        ];
        $desc  = Translate::get('sites') . ' ' . Translate::get('by') . ' ' . $topic['topic_title'] . '. ' . $topic['topic_description'];

        return view(
            '/web/sites',
            [
                'meta'  => meta($m, Translate::get('sites') . ': ' . $topic['topic_title'], $desc),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => 'sites-topic',
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'links'         => $result,
                    'topic'         => $topic
                ]
            ]
        );
    }
}
