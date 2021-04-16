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
        
        // Данные участника и список уведомлений
        $account = Request::getSession('account');
        $list = NotificationsModel::listNotification($account['user_id']);

        $result = Array();
        foreach($list as $ind => $row){
             
            $row['add_time']        = Base::ru_date($row['add_time']);
            $result[$ind]           = $row;
         
        } 

        $uid  = Base::getUid();
        $data = [
            'title'       => 'Уведомления',
            'description' => 'Страница уведомления',
        ];

        return view(PR_VIEW_DIR . '/notification/index', ['data' => $data, 'uid' => $uid, 'list' => $result]);
    }
  
    // Изменяем флаг подписки прочитан или нет (переход по ссылке)
    public function notificationRead()
    {
        $account    = \Request::getSession('account');
        $notif_id   = \Request::getInt('id');
        $info   = NotificationsModel::getNotification($notif_id);

        if($account['user_id'] != $info['recipient_uid']) {
            return false;
        }

        NotificationsModel::updateMessagesUnread($account['user_id'], $notif_id);

        redirect('/' .  $info['url']);
    }  
}
