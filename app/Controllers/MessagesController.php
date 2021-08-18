<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\{MessagesModel, UserModel};
use Lori\{Content, Config, Base};

class MessagesController extends \MainController
{
    public function index()
    {
        $login  = Request::get('login');
        $uid    = Base::getUid();

        // Ошибочный Slug в Url
        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/messages');
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

        if ($messages_dialog) {

            $result = array();
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
                $row['message']     = MessagesModel::getMessageOne($row['dialog_id']);
                $row['unread_num']  = word_form($row['unread'], lang('Message'), lang('Messages-m'), lang('Messages'));
                $row['count_num']   = word_form($row['count'], lang('Message'), lang('Messages-m'), lang('Messages'));
                $result[$ind]       = $row;
            }
        } else {
            $result = [];
        }

        $data = [
            'h1'            => lang('Private messages'),
            'meta_title'    => lang('Private messages') . ' | ' . Config::get(Config::PARAM_NAME),
            'sheet'         => 'all-mess',
            'messages'      => $result,
        ];

        return view(PR_VIEW_DIR . '/messages/messages', ['data' => $data, 'uid' => $uid]);
    }

    public function dialog()
    {
        // Данные участника
        $uid    = Base::getUid();
        $id     = Request::getInt('id');

        if (!$dialog = MessagesModel::getDialogById($id)) {
            Base::addMsg(lang('The dialog does not exist'), 'error');
            redirect('/messages');
        }

        if ($dialog['dialog_recipient_id'] != $uid['user_id'] and $dialog['dialog_sender_id'] != $uid['user_id']) {
            Base::addMsg(lang('The topic does not exist'), 'error');
            redirect('/u/' . $uid['user_login'] . '/messages');
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
                    $list[$key]['message_content']  =  $val['message_content'];
                    $list[$key]['user_login']   = $recipient_user['user_login'];
                    $list[$key]['user_avatar']  = $recipient_user['user_avatar'];
                }
            }
        }

        $data = [
            'h1'                => lang('Dialogue') . ' - ' . $list[$key]['user_login'],
            'meta_title'        => lang('Dialogue') . ' | ' . Config::get(Config::PARAM_NAME),
            'sheet'             => 'dialog',
            'list'              => $list,
            'recipient_user'    => $recipient_user,
        ];

        return view(PR_VIEW_DIR . '/messages/dialog', ['data' => $data, 'uid' => $uid]);
    }

    // Форма отправки из профиля
    public function  profilMessages()
    {
        $uid        = Base::getUid();
        $login      = Request::get('login');
        if (!$user  = UserModel::getUser($login, 'slug')) {
            Base::addMsg(lang('Member does not exist'), 'error');
            redirect('/');
        }

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении ЛС
        $add_pm  = accessPm($uid, $user['user_id'], Config::get(Config::PARAM_TL_ADD_PM));
        if ($add_pm === false) {
            redirect('/');
        }

        $data = [
            'h1'            => lang('Send a message') . ': ' . $login,
            'meta_title'    => lang('Send a message') . ' | ' . Config::get(Config::PARAM_NAME),
            'sheet'         => 'profil-mess',
            'recipient_uid' => $user['user_id'],
        ];

        return view(PR_VIEW_DIR . '/messages/user-add-messages', ['data' => $data, 'uid' => $uid]);
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
        Base::accountBan($user);
        Content::stopContentQuietМode($user);

        // Введите содержание сообщения
        if ($content == '') {
            Base::addMsg(lang('Enter content'), 'error');
            redirect('/u/' . $uid['user_login'] . '/messages');
        }

        // Этого пользователь не существует
        $user  = UserModel::getUser($uid['user_id'], 'id');
        if (!$user) {
            Base::addMsg(lang('Member does not exist'), 'error');
            redirect('/u/' . $uid['user_login'] . '/messages');
        }

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении ЛС
        $add_pm  = accessPm($uid, $recipient_id, Config::get(Config::PARAM_TL_ADD_PM));
        if ($add_pm === false) {
            redirect('/');
        }

        MessagesModel::sendMessage($uid['user_id'], $recipient_id, $content);

        redirect('/u/' . $uid['user_login'] . '/messages');
    }
}
