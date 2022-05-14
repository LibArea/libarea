<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{MessagesModel, NotificationModel};
use Content, Validation, Html, Meta;

class MessagesController extends Controller
{
    public function index()
    {
        if ($messages_dialog = MessagesModel::getMessages($this->user['id'])) {

            foreach ($messages_dialog as $val) {
                $dialog_ids = $val['dialog_id'];
            }
        } else {
            $dialog_ids = null;
        }

        $result = [];
        if ($messages_dialog) {

            $result = [];
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

                $row['unread_num']  = Html::numWord($row['count'], __('app.num_message'), false);
                $row['count_num']   = Html::numWord($row['count'], __('app.num_message'), false);
                $result[$ind]       = $row;
            }
        }

        return $this->render(
            '/messages/messages',
            [
                'meta'  => Meta::get(__('app.private_messages')),
                'data'  => [
                    'sheet'     => 'messages',
                    'type'      => 'messages',
                    'messages'  => $result,
                ]
            ]
        );
    }

    public function dialog()
    {
        $id = Request::getInt('id');
        if (!$dialog = MessagesModel::getDialogById($id)) {
            Validation::ComeBack('msg.no_dialogue', 'error', url('messages', ['login' => $this->user['login']]));
        }

        if ($dialog['dialog_recipient_id'] != $this->user['id'] and $dialog['dialog_sender_id'] != $this->user['id']) {
            Validation::ComeBack('msg.no_topic', 'error', url('messages', ['login' => $this->user['login']]));
        }

        // update views, etc. 
        $dialog_id = MessagesModel::setMessageRead($id, $this->user['id']);

        // id получателя и индификатор события
        NotificationModel::updateMessagesUnread($this->user['id'], $dialog_id);

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
                    $list[$key]['message_content']  =  Content::text($val['message_content'], 'text');
                    $list[$key]['login']   = $recipient_user['login'];
                    $list[$key]['avatar']  = $recipient_user['avatar'];
                    $list[$key]['unread']  = $dialog['dialog_recipient_unread'];
                }
            }
        }

        return $this->render(
            '/messages/dialog',
            [
                'meta'  => Meta::get(__('app.dialogue')),
                'data'  => [
                    'h1'                => __('app.dialogue') . ' - ' . $list[$key]['login'],
                    'sheet'             => __('app.dialogue') . ' - ' . $list[$key]['login'],
                    'type'              => 'type',
                    'list'              => $list,
                    'recipient_user'    => $recipient_user,
                    'dialog'            => MessagesModel::lastBranches($this->user['id']),
                ]
            ]
        );
    }

    // Form for sending personal messages from the profile 
    // Форма отправки личных сообщений из профиля
    public function messages()
    {
        $login      = Request::get('login');
        if (!$user  = UserModel::getUser($login, 'slug')) {
            Validation::ComeBack('msg.no_user', 'error', '/');
        }

        // We will limit the sending of PMs if the level of trust is low
        // Ограничим отправк ЛС, если уровень доверия низок
        if (config('general.tl_add_pm') > $this->user['trust_level']) {
            redirect('/');
        }

        return $this->render(
            '/messages/user-add-messages',
            [
                'meta'  => Meta::get(__('app.send_message')),
                'data'  => [
                    'recipient_uid' => $user['id'],
                    'login'         => $user['login'],
                    'type'          => 'type',
                ]
            ]
        );
    }

    // Sending a private message to a user 
    // Отправка сообщения участнику
    public function send()
    {
        $content        = $_POST['content']; // для Markdown
        $recipient_id   = Request::getPost('recipient');

        // If the user is frozen and if the private message is empty
        // Если пользователь заморожен и если личное сообщение пустое
        (new \App\Controllers\AuditController())->stopContentQuietМode($this->user['limiting_mode']);
        if ($content == '') {
            Validation::ComeBack('msg.enter_content', 'error', url('messages', ['login' => $this->user['login']]));
        }

        // If the user does not exist 
        // Если пользователя не существует
        $user  = UserModel::getUser($this->user['id'], 'id');
        Html::pageRedirection($user, url('messages', ['login' => $this->user['login']]));

        // We will limit the sending of PMs if the level of trust is low
        // Ограничим отправк ЛС, если уровень доверия низок
        if (config('general.tl_add_pm') > $this->user['trust_level']) {
            redirect('/');
        }

        $dialog_id = MessagesModel::sendMessage($this->user['id'], $recipient_id, $content);

        // Оповещение админу
        // Admin notification 
        NotificationModel::send(
            [
                'sender_id'    => $this->user['id'],
                'recipient_id' => $recipient_id,  // admin
                'action_type'  => 1, // Private messages 
                'url'          => '/messages/' . $dialog_id,
            ]
        );

        redirect(url('messages'));
    }
}
