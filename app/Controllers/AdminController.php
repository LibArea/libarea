<?php

namespace App\Controllers;
use Base;

class AdminController extends \MainController
{
	public function index()
	{

        $uid  = Base::getUid();
        $data = [
            'title'        => 'Админка',
            'description'  => 'Админка на AreaDev',
        ];

         return view("admin/index", ['data' => $data, 'uid' => $uid]);
	}
}
