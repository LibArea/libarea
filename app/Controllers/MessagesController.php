<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\User\UserModel;
use App\Models\{MessagesModel, NotificationsModel};
use Content, Config, Translate, Tpl;

class MessagesController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user = UserData::get();
    }

    public function index()
    {
        if ($messages_dialog = MessagesModel::getMessages($this->user['id'])) {

            foreach ($messages_dialog as $val) {
                $dialog_ids = $val['dialog_id'];
            }
        } else {
            $dialog_ids = null;
        }

        if ($dialog_ids) {
            $last_message = MessagesModel::getLastMessages($dialog_ids);
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

                $row['unread_num']  = num_word($row['count'], Translate::get('num-message'), false);
                $row['count_num']   = num_word($row['count'], Translate::get('num-message'), false);
                $result[$ind]       = $row;
            }
        }

        return Tpl::agRender(
            '/messages/messages',
            [
                'meta'  => meta($m = [], Translate::get('private messages')),
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
            addMsg(Translate::get('the dialog does not exist'), 'error');
            redirect(getUrlByName('messages', ['login' => $this->user['login']]));
        }

        if ($dialog['dialog_recipient_id'] != $this->user['id'] and $dialog['dialog_sender_id'] != $this->user['id']) {
            addMsg(Translate::get('the topic does not exist'), 'error');
            redirect(getUrlByName('messages', ['login' => $this->user['login']]));
        }

        // update views, etc. 
        $dialog_id = MessagesModel::setMessageRead($id, $this->user['id']);

        // id получателя и индификатор события
        NotificationsModel::updateMessagesUnread($this->user['id'], $dialog_id);

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

        return Tpl::agRender(
            '/messages/dialog',
            [
                'meta'  => meta($m = [], Translate::get('dialogue')),
                'data'  => [
                    'h1'                => Translate::get('dialogue') . ' - ' . $list[$key]['login'],
                    'sheet'             => Translate::get('dialogue') . ' - ' . $list[$key]['login'],
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
            addMsg(Translate::get('member does not exist'), 'error');
            redirect('/');
        }

        // We will limit the sending of PMs if the level of trust is low
        // Ограничим отправк ЛС, если уровень доверия низок
        if (Config::get('general.tl_add_pm') > $this->user['trust_level']) {
            redirect('/');
        }

        return Tpl::agRender(
            '/messages/user-add-messages',
            [
                'meta'  => meta($m = [], Translate::get('send a message')),
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
            addMsg(Translate::get('enter content'), 'error');
            redirect(getUrlByName('messages', ['login' => $this->user['login']]));
        }

        // If the user does not exist 
        // Если пользователя не существует
        $user  = UserModel::getUser($this->user['id'], 'id');
        pageRedirection($user, getUrlByName('messages', ['login' => $this->user['login']]));

        // We will limit the sending of PMs if the level of trust is low
        // Ограничим отправк ЛС, если уровень доверия низок
        if (Config::get('general.tl_add_pm') > $this->user['trust_level']) {
            redirect('/');
        }

        $dialog_id = MessagesModel::sendMessage($this->user['id'], $recipient_id, $content);

        // Оповещение админу
        // Admin notification 
        NotificationsModel::send(
            [
                'notification_sender_id'    => $this->user['id'],
                'notification_recipient_id' => $recipient_id,  // admin
                'notification_action_type'  => 1, // Private messages 
                'notification_url'          => '/messages/' . $dialog_id,
                'notification_read_flag'    => 0,
            ]
        );

        redirect(getUrlByName('messages'));
    }
}
