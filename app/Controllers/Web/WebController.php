<?php

namespace App\Controllers\Web;

use Hleb\Constructor\Handlers\Request;
use App\Models\WebModel;
use App\Models\FeedModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class WebController extends \MainController
{
    public function index()
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = WebModel::getLinksAllCount();
        $links      = WebModel::getLinksAll($page, $limit, $uid['id']);

        $num = ' | ';
        if ($page > 1) {
            $num = sprintf(lang('page-number'), $page) . ' | ';
        }

        $data = [
            'h1'            => lang('domains-title'),
            'canonical'     => '/domains',
            'sheet'         => 'domains',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'meta_title'    => lang('domains-title') . $num . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('domains-desc') . $num . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/web/links', ['data' => $data, 'uid' => $uid, 'links' => $links]);
    }

    // Посты по домену
    public function posts($sheet)
    {
        $domain     = \Request::get('domain');
        $uid        = Base::getUid();
        $page       = \Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;

        $link       = WebModel::getLinkOne($domain, $uid['id']);
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

        $domains    = WebModel::getLinksTop($domain);

        $meta_title = lang('Domain') . ': ' . $domain . ' | ' . Config::get(Config::PARAM_NAME);
        $meta_desc = lang('domain-desc') . ': ' . $domain . ' ' . Config::get(Config::PARAM_HOME_TITLE);

        $data = [
            'h1'            => lang('Domain') . ': ' . $domain,
            'canonical'     => Config::get(Config::PARAM_URL) . '/domain/' . $domain,
            'sheet'         => 'domain',
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc,
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
        ];

        return view(PR_VIEW_DIR . '/web/link', ['data' => $data, 'uid' => $uid, 'posts' => $result, 'domains' => $domains, 'link' => $link]);
    }
  
}
