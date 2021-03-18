<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use Base;

class NotificationsController extends \MainController
{

    public function index()
    {
        
        // Авторизировались или нет
        if (!$account = Request::getSession('account'))
        {
            redirect('/');
        }  
        
        $user_uid = $account['user_id'];
        
        $list = NotificationsModel::listNotification($user_uid);
        
        $data = [
            'title' => 'Уведомления',
            'list'  => $list,
            'msg'   => Base::getMsg(),
            'uid'   => Base::getUid(),
        ];

        return view("notification/index", ['data' => $data]);
    }

   
}
