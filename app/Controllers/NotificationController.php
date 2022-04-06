<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationModel;
use Translate, SendEmail, Tpl, Meta, UserData;

class NotificationController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user = UserData::get();
    }

    public function index()
    {
        return Tpl::agRender(
            '/notification/index',
            [
                'meta'  => Meta::get(Translate::get('notifications')),
                'data'  => [
                    'sheet'         => 'notifications',
                    'type'          => 'notifications',
                    'notifications' => NotificationModel::listNotification($this->user['id']),
                ]
            ]
        );
    }

    // Change the subscription flag read or not (follow the link) 
    // Изменяем флаг подписки прочитан или нет (переход по ссылке)
    public function read()
    {
        $notif_id   = Request::getInt('id');
        $info       = NotificationModel::getNotification($notif_id);

        if ($this->user['id'] != $info['recipient_id']) {
            return false;
        }

        NotificationModel::updateMessagesUnread($this->user['id'], $notif_id);

        redirect('/' .  $info['url']);
    }

    public function remove()
    {
        NotificationModel::setRemove($this->user['id']);

        redirect(getUrlByName('notifications'));
    }

    public static function setBell($user_id)
    {
        return NotificationModel::bell($user_id);
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
            NotificationModel::send(
                [
                    'sender_id'    => $this->user['id'],
                    'recipient_id' => $recipient_id,  // admin
                    'action_type'  => $action_type, // Система флагов  
                    'url'          => $url,
                ]
            );

            SendEmail::mailText($recipient_id, 'appealed');
        }

        return true;
    }
}
