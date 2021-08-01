<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use App\Models\UserModel;
use Lori\Config;
use Lori\Base;

class NotificationsController extends \MainController
{
    // Страница уведомлений участника
    public function index()
    {
        $login  = \Request::get('login');

        $uid    = Base::getUid();
        $user   = UserModel::getUser($uid['login'], 'slug');

        // Если страница закладок не участника
        if ($login != $uid['login']) {
            redirect('/u/' . $user['login'] . '/notifications');
        }

        // Данные участника и список уведомлений
        $list = NotificationsModel::listNotification($uid['id']);

        $result = array();
        foreach ($list as $ind => $row) {

            $row['add_time']        = lang_date($row['add_time']);
            $result[$ind]           = $row;
        }

        $data = [
            'h1'            => lang('Notifications'),
            'meta_title'    => lang('Notifications') . ' | ' . Config::get(Config::PARAM_NAME),
            'sheet'         => 'notifications',
        ];

        return view(PR_VIEW_DIR . '/notification/index', ['data' => $data, 'uid' => $uid, 'list' => $result]);
    }

    // Изменяем флаг подписки прочитан или нет (переход по ссылке)
    public function read()
    {
        $uid        = Base::getUid();
        $notif_id   = \Request::getInt('id');
        $info       = NotificationsModel::getNotification($notif_id);

        if ($uid['id'] != $info['recipient_uid']) {
            return false;
        }

        // Если личные сообщения 
        if ($info['action_type'] == 1) {
            $info['url'] = 'messages/read/' . $info['connection_type'];
        }

        NotificationsModel::updateMessagesUnread($uid['id'], $notif_id);

        redirect('/' .  $info['url']);
    }

    // Удаляем уведомления
    public function remove()
    {
        $uid    = Base::getUid();
        NotificationsModel::setRemove($uid['id']);
        redirect('/u/' . $uid['login'] . '/notifications');
    }
}
