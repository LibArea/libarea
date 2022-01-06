<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\User\UserModel;
use App\Models\MessagesModel;
use Content, Config, Validation, Translate;

class MessagesController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid = UserData::getUid();
    }

    public function index()
    {
        if ($messages_dialog = MessagesModel::getMessages($this->uid['user_id'])) {

            // $messages_total_rows = MessagesModel::getMessagesTotal($this->uid['user_id']);
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
                if ($row['dialog_recipient_id'] == $this->uid['user_id']) {
                    $row['unread']   = $row['dialog_recipient_unread'];
                    $row['count']    = $row['dialog_recipient_count'];

                    // Отправляющий  AND $row['dialog_sender_count']    
                } else if ($row['dialog_sender_id'] == $this->uid['user_id']) {
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

        return agRender(
            '/messages/messages',
            [
                'meta'  => meta($m = [], Translate::get('private messages')),
                'uid'   => $this->uid,
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
        // Данные участника
        $id     = Request::getInt('id');

        if (!$dialog = MessagesModel::getDialogById($id)) {
            addMsg(Translate::get('the dialog does not exist'), 'error');
            redirect(getUrlByName('messages', ['login' => $this->uid['user_login']]));
        }

        if ($dialog['dialog_recipient_id'] != $this->uid['user_id'] and $dialog['dialog_sender_id'] != $this->uid['user_id']) {
            addMsg(Translate::get('the topic does not exist'), 'error');
            redirect(getUrlByName('messages', ['login' => $this->uid['user_login']]));
        }

        // обновляем просмотры и т.д.
        MessagesModel::setMessageRead($id, $this->uid['user_id']);
        // dialog_recipient_unread
        if ($list = MessagesModel::getMessageByDialogId($id)) {

            if ($dialog['dialog_sender_id'] != $this->uid['user_id']) {
                $recipient_user = UserModel::getUser($dialog['dialog_sender_id'], 'id');
            } else {
                $recipient_user = UserModel::getUser($dialog['dialog_recipient_id'], 'id');
            }

            foreach ($list as $key => $val) {
                if ($dialog['dialog_sender_id'] == $this->uid['user_id'] and $val['message_sender_remove']) {
                    unset($list[$key]);
                } else if ($dialog['dialog_sender_id'] != $this->uid['user_id'] and $val['message_recipient_remove']) {
                    unset($list[$key]);
                } else {
                    $list[$key]['message_content']  =  Content::text($val['message_content'], 'text');
                    $list[$key]['user_login']   = $recipient_user['user_login'];
                    $list[$key]['user_avatar']  = $recipient_user['user_avatar'];
                    $list[$key]['unread']       = $dialog['dialog_recipient_unread'];
                }
            }
        }

        return agRender(
            '/messages/dialog',
            [
                'meta'  => meta($m = [], Translate::get('dialogue')),
                'uid'   => $this->uid,
                'data'  => [
                    'h1'                => Translate::get('dialogue') . ' - ' . $list[$key]['user_login'],
                    'sheet'             => Translate::get('dialogue') . ' - ' . $list[$key]['user_login'],
                    'type'              => 'type',
                    'list'              => $list,
                    'recipient_user'    => $recipient_user,
                    'dialog'            => MessagesModel::lastBranches($this->uid['user_id']),
                ]
            ]
        );
    }

    // Форма отправки из профиля
    public function messages()
    {
        $login      = Request::get('login');
        if (!$user  = UserModel::getUser($login, 'slug')) {
            addMsg(Translate::get('member does not exist'), 'error');
            redirect('/');
        }

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении ЛС
        $add_pm  = Validation::accessPm($this->uid, $user['user_id'], Config::get('general.tl_add_pm'));
        if ($add_pm === false) {
            redirect('/');
        }

        return agRender(
            '/messages/user-add-messages',
            [
                'meta'  => meta($m = [], Translate::get('send a message')),
                'uid'   => $this->uid,
                'data'  => [
                    'recipient_uid' => $user['user_id'],
                    'login'         => $user['user_login'],
                    'type'          => 'type',
                ]
            ]
        );
    }

    // Отправка сообщения участнику
    public function send()
    {
        // Данные участника
        $content        = Request::getPost('content');
        $recipient_id   = Request::getPost('recipient');

        // Если пользователь заморожен
        Content::stopContentQuietМode($this->uid['user_limiting_mode']);

        // Введите содержание сообщения
        if ($content == '') {
            addMsg(Translate::get('enter content'), 'error');
            redirect(getUrlByName('messages', ['login' => $this->uid['user_login']]));
        }

        // Этого пользователь не существует
        $user  = UserModel::getUser($this->uid['user_id'], 'id');
        pageRedirection($user, getUrlByName('messages', ['login' => $this->uid['user_login']]));

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении ЛС
        $add_pm  = Validation::accessPm($this->uid, $recipient_id, Config::get('general.tl_add_pm'));
        if ($add_pm === false) {
            redirect('/');
        }

        MessagesModel::sendMessage($this->uid['user_id'], $recipient_id, $content);

        redirect(getUrlByName('messages'));
    }
}
