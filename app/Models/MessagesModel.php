<?php

namespace App\Models;
use XdORM\XD;
use App\Models\NotificationsModel;

class MessagesModel extends \MainModel
{
    
    // Все диалоги
    public static function getMessages($user_id)
    {
        $query = XD::select('*')->from(['messages_dialog'])
                ->where(['sender_uid'], '=', $user_id) // AND sender_count > 0
                ->or(['recipient_uid'], '=', $user_id) // recipient_count > 0
                ->orderBy(['update_time'])->desc();

        return $query->getSelect();
    }
  
    // Получаем диалог по id
 	public static function getDialogById($dialog_id)
	{
        return  XD::select('*')->from(['messages_dialog'])->where(['id'], '=', $dialog_id)->getSelectOne();
	}
 
    // Диалог
    public static function getDialog($id)
    {
        $query = XD::select('*')->from(['messages']);
       
        return count($query->getSelect());
    }
    
    // Пересчет просмотрено или нет
	public static function setMessageRead($dialog_id, $uid, $receipt = true)
	{
		if (!$messages_dialog = self::getDialogById($dialog_id))
		{
			return false;
		}

        // Отправитель
		if ($messages_dialog['sender_uid'] == $uid)
		{

            XD::update(['messages_dialog'])->set(['sender_unread'], '=', $uid)
                                           ->where(['sender_unread'], '=', 0)
                                           ->and(['id'], '=', $dialog_id)->run();
            
			if ($receipt)
			{
                
                XD::update(['messages_dialog'])->set(['sender_unread'], '=', $uid)
                                               ->where(['sender_unread'], '=', 0)
                                               ->and(['id'], '=', $dialog_id)->run();
            
			}  

		}
        // Получатель
		if ($messages_dialog['recipient_uid'] == $uid)
		{

            XD::update(['messages_dialog'])->set(['recipient_unread'], '=', $uid)
                               ->where(['recipient_unread'], '=', 0)
                               ->and(['id'], '=', $dialog_id)->run();
            
		}
        
        // uid получателя и индификатор события
        NotificationsModel::updateMessagesUnread($uid, $dialog_id);

		return true;
	}
    
    
	public static function getMessageByDialogId($dialog_id)
	{

        $query = XD::select('*')->from(['messages'])
                ->where(['dialog_id'], '=', $dialog_id)
                ->orderBy(['id'])->desc();

        $inbox = $query->getSelect();
     
		if ($inbox)
		{
			foreach ($inbox AS $key => $val)
			{
				$message[$val['id']] = $val;
			}
		}

		return $message;
	}

    // Количество сообщений
    public static function getMessagesTotal($user_id)
    {
        $query = XD::select('*')->from(['messages_dialog']);
       
        return count($query->getSelect());
    }

	public static function getLastMessages($dialog_ids)
	{
		if (!is_array($dialog_ids))
		{
			return false;
		}

		foreach ($dialog_ids as $dialog_id)
		{
            
            $last_message =  XD::select('*')->from(['messages'])->where(['dialog_id'], '=', $dialog_id)->orderBy(['id'])->desc();
         
		}

		return $last_message;
	}

    // Записываем личное сообщение
    public static function SendMessage($sender_uid, $recipient_uid, $message)
    {
		if (!$sender_uid OR !$recipient_uid OR !$message)
		{
			return false;
		}

		if (!$messages_dialog = self::getDialogByUser($sender_uid, $recipient_uid))
		{
            
        // Записываем диалог (если его нет)
        XD::insertInto(['messages_dialog'], '(', ['sender_uid'], ',', ['sender_unread'], ',', ['recipient_uid'], ',', ['recipient_unread'], ',', ['sender_count'],',', ['recipient_count'], ')')->values( '(', XD::setList([$sender_uid, 1, $recipient_uid, 0, 0, 0]), ')' )->run();
           
        // Вернем id диалога для записи в `dialog_id` ниже          
        $messages_dialog_id = XD::select()->last_insert_id('()')->getSelectValue(); // SELECT LAST_INSERT_ID();
            
		} else {
            
			$messages_dialog_id = $messages_dialog['id'];
		}
        
        XD::insertInto(['messages'], '(', ['dialog_id'], ',', ['message'], ',', ['uid'], ')')->values( '(', XD::setList([$messages_dialog_id, $message, $sender_uid]), ')' )->run();
 
		// self::updateDialogCount($messages_dialog_id, $sender_uid);
        /* Где хранить будем изменение и пересчет?
		   UserModel::updateInboxUnread($recipient_uid);

		if ($user_info = UserModel::getUserId($sender_uid))
		{
			// Отправим на E-mail, потом, если он захочет, возможно...
		} */
           
        $type = 1; // Личные сообщения        
        NotificationsModel::send($sender_uid, $recipient_uid, $type, $messages_dialog_id, '', 1);
 
		return $message_id;
    }

    // Изменение количество сообщений
	public static function updateDialogCount($dialog_id, $uid)
	{
		if (!$inbox_dialog = self::getDialogById($dialog_id))
		{
			return false;
		}

        $update_time = date("Y-m-d H:i:s");

        // Оновляем статистику личных сообщений
        $query = XD::select('*')->from(['messages_dialog'])->where(['id'], '=', $dialog_id)->getSelectOne();
        $sender_count    = $query['sender_count'] + 1; 
        $recipient_count = $query['recipient_count'] + 1;
       
       
        XD::update(['messages_dialog'])->set(['sender_count'], '=', $sender_count, ',', ['update_time'], '=', $update_time, ',', ['recipient_count'], '=', $recipient_count)->where(['id'], '=', $dialog_id)->run();

		if ($inbox_dialog['sender_uid'] == $uid)
		{
			// SET recipient_unread = recipient_unread + 1 WHERE id = " . intval($dialog_id));
            
            $recipient_unread = 'recipient_unread + 1';
            XD::update(['messages_dialog'])->set(['recipient_unread'], '=', $recipient_unread)->where(['id'], '=', $dialog_id)->run();
            
		}
		else
		{
            $sender_unread = 'sender_unread + 1';
            XD::update(['messages_dialog'])->set(['sender_unread'], '=', $sender_unread)->where(['id'], '=', $dialog_id)->run();
            
			// " SET sender_unread = sender_unread + 1 WHERE id = " . intval($dialog_id));
		}
	}
    
    // Информация о участнике
	public static function getDialogByUser($sender_uid, $recipient_uid)
	{
        $query = XD::select('*')->from(['messages_dialog'])
                ->where(['sender_uid'], '=', $sender_uid)
                ->and(['recipient_uid'], '=', $recipient_uid)
                ->or(['recipient_uid'], '=', $sender_uid)
                ->and(['sender_uid'], '=', $recipient_uid);

         return $query->getSelectOne();
	}
    
}
