<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\{MessagesModel, NotificationModel};
use App\Models\User\UserModel;
use App\Validate\{Validator, RulesMessage};
use Meta, Msg;

class MessagesController extends Controller
{
    public function index()
    {
        render(
            '/messages/index',
            [
                'meta'  => Meta::get(__('app.private_messages')),
                'data'  => [
                    'dialogs'  => $this->dialogs(),
                ]
            ]
        );
    }

    /**
     * All dialogues
     * Все диалоги   
     */
    public function dialogs(): array
    {
        $result = [];
        $messages_dialog = MessagesModel::getMessages();

        if ($messages_dialog) {

            foreach ($messages_dialog as $ind => $row) {

                // Принимающий  AND $row['dialog_recipient_count']
                if ($row['dialog_recipient_id'] == $this->container->user()->id()) {
                    $row['unread']   = $row['dialog_recipient_unread'];
                    $row['count']    = $row['dialog_recipient_count'];

                    // Отправляющий  AND $row['dialog_sender_count']    
                } else if ($row['dialog_sender_id'] == $this->container->user()->id()) {
                    $row['unread']   = $row['dialog_sender_unread'];
                    $row['count']    = $row['dialog_sender_count'];
                }

                $row['msg_user']    = UserModel::get($row['dialog_sender_id'], 'id');
                $row['msg_to_user'] = UserModel::get($row['dialog_recipient_id'], 'id');
                $row['message']     = MessagesModel::getMessageOne($row['dialog_id']);
                $result[$ind]       = $row;
            }
        }

        return $result;
    }

    public function dialog()
    {
        $id = Request::param('id')->asPositiveInt();
        if (!$dialog = MessagesModel::getDialogById($id)) {
            Msg::redirect(__('msg.no_dialogue'), 'error', url('messages'));
        }

        if ($dialog['dialog_recipient_id'] != $this->container->user()->id() and $dialog['dialog_sender_id'] != $this->container->user()->id()) {
            Msg::redirect(__('msg.no_topic'), 'error', url('messages'));
        }

        // update views, etc. 
        $dialog_id = MessagesModel::setMessageRead($id);

        // id получателя и индификатор события
        NotificationModel::updateMessagesUnread($dialog_id);

        // dialog_recipient_unread
        if ($list = MessagesModel::getMessageByDialogId($id)) {

            if ($dialog['dialog_sender_id'] != $this->container->user()->id()) {
                $recipient_user = UserModel::get($dialog['dialog_sender_id'], 'id');
            } else {
                $recipient_user = UserModel::get($dialog['dialog_recipient_id'], 'id');
            }

            foreach ($list as $key => $val) {
                if ($dialog['dialog_sender_id'] == $this->container->user()->id() and $val['message_sender_remove']) {
                    unset($list[$key]);
                } else if ($dialog['dialog_sender_id'] != $this->container->user()->id() and $val['message_recipient_remove']) {
                    unset($list[$key]);
                } else {
                    $list[$key]['message_content']  =  markdown($val['message_content'], 'text');
                    $list[$key]['login']   = $recipient_user['login'];
                    $list[$key]['avatar']  = $recipient_user['avatar'];
                    // $list[$key]['unread']  = $dialog['dialog_sender_unread'];
                }
            }
        }

        render(
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

    /**
     * Form for sending personal messages from the profile 
     * Форма отправки личных сообщений из профиля
     *
     * @return void
     */
    public function messages()
    {
        $this->limitTl();

        $user  = UserModel::get(Request::param('login')->asString(), 'slug');
        notEmptyOrView404($user);

        // If the dialog exists, then redirect to it
        // Если диалог существует, то редирект в него
        if ($dialog = MessagesModel::availability($user['id'])) {
            redirect('/messages/' . $dialog['dialog_id']);
        }

        render(
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

    /**
     * Sending a private message to a user
     * Отправка сообщения участнику
     *
     * @return void
     */
    public function add()
    {
        $content        = $_POST['content']; // для Markdown
        $recipient_id   = Request::post('recipient')->asInt();

        $this->limitTl();

		$dialog = MessagesModel::availability($recipient_id);

        // If the user does not exist 
        // Если пользователя не существует
        $user  = UserModel::get($recipient_id, 'id');
        notEmptyOrView404($user);


		$dialog_url = !empty($dialog['dialog_id']) ? url('dialogues', ['id' => $dialog['dialog_id']]) : url('profile', ['login' =>  $user['login']]);
		RulesMessage::rules($content, $dialog_url);

        $dialog_id = MessagesModel::sendMessage($recipient_id, $content);
        $url = '/messages/' . $dialog_id;

        NotificationModel::send($recipient_id, NotificationModel::TYPE_PRIVATE_MESSAGES, $url);

        redirect($url);
    }

    /**
     * We will limit the sending of PMs if the level of trust is low
     * Ограничим отправку ЛС, если уровень доверия низок
     */
    public function limitTl(): true
    {
        if (config('trust-levels', 'tl_add_pm') > $this->container->user()->tl()) {
            redirect('/');
        }

        return true;
    }

    /**
     * Let's show the editing form
     * Покажем форму редактирования
     *
     * @return void
     */
    public function addForma()
    {
        $id = Request::post('id')->asInt();
        $message = MessagesModel::getMessage($id);

        insert(
            '/_block/form/form-for-editing',
            [
                'data'  => [
                    'id'        => $id,
                    'content'    => $message['message_content'],
                    'type'         => 'message',
                ]
            ]
        );
    }

    public function edit()
    {
        $id  = Request::post('id')->asInt();
        $content = $_POST['content']; // для Markdown

        // Access check
        $message = MessagesModel::getMessage($id);
        notEmptyOrView404($message);

        if ($message['message_sender_id'] != $this->container->user()->id()) {
            Msg::redirect(__('msg.went_wrong'), 'error', url('dialogues', ['id' => $message['message_dialog_id']]));
        }

        Validator::Length($content, 6, 5000, 'content', url('dialogues', ['id' => $message['message_dialog_id']]));

        MessagesModel::edit($id, $content);

        Msg::redirect(__('msg.change_saved'), 'success', url('dialogues', ['id' => $message['message_dialog_id']]));
    }
}
