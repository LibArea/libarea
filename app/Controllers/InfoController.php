<?php

namespace App\Controllers;
use App\Models\InfoModel;
use Base;

class InfoController extends \MainController
{

	public function index()
	{

		$data = [
          'title' => 'Информация',
          'msg'   => Base::getMsg(),
        ];

         return view("info/index", ['data' => $data]);
	}

    public function stats()
	{

        $user_num      = InfoModel::getUsersNumAll();
        $post_num      = InfoModel::getPostsNumAll();
        $comm_num      = InfoModel::getCommentsNumAll();
        $vote_comm_num = InfoModel::getCommentsVoteNumAll();
        
		$data = [
          'title'         => 'Статистика',
          'user_num'      => $user_num,
          'post_num'      => $post_num,
          'comm_num'      => $comm_num,
          'vote_comm_num' => $vote_comm_num,
          'msg'           => Base::getMsg(),
        ];

        return view('info/stats', ['data' => $data]);

	}

	public function rules()
	{

		$data = [
          'title' => 'Правила сайта',
          'msg'   => Base::getMsg(),
        ];

        return view('info/rules', ['data' => $data]);

	}


	public function about()
	{

		$data = [
          'title' => 'О нас',
          'msg'   => Base::getMsg(),
        ];

        return view('info/about', ['data' => $data]);

	}

    public function privacy()
	{

		$data = [
          'title' => 'Политика конфиденциальности',
          'msg'   => Base::getMsg(),
        ];

        return view('info/privacy', ['data' => $data]);

	}

}
