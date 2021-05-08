<?php

namespace App\Controllers;
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
        
       // title, description
       Base::Meta(lang('Info'), lang('info-desc'), $other = false); 
 
       return view(PR_VIEW_DIR . '/info/index', ['data' => $data, 'uid' => $uid]);
    }

    public function stats()
	{
        // Получаем общее количество: участников, постов, комментариев и голосов по ним
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

        // title, description
        Base::Meta(lang('Statistics'), lang('stats-desc'), $other = false); 

        return view(PR_VIEW_DIR . '/info/stats', ['data' => $data, 'uid' => $uid]);
	}

	public function rules()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Rules'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/info/rules',
        ];

        // title, description
        Base::Meta(lang('Rules'), lang('rules-desc'));

        return view(PR_VIEW_DIR . '/info/rules', ['data' => $data, 'uid' => $uid]);
	}

	public function about()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'        => lang('About'),
            'canonical' => Config::get(Config::PARAM_URL) . '/info/about',
        ];

        // title, description
        Base::Meta(lang('About'), lang('about-desc'), $other = false);

        return view(PR_VIEW_DIR . '/info/about', ['data' => $data, 'uid' => $uid]);
	}

    public function markdown()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Мarkdown'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/info/markdown',
        ];

        // title, description
        Base::Meta(lang('Мarkdown'), lang('markdown-desc'), $other = false);

        return view(PR_VIEW_DIR . '/info/markdown', ['data' => $data, 'uid' => $uid]);
	}  

    public function privacy()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'        => lang('Privacy Policy'),
            'canonical' => Config::get(Config::PARAM_URL) . '/info/privacy',
        ];

        // title, description
        Base::Meta(lang('Privacy Policy'), lang('privacy-desc'), $other = false);

        return view(PR_VIEW_DIR . '/info/privacy', ['data' => $data, 'uid' => $uid]);
	}  
    
    public function trustLevel()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'        => lang('Trust level') . ' (TL) ',
            'canonical' => Config::get(Config::PARAM_URL) . '/info/trust-level',
        ];

        // title, description
        Base::Meta(lang('Trust level'), lang('tl-desc'), $other = false);

        return view(PR_VIEW_DIR . '/info/trust-level', ['data' => $data, 'uid' => $uid]);
	} 
    
    public function restriction()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'        => lang('Restriction'),
            'canonical' => Config::get(Config::PARAM_URL) . '/info/restriction',
        ];

        // title, description
        Base::Meta(lang('Restriction'), lang('Restriction'), $other = false);

        return view(PR_VIEW_DIR . '/info/restriction', ['data' => $data, 'uid' => $uid]);
	} 

    public function initialSetup()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'        => lang('Initial Setup'),
            'canonical' => Config::get(Config::PARAM_URL) . '/info/initial-setup',         
        ];

        // title, description
        Base::Meta(lang('Initial Setup'), lang('init-setip-desc'), $other = false);

        return view(PR_VIEW_DIR . '/info/initial-setup', ['data' => $data, 'uid' => $uid]);
	}    
}
