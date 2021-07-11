<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\HomeModel;
use App\Models\SpaceModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class HomeController extends \MainController
{
    // Главная страница
    public function index($sheet) 
    {
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        $uid    = Base::getUid();

        $space_user         = SpaceModel::getSpaces($uid['id'], 'subscription');
        $latest_answers     = HomeModel::latestAnswers($uid);
        
        $pagesCount = HomeModel::feedCount($space_user, $uid); 
        $posts      = HomeModel::feed($page, $space_user, $uid, $sheet);

        Base::PageError404($posts);

        $result = Array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }  
 
        $result_comm = Array();
        foreach ($latest_answers as $ind => $row) {
            $row['answer_content']      = Base::cutWords($row['answer_content'], 81);
            $row['answer_date']         = lang_date($row['answer_date']);
            $result_comm[$ind]          = $row;
        }

        $num = '';
        if ($page > 1) { 
            $num = ' | ' . lang('Page') . ' ' . $page;
        } 

        $meta_title = Config::get(Config::PARAM_HOME_TITLE) . $num;
        $meta_desc  = Config::get(Config::PARAM_META_DESC) . $num;
        $canonical  = Config::get(Config::PARAM_URL);
        
        if ($sheet == 'top') {
            $meta_title = lang('TOP') .'. '. Config::get(Config::PARAM_HOME_TITLE) . $num;
            $meta_desc  = lang('top-desc') . $num;   
            $canonical  = Config::get(Config::PARAM_URL) . '/top';
        } 
        
        if ($sheet == 'all') {
            $meta_title = lang('All') .'. '. Config::get(Config::PARAM_HOME_TITLE) . $num;
        } 

        $data = [
            'latest_answers'    => $result_comm,
            'pagesCount'        => $pagesCount,
            'pNum'              => $page,
            'sheet'             => $sheet,
            'canonical'         => $canonical,
            'img'               => Config::get(Config::PARAM_URL) . '/assets/images/areadev.webp',
            'meta_title'        => $meta_title,
            'meta_desc'         => $meta_desc,
        ];

        return view(PR_VIEW_DIR . '/home', ['data' => $data, 'uid' => $uid, 'posts' => $result, 'space_user' => $space_user]);
    }

}