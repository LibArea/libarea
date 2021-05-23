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
            'h1'        => lang('Info'),
            'canonical' => Config::get(Config::PARAM_URL) . '/info',
        ];
        
        Request::getResources()->addBottomStyles('/assets/css/info.css');
        
       // Далее title, description
       Base::Meta(lang('Info'), lang('info-desc'), $other = false); 
 
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
            'flow_num'      => $result
        ];

        Request::getResources()->addBottomStyles('/assets/css/info.css');
        Request::getResources()->addBottomScript('/assets/js/canvas.js');

        Base::Meta(lang('Statistics'), lang('stats-desc'), $other = false); 

        return view(PR_VIEW_DIR . '/info/stats', ['data' => $data, 'uid' => $uid]);
	}

	public function rules()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'        => lang('Rules'),
            'canonical' => Config::get(Config::PARAM_URL) . '/info/rules',
        ];
        
        Request::getResources()->addBottomStyles('/assets/css/info.css');

        Base::Meta(lang('Rules'), lang('rules-desc'), $other = false);

        return view(PR_VIEW_DIR . '/info/rules', ['data' => $data, 'uid' => $uid]);
	}

	public function about()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'        => lang('About'),
            'canonical' => Config::get(Config::PARAM_URL) . '/info/about',
        ];

        Request::getResources()->addBottomStyles('/assets/css/info.css');

        Base::Meta(lang('About'), lang('about-desc'), $other = false);

        return view(PR_VIEW_DIR . '/info/about', ['data' => $data, 'uid' => $uid]);
	}

    public function privacy()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'        => lang('Privacy Policy'),
            'canonical' => Config::get(Config::PARAM_URL) . '/info/privacy',
        ];

        Request::getResources()->addBottomStyles('/assets/css/info.css');

        Base::Meta(lang('Privacy Policy'), lang('privacy-desc'), $other = false);

        return view(PR_VIEW_DIR . '/info/privacy', ['data' => $data, 'uid' => $uid]);
	}  

    public function restriction()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'        => lang('Restriction'),
            'canonical' => Config::get(Config::PARAM_URL) . '/info/restriction',
        ];
        
        Request::getResources()->addBottomStyles('/assets/css/info.css');

        Base::Meta(lang('Restriction'), lang('Restriction'), $other = false);

        return view(PR_VIEW_DIR . '/info/restriction', ['data' => $data, 'uid' => $uid]);
	} 

}
