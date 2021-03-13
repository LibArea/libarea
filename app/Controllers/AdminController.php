<?php

namespace App\Controllers;
use Base;

class AdminController extends \MainController
{
	public function index()
	{

		$data = [
          'title' => 'Админка',
          'msg'   => Base::getMsg(),
        ];

         return view("admin/index", ['data' => $data]);
	}
}
