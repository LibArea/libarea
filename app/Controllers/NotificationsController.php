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
        
        $user_id = $account['user_id'];
        
        $list = NotificationsModel::listNotification($user_id);
        
		if (!$list AND $user_info['notification_unread'] != 0)
		{
			// updateNotificationUnread($user_id);
		}
        
        $data = [
            'title' => 'Уведомления',
            'msg'   => Base::getMsg(),
        ];

        return view("notification/index", ['data' => $data]);
    }

   
}
