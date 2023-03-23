<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{MessagesModel, NotificationModel};
use Meta;

class MessagesController extends Controller
{
    public function index()
    {
        return $this->render(
            '/messages/index',
            [
                'meta'  => Meta::get(__('app.private_messages')),
                'data'  => [
                    'dialogs'  => $this->dialogs(),
                ]
            ]
        );
    }

    // All dialogues
    // Все диалоги    
    public function dialogs()
    {
        $result = [];
        $messages_dialog = MessagesModel::getMessages();

        if ($messages_dialog) {

            foreach ($messages_dialog as $ind => $row) {

                // Принимающий  AND $row['dialog_recipient_count']
                if ($row['dialog_recipient_id'] == $this->user['id']) {
                    $row['unread']   = $row['dialog_recipient_unread'];
                    $row['count']    = $row['dialog_recipient_count'];

                    // Отправляющий  AND $row['dialog_sender_count']    
                } else if ($row['dialog_sender_id'] == $this->user['id']) {
                    $row['unread']   = $row['dialog_sender_unread'];
                    $row['count']    = $row['dialog_sender_count'];
                }

                $row['msg_user']    = UserModel::getUser($row['dialog_sender_id'], 'id');
                $row['msg_to_user'] = UserModel::getUser($row['dialog_recipient_id'], 'id');
                $row['message']     = MessagesModel::getMessageOne($row['dialog_id']);
                $result[$ind]       = $row;
            }
        }

        return $result;
    }

    public function dialog()
    {
        $id = Request::getInt('id');
        if (!$dialog = MessagesModel::getDialogById($id)) {
            is_return(__('msg.no_dialogue'), 'error', url('messages', ['login' => $this->user['login']]));
        }

        if ($dialog['dialog_recipient_id'] != $this->user['id'] and $dialog['dialog_sender_id'] != $this->user['id']) {
            is_return(__('msg.no_topic'), 'error', url('messages', ['login' => $this->user['login']]));
        }

        // update views, etc. 
        $dialog_id = MessagesModel::setMessageRead($id);

        // id получателя и индификатор события
        NotificationModel::updateMessagesUnread($dialog_id);

        // dialog_recipient_unread
        if ($list = MessagesModel::getMessageByDialogId($id)) {

            if ($dialog['dialog_sender_id'] != $this->user['id']) {
                $recipient_user = UserModel::getUser($dialog['dialog_sender_id'], 'id');
            } else {
                $recipient_user = UserModel::getUser($dialog['dialog_recipient_id'], 'id');
            }

            foreach ($list as $key => $val) {
                if ($dialog['dialog_sender_id'] == $this->user['id'] and $val['message_sender_remove']) {
                    unset($list[$key]);
                } else if ($dialog['dialog_sender_id'] != $this->user['id'] and $val['message_recipient_remove']) {
                    unset($list[$key]); 
                } else {
                    $list[$key]['message_content']  =  markdown($val['message_content'], 'text');
                    $list[$key]['login']   = $recipient_user['login'];
                    $list[$key]['avatar']  = $recipient_user['avatar'];
                   // $list[$key]['unread']  = $dialog['dialog_sender_unread'];
                }
            }
        }

        Request::getResources()->addBottomStyles('/assets/js/editor/easymde.min.css');
        Request::getResources()->addBottomScript('/assets/js/editor/easymde.min.js');

        return $this->render(
            '/messages/dialog',
            [
                'meta'  => Meta::get(__('app.dialogue')),
                'data'  => [
                    'sheet'             => __('app.dialogue') . ' — <b>' . $list[$key]['login'] . '</b>',
                    'list'              => $list,
                    'recipient_user'    => $recipient_user,
                    'dialogs'           => $this->dialogs(),
                ]
            ]
        );
    }

    // Form for sending personal messages from the profile 
    // Форма отправки личных сообщений из профиля
    public function messages()
    {
        $this->limitTl();

        $user  = UserModel::getUser(Request::get('login'), 'slug');
        notEmptyOrView404($user);

        // If the dialog exists, then redirect to it
        // Если диалог существует, то редирект в него
        if ($dialog = MessagesModel::availability($user['id'])) {
            redirect('/messages/' . $dialog['dialog_id']);
        }

        return $this->render(
            '/messages/user-add-messages',
            [
                'meta'  => Meta::get(__('app.send_message')),
                'data'  => [
                    'recipient_uid' => $user['id'],
                    'login'         => $user['login'],
                ]
            ]
        );
    }

    // Sending a private message to a user 
    // Отправка сообщения участнику
    public function create()
    {
        $content        = $_POST['content']; // для Markdown
        $recipient_id   = Request::getPost('recipient');

        $this->limitTl();

        // Private message is empty
        // Если личное сообщение пустое
        if ($content == '') {
            is_return(__('msg.enter_content'), 'error', url('messages', ['login' => $this->user['login']]));
        }

        // If the user does not exist 
        // Если пользователя не существует
        $user  = UserModel::getUser($recipient_id, 'id');
        notEmptyOrView404($user);

        $dialog_id = MessagesModel::sendMessage($recipient_id, $content);
        $url = '/messages/' . $dialog_id;

        NotificationModel::send($recipient_id, NotificationModel::TYPE_PRIVATE_MESSAGES, $url);

        redirect($url);
    }

    // We will limit the sending of PMs if the level of trust is low
    // Ограничим отправк ЛС, если уровень доверия низок
    public function limitTl()
    {
        if (config('trust-levels.tl_add_pm') > $this->user['trust_level']) {
            redirect('/');
        }

        return true;
    }
}
