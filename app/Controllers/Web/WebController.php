<?php

namespace App\Controllers\Web;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{WebModel, FeedModel};
use Lori\{Content, Config, Base};

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

        $num = ' | ';
        if ($page > 1) {
            $num = sprintf(lang('page-number'), $page) . ' | ';
        }

        $meta = [
            'canonical'     => '/web',
            'sheet'         => 'domains',
            'meta_title'    => lang('domains-title') . $num . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('domains-desc') . $num . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'sheet'         => 'domains',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'links'         => $links
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

        $limit      = 25;
        $data       = ['link_url_domain' => $link['link_url_domain']];
        $posts      = FeedModel::feed($page, $limit, $uid, $sheet, 'link', $data);
        $pagesCount = FeedModel::feedCount($uid, 'link', $data);

        $result = array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $result[$ind]                   = $row;
        }

        $meta_title = lang('Domain') . ': ' . $domain . ' | ' . Config::get(Config::PARAM_NAME);
        $meta_desc  = lang('domain-desc') . ': ' . $domain . ' ' . Config::get(Config::PARAM_HOME_TITLE);

        $meta = [
            'canonical'     => Config::get(Config::PARAM_URL) . '/domain/' . $domain,
            'sheet'         => 'domain',
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc,
        ];

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
}
