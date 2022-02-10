<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use Translate, SendEmail, Tpl, UserData;

class NotificationsController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user = UserData::get();
    }

    public function index()
    {
        $list = NotificationsModel::listNotification($this->user['id']);

        $result = [];
        foreach ($list as $ind => $row) {
            $row['add_time']        = lang_date($row['notification_add_time']);
            $result[$ind]           = $row;
        }

        return Tpl::agRender(
            '/notification/index',
            [
                'meta'  => meta($m = [], Translate::get('notifications')),
                'data'  => [
                    'sheet'         => 'notifications',
                    'type'          => 'notifications',
                    'notifications' => $result,
                ]
            ]
        );
    }

    // Change the subscription flag read or not (follow the link) 
    // Изменяем флаг подписки прочитан или нет (переход по ссылке)
    public function read()
    {
        $notif_id   = Request::getInt('id');
        $info       = NotificationsModel::getNotification($notif_id);

        if ($this->user['id'] != $info['notification_recipient_id']) {
            return false;
        }

        NotificationsModel::updateMessagesUnread($this->user['id'], $notif_id);

        redirect('/' .  $info['notification_url']);
    }

    public function remove()
    {
        NotificationsModel::setRemove($this->user['id']);

        redirect(getUrlByName('notifications'));
    }

    public static function setBell($user_id)
    {
        return NotificationsModel::bell($user_id);
    }

    // Appeal (@)
    public function mention($action_type, $message, $connection_type, $url, $owner_id = null)
    {
        foreach ($message as $recipient_id) {
            // Prohibit sending to yourself 
            // Запретим отправку себе
            if ($recipient_id == $this->user['id']) {
                continue;
            }

            // Forbid sending a reply to the author 
            // Запретим отправку автору ответа
            if ($owner_id) {
                if ($recipient_id == $owner_id) {
                    continue;
                }
            }

            // Оповещение админу
            // Admin notification 
            NotificationsModel::send(
                [
                    'notification_sender_id'    => $this->user['id'],
                    'notification_recipient_id' => $recipient_id,  // admin
                    'notification_action_type'  => $action_type, // Система флагов  
                    'notification_url'          => $url,
                    'notification_read_flag'    => 0,
                ]
            );

            SendEmail::mailText($recipient_id, 'appealed');
        }

        return true;
    }
}
