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

    // Change the subscription flag read or not (follow the link) 
    // Изменяем флаг подписки прочитан или нет (переход по ссылке)
    public function read()
    {
        $notif_id   = Request::getInt('id');
        $info       = NotificationsModel::getNotification($notif_id);

        if ($this->uid['user_id'] != $info['notification_recipient_id']) {
            return false;
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

    // Appeal (@)
    public function mention($action_type, $message, $connection_type, $url, $owner_id = null)
    {
        $sender_id = $this->uid['user_id'];
        foreach ($message as $recipient_id) {
            // Prohibit sending to yourself 
            // Запретим отправку себе
            if ($recipient_id == $sender_id) {
                continue;
            }

            // Forbid sending a reply to the author 
            // Запретим отправку автору ответа
            if ($owner_id) {
                if ($recipient_id == $owner_id) {
                    continue;
                }
            }
            
            NotificationsModel::send(compact('sender_id', 'recipient_id', 'action_type', 'url'));

            SendEmail::mailText($recipient_id, 'appealed');
        }

        return true;
    }
}
