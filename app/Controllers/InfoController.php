<?php

namespace App\Controllers;
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

		$data = [
          'title' => 'Статистика',
          'msg'   => Base::getMsg(),
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
