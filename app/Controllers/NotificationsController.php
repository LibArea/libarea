<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use App\Models\UserModel;
use Lori\Base;

class NotificationsController extends \MainController
{
    // Страница уведомлений участника
    public function index()
    {
        $login  = \Request::get('login');
        
        $uid    = Base::getUid();
        $user   = UserModel::getUserLogin($uid['login']);

        // Если страница закладок не участника
        if($login != $uid['login']){
            redirect('/u/' . $user['login'] . '/notifications');
        }
        
        // Данные участника и список уведомлений
        $list = NotificationsModel::listNotification($uid['id']);

        $result = Array();
        foreach($list as $ind => $row) {
            
            $row['add_time']        = Base::ru_date($row['add_time']);
            $result[$ind]           = $row;
         
        } 

        $data = [
            'title'       => 'Уведомления',
            'description' => 'Страница уведомления',
        ];

        return view(PR_VIEW_DIR . '/notification/index', ['data' => $data, 'uid' => $uid, 'list' => $result]);
    }
  
    // Изменяем флаг подписки прочитан или нет (переход по ссылке)
    public function notifRead()
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
    
    // Удаляем уведомления
    public function notifRemove()
    {
        $uid    = Base::getUid();
        NotificationsModel::setRemove($uid['id']);
        redirect('/u/' . $uid['login'] . '/notifications');
    }  
}
