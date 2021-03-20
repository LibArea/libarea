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
            'title'        => 'Информация',
            'description'  => 'Информация о сайте и команде работающей над AreaDev',
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
        
        $uid  = Base::getUid();
        $data = [
            'title'         => 'Статистика',
            'description'   => 'Статистика сайта AreaDev. Посты, комментарии, участники и голосование',
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
            'title'       => 'Правила сайта',
            'description' => 'Правила сайта AreaDev, политика почтительности',
        ];

        return view('info/rules', ['data' => $data, 'uid' => $uid]);

	}

	public function about()
	{
        
        $uid  = Base::getUid();
        $data = [
            'title'       => 'О нас',
            'description' => 'Информация о сайте и команде работающей над AreaDev',
        ];

        return view('info/about', ['data' => $data, 'uid' => $uid]);

	}

    public function privacy()
	{
        
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Политика конфиденциальности',
            'description' => 'Политика конфиденциальности сайта AreaDev',
        ];

        return view('info/privacy', ['data' => $data, 'uid' => $uid]);

	}

}
