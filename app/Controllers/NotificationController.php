<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationModel;
use SendEmail, Meta, UserData;

class NotificationController extends Controller
{
    public function index()
    {
        return $this->render(
            '/notification/index',
            [
                'meta'  => Meta::get(__('app.notifications')),
                'data'  => [
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

        NotificationModel::updateMessagesUnread($notif_id);

        redirect('/' .  $info['url']);
    }

    public function remove()
    {
        NotificationModel::setRemove();

        redirect(url('notifications'));
    }

    public static function get()
    {
        return NotificationModel::get(UserData::getUserId());
    }

    // Appeal (@)
    public function mention($action_type, $message, $url, $owner_id = null)
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

            NotificationModel::send($recipient_id, $action_type, $url);

            SendEmail::mailText($recipient_id, 'appealed');
        }

        return true;
    }
}
