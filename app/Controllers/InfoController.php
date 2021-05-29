<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\InfoModel;
use Lori\Config;
use Lori\Base;

class InfoController extends \MainController
{
    // Далее методы по названию страниц
    public function index()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Info'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/info',
            'sheet'         => 'info',
            'meta_title'    => lang('Info') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('info-desc') .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];
        
       return view(PR_VIEW_DIR . '/info/index', ['data' => $data, 'uid' => $uid]);
    }

    public function stats()
	{
        // Количество: участников, постов, комментариев и голосов по ним
        $user_num       = InfoModel::getUsersNumAll();
        $post_num       = InfoModel::getPostsNumAll();
        $comm_num       = InfoModel::getCommentsNumAll();
        $vote_comm_num  = InfoModel::getCommentsVoteNumAll();
        $vote_post_num  = InfoModel::getPostVoteNumAll();
        $flow_num       = InfoModel::GrafFlow();
        
        $result = Array();
        foreach($flow_num as $ind => $row){
            $row['date']    = date("j", strtotime($row['date']));
            $result[$ind]   = $row;
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Statistics'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/info/stats',
            'user_num'      => $user_num,
            'post_num'      => $post_num,
            'comm_num'      => $comm_num,
            'vote_comm_num' => $vote_comm_num,
            'vote_post_num' => $vote_post_num,
            'flow_num'      => $result,
            'sheet'         => 'stats',
            'meta_title'    => lang('Statistics') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('stats-desc') .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/info/stats', ['data' => $data, 'uid' => $uid]);
	}

    public function privacy()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Privacy Policy'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/info/privacy',
            'sheet'         => 'privacy',
            'meta_title'    => lang('Privacy Policy') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('privacy-desc') .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/info/privacy', ['data' => $data, 'uid' => $uid]);
	}  

    public function restriction()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Restriction'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/info/restriction',
            'sheet'         => 'info-restriction',
            'meta_title'    => lang('Restriction') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('Restriction') .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/info/restriction', ['data' => $data, 'uid' => $uid]);
	} 

}
