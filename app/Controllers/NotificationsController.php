<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\NotificationsModel;
use Translate;

class NotificationsController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid = UserData::getUid();
    }

    // Страница уведомлений участника
    public function index()
    {
        // Если страница закладок не участника
        $login  = Request::get('login');
        if ($login != $this->uid['user_login']) {
            redirect(getUrlByName('notifications', ['login' => $this->uid['user_login']]));
        }

        // Данные участника и список уведомлений
        $list = NotificationsModel::listNotification($this->uid['user_id']);

        $result = [];
        foreach ($list as $ind => $row) {
            $row['add_time']        = lang_date($row['notification_add_time']);
            $result[$ind]           = $row;
        }

        return agRender(
            '/notification/index',
            [
                'meta'  => meta($m = [], Translate::get('notifications')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => 'notifications',
                    'type'          => 'notifications',
                    'notifications' => $result,
                ]
            ]
        );
    }

    // Изменяем флаг подписки прочитан или нет (переход по ссылке)
    public function read()
    {
        $notif_id   = Request::getInt('id');
        $info       = NotificationsModel::getNotification($notif_id);

        if ($this->uid['user_id'] != $info['notification_recipient_id']) {
            return false;
        }

        // Если личные сообщения 
        if ($info['notification_action_type'] == 1) {
            $info['notification_url'] = 'messages/read/' . $info['notification_connection_type'];
        }

        NotificationsModel::updateMessagesUnread($this->uid['user_id'], $notif_id);

        redirect('/' .  $info['notification_url']);
    }

    public function remove()
    {
        NotificationsModel::setRemove($this->uid['user_id']);

        redirect(getUrlByName('notifications', ['login' => $this->uid['user_login']]));
    }

    public static function setBell($user_id)
    {
        return NotificationsModel::bell($user_id);
    }
}
