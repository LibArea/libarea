<?php

namespace App\Models;
use XdORM\XD;
use App\Models\UserModel;

class NotificationsModel extends \MainModel
{
    // $action_type
    // 1 - сообщение
    // 2 - пост
    // 3 - ответ
    // 4 - комментарий
    // 5 - пост в чат
    // 6 - понравился пост
    // 7 - понравился ответ
    // 10 - обращение в постах (@login)
    // 11 - в ответах (@login)
    // 12 - в комментариях (@login)
    // 15 -  аудит

    // Лист уведомлений
    public static function listNotification($user_uid)
    {
        $query = XD::select('*')->from(['notification'])
                ->leftJoin(['users'])->on(['id'], '=', ['sender_uid'])
                ->where(['recipient_uid'], '=', $user_uid)
                ->orderBy(['notification_id'])->desc();

        return $query->getSelect();
    }  

    // Уведомление
    public static function usersNotification($user_uid)
    {
        $query = XD::select('*')->from(['notification'])
                ->where(['recipient_uid'], '=', $user_uid)
                ->and(['read_flag'], '=', 0);  

        return  $query->getSelectOne();
    }  


	// Уведомление
    // Пример: 2 - ответы
    // NotificationsModel::send($sender_uid, $recipient_uid, $type, $messages_dialog_id, $url, 1);
	public static function send($sender_uid, $recipient_uid, $action_type, $connection_type, $url, $model_type = 0)
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

        XD::insertInto(['notification'], '(', ['sender_uid'], ',', ['recipient_uid'], ',', ['action_type'], ',', ['connection_type'], ',', ['url'], ',', ['read_flag'], ')')->values( '(', XD::setList([$sender_uid, $recipient_uid,$action_type, $connection_type, $url, 0]), ')' )->run();
       
		return true;
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
    public static function updateMessagesUnread($uid, $notif_id)
    {
        XD::update(['notification'])->set(['read_flag'], '=', 1)
                                 ->where(['recipient_uid'], '=', $uid)
                                 ->and(['notification_id'], '=', $notif_id)
                                 ->run();
        return true;
    } 
    
    public static function getNotification($id)
    {
        return  XD::select('*')->from(['notification'])
                ->where(['notification_id'], '=', $id)->getSelectOne();
    }  

    public static function setRemove($user_id)
    {
        XD::update(['notification'])->set(['read_flag'], '=', 1)
                                 ->where(['recipient_uid'], '=', $user_id)
                                 ->run();
        return true;
    }
}
