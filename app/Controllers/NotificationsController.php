<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use Base;

class NotificationsController extends \MainController
{
    // Страница уведомлений участника
    public function index()
    {
        
        if (!$account = Request::getSession('account'))
        {
            redirect('/');
        }  
        
        $list = NotificationsModel::listNotification($account['user_id']);
        
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Уведомления',
            'description' => 'Страница уведомления',
            'list'        => $list,
        ];

        return view("notification/index", ['data' => $data, 'uid' => $uid]);
    }
   
}
