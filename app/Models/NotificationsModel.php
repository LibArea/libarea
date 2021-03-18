<?php

namespace App\Models;
use XdORM\XD;
use App\Models\UserModel;

class NotificationsModel extends \MainModel
{
    
    // Лист уведомлений для участника
    public static function listNotification($user_uid)
    {

        $query = XD::select('*')->from(['notification'])
                ->leftJoin(['users'])->on(['id'], '=', ['sender_uid'])
                ->where(['recipient_uid'], '=', $user_uid)
                ->orderBy(['notification_id'])->desc();

        return $query->getSelect();

    }  

    // Лист уведомлений для участника
    public static function usersNotification($user_uid)
    {

        $query = XD::select('*')->from(['notification'])
                ->where(['recipient_uid'], '=', $user_uid)
                ->and(['read_flag'], '=', 0);  

        return  $query->getSelectOne();

    }  


	// Уведомления
    // NotificationsModel::send($sender_uid, $recipient_uid, $type, $messages_dialog_id, 1);
	public static function send($sender_uid, $recipient_uid, $action_type, $connection_type, $model_type = 0)
	{
		if (!$recipient_uid)
		{
			return false;
		}

        // Настройки участника
		if (!$action_type OR !self::check_notification_setting($recipient_uid, $action_type))
		{
			// return false; 
		} 

        XD::insertInto(['notification'], '(', ['sender_uid'], ',', ['recipient_uid'], ',', ['action_type'], ',', ['connection_type'], ',', ['read_flag'], ')')->values( '(', XD::setList([$sender_uid, $recipient_uid,$action_type, $connection_type, 0]), ')' )->run();
       
		return $notification_id;
		
	}

	// Проверить настройки уведомлений указанного пользователя 
	public static function check_notification_setting($recipient_uid, $action_type)
	{
		if (!$action_type)
		{
			return false;
		}
         
		$notification_setting = UserModel::getNotificationSettingByUid($recipient_uid);

		if ($action_type)
		{
			return false;
		}

		return true;
	}
    
    // Оповещение просмотрено
    public static function updateMessagesUnread($uid, $connection_type)
    {
     
        XD::update(['notification'])->set(['read_flag'], '=', 1)
                                 ->where(['recipient_uid'], '=', $uid)
                                 ->and(['connection_type'], '=', $connection_type)
                                 ->run();
        return true;
     
    } 
    
}
