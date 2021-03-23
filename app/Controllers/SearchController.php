<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use Base;

class SearchController extends \MainController
{
    // Поиск
    public function index()
    {
        
        
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Поиск по сайту',
            'description' => 'Страница поиска по сайту',
        ];

        return view("search/index", ['data' => $data, 'uid' => $uid]);
    }
   
}
