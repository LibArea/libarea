<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\HomeModel;
use Agouti\{Content, Config, Base};

class HomeController extends MainController
{
    // Главная страница
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $space_user         = HomeModel::getSubscriptionSpaces($uid['user_id']);
        $latest_answers     = HomeModel::latestAnswers($uid);

        $pagesCount = HomeModel::feedCount($space_user, $uid);
        $posts      = HomeModel::feed($page, $limit, $space_user, $uid, $sheet);
        Base::PageError404($posts);

        $result_post = array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result_post[$ind]              = $row;
        }

        $result_answers = array();
        foreach ($latest_answers as $ind => $row) {
            $row['answer_content']      = cutWords($row['answer_content'], 8);
            $row['answer_date']         = lang_date($row['answer_date']);
            $result_answers[$ind]       = $row;
        }

        $num        = $page > 1 ? sprintf(lang('page-number'), $page) : '';
        $meta_title = Config::get(Config::PARAM_HOME_TITLE) . $num;
        $meta_desc  = Config::get(Config::PARAM_META_DESC) . $num;

        $url        = Config::get(Config::PARAM_URL);
        $canonical  = $sheet == 'top' ? $url . '/top' : $url;

        if ($sheet == 'top' || $sheet == 'all') {
            $meta_title = lang($sheet . '-title') . $num . Config::get(Config::PARAM_HOME_TITLE);
            $meta_desc  = lang($sheet . '-desc') . $num . Config::get(Config::PARAM_NAME);
        }

        $meta = [
            'canonical'         => $canonical,
            'img'               => $url . '/assets/images/agouti.webp',
            'meta_title'        => $meta_title,
            'meta_desc'         => $meta_desc,
            'sheet'             => $sheet,
        ];

        $data = [
            'pagesCount'        => ceil($pagesCount / $limit),
            'pNum'              => $page,
            'sheet'             => $sheet,
            'latest_answers'    => $result_answers,
            'space_user'        => $space_user,
            'posts'             => $result_post
        ];

        return view('/home', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
