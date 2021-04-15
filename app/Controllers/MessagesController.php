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

        // Данные участника
        $account = Request::getSession('account');
        $user_id = $account['user_id'];
        
        if ($messages_dialog = MessagesModel::getMessages($user_id))
		{
          
            $messages_total_rows = MessagesModel::getMessagesTotal($user_id);

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

                if(!$row['msg_user']['avatar']) {
                    $row['msg_user']['avatar'] = 'noavatar.png';
                } 
           
                $row['unread_num']  = Base::ru_num('pm', $row['unread']);
                $row['count_num']   = Base::ru_num('pm', $row['count']);
                
                $result[$ind]       = $row;
             
			}
		} else {
            $result = [];
        }

        $uid  = Base::getUid();
		$data = [
            'title'       => 'Личные сообщения',
            'description' => 'Страница личных сообщений',
            'messages'    => $result,
        ];
        
        
        
        return view(PR_VIEW_DIR . '/messages/index', ['data' => $data, 'uid' => $uid]);
	}

	public function dialog()
	{
        // Данные участника
        $account = Request::getSession('account');
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
        
        if(!$recipient_user['avatar'] ) {
                $recipient_user['avatar'] = 'noavatar.png';
            } 
        
        $uid  = Base::getUid();
        $data = [
            'title'          => 'Диалог',
            'description'    => 'Страница диалогов',
            'list'           => $list,  
            'recipient_user' => $recipient_user,
        ];

        return view(PR_VIEW_DIR . '/messages/dialog', ['data' => $data, 'uid' => $uid]);
	}
    
    // отправка сообщения участнику
	public function send()
	{
        
        // Данные участника
        $account = Request::getSession('account');
        $sender_uid = $account['user_id'];

        $message = Request::getPost('message');
        $recipient_uid = Request::getPost('recipient');

        // Введите содержание сообщения
        if ($message == '')
        {
            Base::addMsg('Введите содержание сообщения', 'error');
            redirect('/register');
        }

        // Этого пользователь не существует (добавить!!!!!!!!!!!)

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
        
        $login = Request::get('login');
        
        if(!$user = UserModel::getUserLogin($login))
        {
            Base::addMsg('Пользователя не существует', 'error');
            redirect('/');
        }  
        
        $uid  = Base::getUid();
        $data = [
            'title'          => 'Отправить сообщение ' . $login,
            'description'    => 'Страница отправки сообщения',
            'recipient_uid'  => $user['id'],
        ];

        return view(PR_VIEW_DIR . '/messages/user-add-messages', ['data' => $data, 'uid' => $uid]);
      
    }
    
}
