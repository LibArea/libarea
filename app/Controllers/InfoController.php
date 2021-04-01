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
            'title'        => 'Информация | ' . $GLOBALS['conf']['sitename'],
            'description'  => 'Информация о сайте и команде работающей над ' . $GLOBALS['conf']['sitename'],
        ];
        
        return view("info/index", ['data' => $data, 'uid' => $uid]);
    }

    public function stats()
	{
        // Получаем общее количество: участников, постов, комментариев и голосов за них
        $user_num      = InfoModel::getUsersNumAll();
        $post_num      = InfoModel::getPostsNumAll();
        $comm_num      = InfoModel::getCommentsNumAll();
        $vote_comm_num = InfoModel::getCommentsVoteNumAll();
        $vote_post_num = InfoModel::getPostVoteNumAll();
        
        $vote_graff = InfoModel::GrafVote();
        
        $uid  = Base::getUid();
        $data = [
            'title'         => 'Статистика | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Статистика сайта. Посты, комментарии, участники и голосование' . $GLOBALS['conf']['sitename'],
            'user_num'      => $user_num,
            'post_num'      => $post_num,
            'comm_num'      => $comm_num,
            'vote_comm_num' => $vote_comm_num,
        ];

        return view('info/stats', ['data' => $data, 'uid' => $uid]);
	}

	public function rules()
	{
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Правила сайта | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Правила сайта, политика почтительности ' . $GLOBALS['conf']['sitename'],
        ];

        return view('info/rules', ['data' => $data, 'uid' => $uid]);
	}

	public function about()
	{
        $uid  = Base::getUid();
        $data = [
            'title'       => 'О нас | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Информация о сайте и команде работающей над ' . $GLOBALS['conf']['sitename'],
        ];

        return view('info/about', ['data' => $data, 'uid' => $uid]);
	}

    public function privacy()
	{
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Политика конфиденциальности | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Политика конфиденциальности сайта ' . $GLOBALS['conf']['sitename'],
        ];

        return view('info/privacy', ['data' => $data, 'uid' => $uid]);
	}  
    
    public function trustlevel()
	{
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Уровень доверия (TL)  | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Информация об уровни доверия пользователя (TL). Права, доступ. ' . $GLOBALS['conf']['sitename'],
        ];

        return view('info/trust-level', ['data' => $data, 'uid' => $uid]);
	} 
    
    public function restriction()
	{
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Ограничение  | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Профиль проверяется ' . $GLOBALS['conf']['sitename'],
        ];

        return view('info/restriction', ['data' => $data, 'uid' => $uid]);
	} 
}
