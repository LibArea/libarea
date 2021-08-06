<?php

namespace App\Models;

use App\Models\NotificationsModel;
use DB;
use PDO;

class MessagesModel extends \MainModel
{
    // Все диалоги
    public static function getMessages($user_id)
    {
        $sql = "SELECT  
                    id,
                    sender_uid,
                    sender_unread,
                    recipient_uid,
                    recipient_unread,
                    add_time,
                    update_time,
                    sender_count,
                    recipient_count
                        FROM messages_dialog 
                            WHERE sender_uid = :user_id OR recipient_uid = :user_id
                            ORDER BY update_time DESC";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Получаем диалог по id
    public static function getDialogById($dialog_id)
    {
        $sql = "SELECT  
                    id,
                    sender_uid,
                    sender_unread,
                    recipient_uid,
                    recipient_unread,
                    add_time,
                    update_time,
                    sender_count,
                    recipient_count
                        FROM messages_dialog 
                        WHERE id = :dialog_id";

        return DB::run($sql, ['dialog_id' => $dialog_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Пересчет просмотрено или нет
    public static function setMessageRead($dialog_id, $uid, $receipt = true)
    {
        if (!$messages_dialog = self::getDialogById($dialog_id)) {
            return false;
        }

        // Отправитель
        if ($messages_dialog['sender_uid'] == $uid) {

            $sql = "UPDATE messages_dialog SET sender_unread = :uid 
            WHERE sender_unread = 0 AND id = :dialog_id";

            DB::run($sql, ['uid' => $uid, 'dialog_id' => $dialog_id]);

            if ($receipt) {

                $sql = "UPDATE messages_dialog SET sender_unread = :uid 
                            WHERE sender_unread = 0 AND id = :dialog_id";

                DB::run($sql, ['uid' => $uid, 'dialog_id' => $dialog_id]);
            }
        }
        // Получатель
        if ($messages_dialog['recipient_uid'] == $uid) {

            $sql = "UPDATE messages_dialog SET recipient_unread = :uid 
                        WHERE recipient_unread = 0 AND id = :dialog_id";

            DB::run($sql, ['uid' => $uid, 'dialog_id' => $dialog_id]);
        }

        // uid получателя и индификатор события
        NotificationsModel::updateMessagesUnread($uid, $dialog_id);

        return true;
    }

    // Последнее сообщение в диалоге
    public static function getMessageOne($dialog_id)
    {
        $sql = "SELECT  
                    id,
                    uid,
                    dialog_id,
                    message,
                    add_time,
                    sender_remove,
                    recipient_remove,
                    receipt
                        FROM messages 
                        WHERE dialog_id = :dialog_id
                        ORDER BY id DESC";

        return DB::run($sql, ['dialog_id' => $dialog_id])->fetch(PDO::FETCH_ASSOC);
    }

    public static function getMessageByDialogId($dialog_id)
    {
        $sql = "SELECT  
                    id,
                    uid,
                    dialog_id,
                    message,
                    add_time,
                    sender_remove,
                    recipient_remove,
                    receipt
                        FROM messages 
                        WHERE dialog_id = :dialog_id
                        ORDER BY id DESC";

        $query = DB::run($sql, ['dialog_id' => $dialog_id])->fetchAll(PDO::FETCH_ASSOC);

        if ($query) {
            foreach ($query as $key => $val) {
                $message[$val['id']] = $val;
            }
        }

        return $message;
    }

    // Количество сообщений (не задействованно)
    public static function getMessagesTotal($user_id)
    {
        $sql = "SELECT id FROM messages_dialog";

        return DB::run($sql)->rowCount();
    }

    public static function getLastMessages($dialog_ids)
    {
        if (!is_array($dialog_ids)) {
            return false;
        }

        foreach ($dialog_ids as $dialog_id) {
            $last_message =  self::getMessageOne($dialog_id);
        }

        return $last_message;
    }

    // Записываем личное сообщение
    public static function SendMessage($sender_uid, $recipient_uid, $message)
    {
        if (!$sender_uid or !$recipient_uid or !$message) {
            return false;
        }

        if (!$messages_dialog = self::getDialogByUser($sender_uid, $recipient_uid)) {

            // Записываем диалог (если его нет)
            $params = [
                'sender_uid'        => $sender_uid,
                'sender_unread'     => 1,
                'recipient_uid'     => $recipient_uid,
                'recipient_unread'  => 0,
                'sender_count'      => 0,
                'recipient_count'   => 0,
            ];

            $sql = "INSERT INTO messages_dialog(sender_uid, 
                                        sender_unread, 
                                        recipient_uid, 
                                        recipient_unread, 
                                        sender_count, 
                                        recipient_count) 
                                        
                                VALUES(:sender_uid, 
                                        :sender_unread, 
                                        :recipient_uid, 
                                        :recipient_unread, 
                                        :sender_count, 
                                        :recipient_count)";

            DB::run($sql, $params);

            // Вернем id диалога для записи в `dialog_id` ниже          
            $sql_last_id =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch(PDO::FETCH_ASSOC);

            $messages_dialog_id = $sql_last_id['last_id'];
        } else {

            $messages_dialog_id = $messages_dialog['id'];
        }

        $params_send = [
            'dialog_id' => $messages_dialog_id,
            'message'   => $message,
            'uid'       => $sender_uid,
        ];

        $sql = "INSERT INTO messages(dialog_id, message, uid)
                            VALUES(:dialog_id, :message, :uid)";

        DB::run($sql, $params_send);

        self::updateDialogCount($messages_dialog_id, $sender_uid);

        /* Отправим на E-mail...
		   UserModel::updateInboxUnread($recipient_uid);
		if ($user_info = UserModel::getUser($sender_uid, 'id'))
		{
			...
		} */

        $type = 1; // Личные сообщения        
        NotificationsModel::send($sender_uid, $recipient_uid, $type, $messages_dialog_id, '', 1);

        return $message_id;
    }

    // Изменение количество сообщений
    public static function updateDialogCount($dialog_id, $uid)
    {
        if (!$inbox_dialog = self::getDialogById($dialog_id)) {
            return false;
        }

        $sql = "SELECT  
                    id,
                    sender_count,
                    recipient_count
                        FROM messages_dialog
                        WHERE id = :dialog_id";

        $query = DB::run($sql, ['dialog_id' => $dialog_id])->fetch(PDO::FETCH_ASSOC);

        $sender_count    = $query['sender_count'] + 1;
        $recipient_count = $query['recipient_count'] + 1;
        $update_time     = date("Y-m-d H:i:s");

        $params = [
            'sender_count'      => $sender_count,
            'update_time'       => $update_time,
            'recipient_count'   => $recipient_count,
            'dialog_id'         => $dialog_id,
        ];

        $sql_dialog = "UPDATE messages_dialog SET 
                            sender_count = :sender_count,  update_time = :update_time, recipient_count = :recipient_count WHERE id = :dialog_id";

        DB::run($sql_dialog, $params);

        if ($inbox_dialog['sender_uid'] == $uid) {

            $recipient_unread = 0;

            $sql = "UPDATE messages_dialog SET recipient_unread = :recipient WHERE id = :id";

            return  DB::run($sql, ['recipient' => $recipient_unread, 'id' => $dialog_id]);
        } else {
            $sender_unread = 0;

            $sql = "UPDATE messages_dialog SET sender_unread = :sender WHERE id = :id";

            return  DB::run($sql, ['sender' => $sender_unread, 'id' => $dialog_id]);
        }
    }

    // Информация о участнике
    public static function getDialogByUser($sender_uid, $recipient_uid)
    {
        $sql = "SELECT  
                    id,
                    sender_uid,
                    sender_unread,
                    recipient_uid,
                    recipient_unread,
                    add_time,
                    update_time,
                    sender_count,
                    recipient_count
                        FROM messages_dialog 
                        WHERE sender_uid = :sender_uid AND recipient_uid = :recipient_uid
                        OR recipient_uid = :sender_uid AND sender_uid = :recipient_uid";

        return DB::run($sql, ['sender_uid' => $sender_uid, 'recipient_uid' => $recipient_uid])->fetch(PDO::FETCH_ASSOC);
    }
}
