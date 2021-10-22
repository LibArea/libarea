<?php

namespace App\Controllers\Web;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{WebModel, FeedModel, TopicModel};
use Content, Base;

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

        $num = $page > 1 ? sprintf(lang('page-number'), $page) : '';

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('web'),
        ];
        $meta = meta($m, lang('domains-title'), lang('domains-title'));

        $data = [
            'sheet'         => 'domains',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'links'         => $result,
        ];

        return view('/web/links', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
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
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('answer'), lang('answers-m'), lang('answers'));
            $result[$ind]                   = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('domain', ['domain' => $domain]),
        ];
        $meta = meta($m, lang('domain') . ': ' . $domain, lang('domain-desc') . ': ' . $domain);

        $data = [
            'sheet'         => 'domain',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'posts'         => $result,
            'domains'       => WebModel::getLinksTop($domain),
            'link'          => $link
        ];

        return view('/web/link', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
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
        $desc  = lang('sites') . ' ' . lang('by') . ' ' . $topic['topic_title'] . '. ' . $topic['topic_description'];
        $meta = meta($m, lang('sites') . ': ' . $topic['topic_title'], $desc);

        $data = [
            'sheet'         => 'sites-topic',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'links'         => $result,
            'topic'         => $topic
        ];

        return view('/web/sites', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
