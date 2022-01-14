<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\NotificationsModel;
use Translate, SendEmail;

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
            $info['notification_url'] = 'messages/' . $info['notification_connection_type'];
        }

        NotificationsModel::updateMessagesUnread($this->uid['user_id'], $notif_id);

        redirect('/' .  $info['notification_url']);
    }

    public function remove()
    {
        NotificationsModel::setRemove($this->uid['user_id']);

        redirect(getUrlByName('notifications'));
    }

    public static function setBell($user_id)
    {
        return NotificationsModel::bell($user_id);
    }

    // Обращение (@)
    public function mention($type, $message, $last_id, $url, $owner_id = null)
    {
        foreach ($message as $user_id) {
            // 
            // Запретим отправку себе
            if ($user_id == $this->uid['user_id']) {
                continue;
            }

            // 
            // И автору ответа
            if ($owner_id) {
                if ($user_id == $owner_id) {
                    continue;
                }
            }

            NotificationsModel::send(
                [
                    'sender_id'         => $this->uid['user_id'],
                    'recipient_id'      => $user_id,
                    'action_type'       => $type,
                    'connection_type'   => $last_id,
                    'content_url'       => $url,
                ]
            );
            SendEmail::mailText($user_id, 'appealed');
        }

        return true;
    }
}
