<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\MessagesModel;
use Content, Config, Base, Validation, Translate;

class MessagesController extends MainController
{
    public function index()
    {
        $login  = Request::get('login');
        $uid    = Base::getUid();

        // Ошибочный Slug в Url
        if ($login != $uid['user_login']) {
            redirect(getUrlByName('messages', ['login' => $uid['user_login']]));
        }

        if ($messages_dialog = MessagesModel::getMessages($uid['user_id'])) {

            // $messages_total_rows = MessagesModel::getMessagesTotal($uid['user_id']);
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
                if ($row['dialog_recipient_id'] == $uid['user_id']) {
                    $row['unread']   = $row['dialog_recipient_unread'];
                    $row['count']    = $row['dialog_recipient_count'];

                    // Отправляющий  AND $row['dialog_sender_count']    
                } else if ($row['dialog_sender_id'] == $uid['user_id']) {
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

        return view(
            '/messages/messages',
            [
                'meta'  => meta($m = [], Translate::get('private messages')),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => 'all-mess',
                    'messages'      => $result,
                ]
            ]
        );
    }

    public function dialog()
    {
        // Данные участника
        $uid    = Base::getUid();
        $id     = Request::getInt('id');

        if (!$dialog = MessagesModel::getDialogById($id)) {
            addMsg(Translate::get('the dialog does not exist'), 'error');
            redirect(getUrlByName('messages', ['login' => $uid['user_login']]));
        }

        if ($dialog['dialog_recipient_id'] != $uid['user_id'] and $dialog['dialog_sender_id'] != $uid['user_id']) {
            addMsg(Translate::get('the topic does not exist'), 'error');
            redirect(getUrlByName('messages', ['login' => $uid['user_login']]));
        }

        // обновляем просмотры и т.д.
        MessagesModel::setMessageRead($id, $uid['user_id']);

        if ($list = MessagesModel::getMessageByDialogId($id)) {

            if ($dialog['dialog_sender_id'] != $uid['user_id']) {
                $recipient_user = UserModel::getUser($dialog['dialog_sender_id'], 'id');
            } else {
                $recipient_user = UserModel::getUser($dialog['dialog_recipient_id'], 'id');
            }

            foreach ($list as $key => $val) {
                if ($dialog['dialog_sender_id'] == $uid['user_id'] and $val['message_sender_remove']) {
                    unset($list[$key]);
                } else if ($dialog['dialog_sender_id'] != $uid['user_id'] and $val['message_recipient_remove']) {
                    unset($list[$key]);
                } else {
                    $list[$key]['message_content']  =  Content::text($val['message_content'], 'line');
                    $list[$key]['user_login']   = $recipient_user['user_login'];
                    $list[$key]['user_avatar']  = $recipient_user['user_avatar'];
                }
            }
        }

        return view(
            '/messages/dialog',
            [
                'meta'  => meta($m = [], Translate::get('dialogue')),
                'uid'   => $uid,
                'data'  => [
                    'h1'                => Translate::get('dialogue') . ' - ' . $list[$key]['user_login'],
                    'sheet'             => 'dialog',
                    'list'              => $list,
                    'recipient_user'    => $recipient_user,
                ]
            ]
        );
    }

    // Форма отправки из профиля
    public function messages()
    {
        $uid        = Base::getUid();
        $login      = Request::get('login');
        if (!$user  = UserModel::getUser($login, 'slug')) {
            addMsg(Translate::get('member does not exist'), 'error');
            redirect('/');
        }

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении ЛС
        $add_pm  = Validation::accessPm($uid, $user['user_id'], Config::get('general.tl_add_pm'));
        if ($add_pm === false) {
            redirect('/');
        }

        return view(
            '/messages/user-add-messages',
            [
                'meta'  => meta($m = [], Translate::get('send a message')),
                'uid'   => $uid,
                'data'  => [
                    'recipient_uid' => $user['user_id'],
                    'login'         => $user['user_login'],
                ]
            ]
        );
    }

    // Отправка сообщения участнику
    public function send()
    {
        // Данные участника
        $uid            = Base::getUid();
        $content        = Request::getPost('content');
        $recipient_id   = Request::getPost('recipient');

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($uid['user_id'], 'id');
        (new \App\Controllers\Auth\BanController())->getBan($user);
        Content::stopContentQuietМode($user);

        // Введите содержание сообщения
        if ($content == '') {
            addMsg(Translate::get('enter content'), 'error');
            redirect(getUrlByName('messages', ['login' => $uid['user_login']]));
        }

        // Этого пользователь не существует
        $user  = UserModel::getUser($uid['user_id'], 'id');
        pageRedirection($user, getUrlByName('messages', ['login' => $uid['user_login']]));

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении ЛС
        $add_pm  = Validation::accessPm($uid, $recipient_id, Config::get('general.tl_add_pm'));
        if ($add_pm === false) {
            redirect('/');
        }

        MessagesModel::sendMessage($uid['user_id'], $recipient_id, $content);

        redirect(getUrlByName('messages', ['login' => $uid['user_login']]));
    }
}
