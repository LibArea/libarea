<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\MessagesModel;
use App\Models\UserModel;
use Base;

class MessagesController extends \MainController
{

	public function index()
	{
        // Авторизировались или нет
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }

        $user_id = $account['user_id'];
        
        if ($messages_dialog = MessagesModel::getMessages($user_id))
		{
          
            $messages_total_rows = MessagesModel::getMessagesTotal($user_id);

            foreach ($messages_dialog as $val)
            {
                $dialog_ids = $val['id'];
            } 
            
		} else {
            $dialog_ids = 0;
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
               
                if ($row['recipient_uid'] == $user_id) // Принимающий  AND $row['recipient_count']
                {

                    $row['unread']   = $row['recipient_unread'];
                    $row['count']    = $row['recipient_count'];
                    $row['msg_user'] = UserModel::getUserId($row['sender_uid']);

                }
                else if ($row['sender_uid'] == $user_id) // Отправляющий  AND $row['sender_count']
                {

                    $row['unread']   = $row['sender_unread'];
                    $row['count']    = $row['sender_count'];
                    $row['msg_user'] = UserModel::getUserId($row['sender_uid']);
                
                } 

                $result[$ind]         = $row;
             
			}
		} else {
            $result = [];
        }

		$data = [
            'title'    => 'Личные сообщения',
            'messages' => $result,
            'msg'      => Base::getMsg(),
        ];
        
        return view("messages/index", ['data' => $data]);
	}

	public function dialog()
	{
        // Авторизировались или нет
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }
        
        $user_id = $account['user_id'];

        $id  = Request::get('id');

        if (!$dialog = MessagesModel::getDialogById($id))
        {
            Base::addMsg('Указанного диалога не существует', 'error');
            redirect('/messages');
        }

        if ($dialog['recipient_uid'] != $user_id AND $dialog['sender_uid'] != $user_id)
        {
            Base::addMsg('Указанного темы не существует', 'error');
            redirect('/messages');
        }
        
        // обновляем просмотры и т.д.
        MessagesModel::setMessageRead($id, $user_id);
        
        if ($list = MessagesModel::getMessageByDialogId($id))
		{
			if ($dialog['sender_uid'] != $user_id)
			{
               
				$recipient_user = UserModel::getUserId($dialog['sender_uid']);
			}
			else
			{    
				$recipient_user = UserModel::getUserId($dialog['recipient_uid']);
			}
 
            foreach ($list as $key => $val)
            {
                if ($dialog['sender_uid'] == $user_id AND $val['sender_remove'])
                {
                    unset($list[$key]);
                }
                else if ($dialog['sender_uid'] != $user_id AND $val['recipient_remove'])
                {
                    unset($list[$key]);
                    
                } else {
                    $list[$key]['message'] =  $val['message'];
                    $list[$key]['login'] = $recipient_user['login'];
                }
            }
		}
        
        $data = [
            'title'          => 'Диалог',
            'list'           => $list,  
            'recipient_user' => $recipient_user,
            'msg'            => Base::getMsg(),
        ];

        return view("messages/dialog", ['data' => $data]);
	}
    
    // отправка сообщения участнику
	public function send()
	{
        
        // Авторизировались или нет
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }

        $sender_uid = $account['user_id'];

        $message = Request::getPost('message');
        $recipient_uid = Request::getPost('recipient');

        // Введите содержание сообщения

        if ($message == '')
        {
            Base::addMsg('Введите содержание сообщения', 'error');
            redirect('/register');
        }

        // Этого пользователь не существует (добавить)

        if ($recipient_uid == $sender_uid)
        {
            Base::addMsg('Себе отправлять сообщение нельзя', 'error');
            redirect('/messages');
        }

        MessagesModel::SendMessage($sender_uid, $recipient_uid, $message);

        redirect('/messages');
    }
    
    // Форма отправки из профиля
    public function  profilMessages()
    {
        
        // Авторизировались или нет
        if(!$account = Request::getSession('account')) {
            redirect('/');
        }
        
        $login = Request::get('login');
        
        if(!$user = UserModel::getUserLogin($login))
        {
            Base::addMsg('Пользователя не существует', 'error');
            redirect('/');
        }    
        
        $data = [
            'title'          => 'Отправить сообщение ' . $login,
            'recipient_uid'  => $user['id'],
            'msg'            => Base::getMsg(),
        ];

        return view("messages/useraddmessages", ['data' => $data]);
      
    }
    
}
