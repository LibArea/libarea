<?php

namespace App\Controllers;
use App\Models\InfoModel;
use Base;

class InfoController extends \MainController
{
    // Далее методы по названию страниц
    public function index()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Info'),
            'title'         => lang('Info') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('info-desc') . ' ' . $GLOBALS['conf']['sitename'],
        ];
        
        return view(PR_VIEW_DIR . '/info/index', ['data' => $data, 'uid' => $uid]);
    }

    public function stats()
	{
        // Получаем общее количество: участников, постов, комментариев и голосов по ним
        $user_num      = InfoModel::getUsersNumAll();
        $post_num      = InfoModel::getPostsNumAll();
        $comm_num      = InfoModel::getCommentsNumAll();
        $vote_comm_num = InfoModel::getCommentsVoteNumAll();
        $vote_post_num = InfoModel::getPostVoteNumAll();
        
        $vote_graff = InfoModel::GrafVote();
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Statistics'),
            'title'         => lang('Statistics') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('stats-desc') .' ' . $GLOBALS['conf']['sitename'],
            'user_num'      => $user_num,
            'post_num'      => $post_num,
            'comm_num'      => $comm_num,
            'vote_comm_num' => $vote_comm_num,
            'vote_post_num' => $vote_post_num,
        ];

        return view(PR_VIEW_DIR . '/info/stats', ['data' => $data, 'uid' => $uid]);
	}

	public function rules()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Rules'),
            'title'         => lang('Rules') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('rules-desc') . ' ' . $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/info/rules', ['data' => $data, 'uid' => $uid]);
	}

	public function about()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('About'),
            'title'         => lang('About') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('about-desc') . ' ' . $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/info/about', ['data' => $data, 'uid' => $uid]);
	}

    public function privacy()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Privacy Policy'),
            'title'         => lang('Privacy Policy') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('privacy-desc') . ' ' . $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/info/privacy', ['data' => $data, 'uid' => $uid]);
	}  
    
    public function trustLevel()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Trust level') . ' (TL) ',
            'title'         => lang('Trust level') . ' (TL) | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('tl-desc') . ' ' . $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/info/trust-level', ['data' => $data, 'uid' => $uid]);
	} 
    
    public function restriction()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Restriction'),
            'title'         => lang('Restriction') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('Restriction') . ' ' . $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/info/restriction', ['data' => $data, 'uid' => $uid]);
	} 

    public function markdown()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Мarkdown'),
            'title'         => lang('Мarkdown') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('markdown-desc') . ' ' . $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/info/markdown', ['data' => $data, 'uid' => $uid]);
	}  

    public function initialSetup()
	{
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Initial Setup'), 
            'title'         => lang('Initial Setup') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => lang('init-setip-desc') . ' | ' . $GLOBALS['conf']['sitename'],
        ];

        return view(PR_VIEW_DIR . '/info/initial-setup', ['data' => $data, 'uid' => $uid]);
	}    
}
