<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\NotificationModel;
use SendEmail, Meta;

class NotificationController extends Controller
{
    public function index()
    {
        render(
            '/notification/index',
            [
                'meta'  => Meta::get(__('app.notifications')),
                'data'  => [
                    'notifications' => NotificationModel::listNotification($this->container->user()->id()),
                ]
            ]
        );
    }

    /**
     * Change the subscription flag read or not (follow the link) 
     * Изменяем флаг подписки прочитан или нет (переход по ссылке)
     */
    public function read(): bool
    {
        $notif_id   = Request::param('id')->asInt();
        $info       = NotificationModel::getNotification($notif_id);

        if ($this->container->user()->id() != $info['recipient_id']) {
            return false;
        }

        NotificationModel::updateMessagesUnread($notif_id);

        redirect($info['url']);

        return true;
    }

    public function remove()
    {
        NotificationModel::setRemove();

        redirect(Request::getHeaders()['referer'][0]);
    }

    public function get()
    {
        return NotificationModel::get($this->container->user()->id());
    }

    /**
     * Appeal (@)
     * Обращение (@)
     *
     * @param int $action_type
     * @param array $message
     * @param string $url
     * @param integer|null $owner_id
     */
    public function mention(int $action_type, array $message, string $url, int|null $owner_id = null)
    {
        foreach ($message as $recipient_id) {
            // Prohibit sending to yourself 
            // Запретим отправку себе
            if ($recipient_id == $this->container->user()->id()) {
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

    public function addForma()
    {
        insert(
            '/content/notification/notif-model',
            [
                'data'  => [
                    'notifications'    => NotificationModel::listNotification($this->container->user()->id(), 8),
                ]
            ]
        );
    }
}
