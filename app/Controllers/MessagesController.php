<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\MessagesModel;
use App\Models\UserModel;
use Lori\Base;

class MessagesController extends \MainController
{

	public function index()
	{
        // Страница участника и данные
        $login      = \Request::get('login');
        $uid    = Base::getUid();
        
        // Ошибочный Slug в Url
        if($login != $uid['login']) {
            redirect('/u/' . $uid['login'] . '/messages');
        }
       
        if ($messages_dialog = MessagesModel::getMessages($uid['id'])) 
		{
            $messages_total_rows = MessagesModel::getMessagesTotal($uid['id']);

            foreach ($messages_dialog as $val)
            {
                $dialog_ids = $val['id'];
            } 
            
		} else {
            $dialog_ids = null;
        }
        
        if ($dialog_ids)
        {
            $last_message = MessagesModel::getLastMessages($dialog_ids);
        }
  
	    if ($messages_dialog)
		{
 
            $result = Array();
			foreach ($messages_dialog as $ind => $row)
			{
               
                if ($row['recipient_uid'] == $uid['id']) // Принимающий  AND $row['recipient_count']
                {
                    $row['unread']   = $row['recipient_unread'];
                    $row['count']    = $row['recipient_count'];
                    $row['msg_user'] = UserModel::getUserId($row['sender_uid']);
                }
                else if ($row['sender_uid'] == $uid['id']) // Отправляющий  AND $row['sender_count']
                {
                    $row['unread']   = $row['sender_unread'];
                    $row['count']    = $row['sender_count'];
                    $row['msg_user'] = UserModel::getUserId($row['sender_uid']);
                } 

                $row['unread_num']  = word_form($row['unread'], lang('Message'), lang('Messages-m'), lang('Messages'));
                $row['count_num']   = word_form($row['count'], lang('Message'), lang('Messages-m'), lang('Messages'));

                $result[$ind]       = $row;
             
			}
            
		} else {
            $result = [];
        }

		$data = [
            'h1'            => lang('Private messages'),
            'meta_title'    => lang('Private messages'),
            'sheet'         => 'all-mess',
            'messages'      => $result,
        ];

        return view(PR_VIEW_DIR . '/messages/index', ['data' => $data, 'uid' => $uid]);
	}

	public function dialog()
	{
        // Данные участника
        $uid  = Base::getUid();

        $id  = Request::getInt('id');

        if (!$dialog = MessagesModel::getDialogById($id))
        {
            Base::addMsg('Указанного диалога не существует', 'error');
            redirect('/messages');
        }

        if ($dialog['recipient_uid'] != $uid['id'] AND $dialog['sender_uid'] != $uid['id'])
        {
            Base::addMsg('Указанного темы не существует', 'error');
            redirect('/u/' . $uid['login'] . '/messages');
        }
        
        // обновляем просмотры и т.д.
        MessagesModel::setMessageRead($id, $uid['id']);
        
        if ($list = MessagesModel::getMessageByDialogId($id))
		{
			if ($dialog['sender_uid'] != $uid['id'])
			{
				$recipient_user = UserModel::getUserId($dialog['sender_uid']);
			}
			else
			{    
				$recipient_user = UserModel::getUserId($dialog['recipient_uid']);
			}
 
            foreach ($list as $key => $val)
            {
                if ($dialog['sender_uid'] == $uid['id'] AND $val['sender_remove'])
                {
                    unset($list[$key]);
                }
                else if ($dialog['sender_uid'] != $uid['id'] AND $val['recipient_remove'])
                {
                    unset($list[$key]);
                    
                } else {
                    $list[$key]['message'] =  $val['message'];
                    $list[$key]['login'] = $recipient_user['login'];
                }
            }
		}
        
        $data = [
            'h1'                => lang('Dialogue'),
            'meta_title'        => lang('Dialogue'),
            'sheet'             => 'dialog',
            'list'              => $list,  
            'recipient_user'    => $recipient_user,
        ];

        return view(PR_VIEW_DIR . '/messages/dialog', ['data' => $data, 'uid' => $uid]);
	}
    
    // отправка сообщения участнику
	public function send()
	{
        // Данные участника
        $uid            = Base::getUid();
        $message        = Request::getPost('message');
        $recipient_uid  = Request::getPost('recipient');

        // Введите содержание сообщения
        if ($message == '')
        {
            Base::addMsg('Введите содержание сообщения', 'error');
            redirect('/u/' . $uid['login'] . '/messages');
        }

        // Этого пользователь не существует
        $user  = UserModel::getUserId($uid['id']);
        if(!$user) {
            Base::addMsg('Введите содержание сообщения', 'error');
            redirect('/u/' . $uid['login'] . '/messages');
        }

        if ($recipient_uid == $uid['id'])
        {
            Base::addMsg('Себе отправлять сообщение нельзя', 'error');
            redirect('/u/' . $uid['login'] . '/messages');
        }

        MessagesModel::SendMessage($uid['id'], $recipient_uid, $message);

        redirect('/u/' . $uid['login'] . '/messages');
    }
    
    // Форма отправки из профиля
    public function  profilMessages()
    {
        $login      = Request::get('login');
        if(!$user   = UserModel::getUserLogin($login))
        {
            Base::addMsg('Пользователя не существует', 'error');
            redirect('/');
        }  
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Send a message') . ' ' . $login,
            'sheet'         => 'profil-mess',
            'recipient_uid' => $user['id'],
        ];

        return view(PR_VIEW_DIR . '/messages/user-add-messages', ['data' => $data, 'uid' => $uid]);
    }
    
}
