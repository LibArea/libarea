<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use App\Models\NotificationsModel;
use DB;
use PDO;

class MessagesModel extends MainModel
{
    // Все диалоги
    public static function getMessages($user_id)
    {
        $sql = "SELECT  
                    dialog_id,
                    dialog_sender_id,
                    dialog_sender_unread,
                    dialog_recipient_id,
                    dialog_recipient_unread,
                    dialog_add_time,
                    dialog_update_time,
                    dialog_sender_count,
                    dialog_recipient_count
                        FROM messages_dialog 
                          WHERE dialog_sender_id = :user_id OR dialog_recipient_id = :user_id
                            ORDER BY dialog_update_time DESC";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function lastBranches($user_id)
    {
        $sql = "SELECT  
                    dialog_id,
                    dialog_recipient_unread,
                    dialog_add_time,
                    dialog_sender_count,
                    dialog_recipient_count,
                    user_id,
                    user_login,
                    user_avatar
                        FROM messages_dialog 
                        LEFT JOIN users ON dialog_sender_id = user_id OR dialog_recipient_id = user_id
                          WHERE dialog_sender_id = :user_id OR dialog_recipient_id = :user_id
                            ORDER BY dialog_update_time DESC LIMIT 15";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }   

    // Получаем диалог по id
    public static function getDialogById($dialog_id)
    {
        $sql = "SELECT  
                    dialog_id,
                    dialog_sender_id,
                    dialog_sender_unread,
                    dialog_recipient_id,
                    dialog_recipient_unread,
                    dialog_add_time,
                    dialog_update_time,
                    dialog_sender_count,
                    dialog_recipient_count
                        FROM messages_dialog 
                        WHERE dialog_id = :dialog_id";

        return DB::run($sql, ['dialog_id' => $dialog_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Пересчет просмотрено или нет
    public static function setMessageRead($dialog_id, $user_id, $receipt = true)
    {
        if (!$messages_dialog = self::getDialogById($dialog_id)) {
            return false;
        }

        // Отправитель
        if ($messages_dialog['dialog_sender_id'] == $user_id) {

            $sql = "UPDATE messages_dialog SET dialog_sender_unread = :user_id 
            WHERE dialog_sender_unread = 0 AND dialog_id = :dialog_id";

            DB::run($sql, ['user_id' => $user_id, 'dialog_id' => $dialog_id]);

            if ($receipt) {

                $sql = "UPDATE messages_dialog SET dialog_sender_unread = :user_id 
                            WHERE dialog_sender_unread = 0 AND dialog_id = :dialog_id";

                DB::run($sql, ['user_id' => $user_id, 'dialog_id' => $dialog_id]);
            }
        }
        // Получатель
        if ($messages_dialog['dialog_recipient_id'] == $user_id) {

            $sql = "UPDATE messages_dialog SET dialog_recipient_unread = :user_id 
                        WHERE dialog_recipient_unread = 0 AND dialog_id = :dialog_id";

            DB::run($sql, ['user_id' => $user_id, 'dialog_id' => $dialog_id]);
        }

        // user_id получателя и индификатор события
        NotificationsModel::updateMessagesUnread($user_id, $dialog_id);

        return true;
    }

    // Последнее сообщение в диалоге
    public static function getMessageOne($dialog_id)
    {
        $sql = "SELECT  
                    message_id,
                    message_sender_id,
                    message_dialog_id,
                    message_content,
                    message_add_time,
                    message_sender_remove,
                    message_recipient_remove,
                    message_receipt
                        FROM messages 
                        WHERE message_dialog_id = :dialog_id
                        ORDER BY message_id DESC";

        return DB::run($sql, ['dialog_id' => $dialog_id])->fetch(PDO::FETCH_ASSOC);
    }

    public static function getMessageByDialogId($dialog_id)
    {
        $sql = "SELECT  
                    message_id,
                    message_sender_id,
                    message_dialog_id,
                    message_content,
                    message_add_time,
                    message_sender_remove,
                    message_recipient_remove,
                    message_receipt
                        FROM messages 
                        WHERE message_dialog_id = :dialog_id
                        ORDER BY message_id DESC";

        $query = DB::run($sql, ['dialog_id' => $dialog_id])->fetchAll(PDO::FETCH_ASSOC);

        if ($query) {
            foreach ($query as $key => $val) {
                $message[$val['message_id']] = $val;
            }
        }

        return $message;
    }

    // Количество сообщений (не задействованно)
    public static function getMessagesTotal($user_id)
    {
        $sql = "SELECT dialog_id FROM messages_dialog";

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
    public static function sendMessage($dialog_sender_id, $dialog_recipient_id, $message_content)
    {
        if (!$dialog_sender_id or !$dialog_recipient_id or !$message_content) {
            return false;
        }

        if (!$messages_dialog = self::getDialogByUser($dialog_sender_id, $dialog_recipient_id)) {

            // Записываем диалог (если его нет)
            $params = [
                'dialog_sender_id'        => $dialog_sender_id,
                'dialog_sender_unread'     => 1,
                'dialog_recipient_id'     => $dialog_recipient_id,
                'dialog_recipient_unread'  => 0,
                'dialog_sender_count'      => 0,
                'dialog_recipient_count'   => 0,
            ];

            $sql = "INSERT INTO messages_dialog(dialog_sender_id, 
                                        dialog_sender_unread, 
                                        dialog_recipient_id, 
                                        dialog_recipient_unread, 
                                        dialog_sender_count, 
                                        dialog_recipient_count) 
                                        
                                VALUES(:dialog_sender_id, 
                                        :dialog_sender_unread, 
                                        :dialog_recipient_id, 
                                        :dialog_recipient_unread, 
                                        :dialog_sender_count, 
                                        :dialog_recipient_count)";

            DB::run($sql, $params);

            // Вернем id диалога для записи в `dialog_id` ниже          
            $sql_last_id =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch(PDO::FETCH_ASSOC);

            $messages_dialog_id = $sql_last_id['last_id'];
        } else {

            $messages_dialog_id = $messages_dialog['dialog_id'];
        }

        $params_send = [
            'message_dialog_id' => $messages_dialog_id,
            'message_content'   => $message_content,
            'message_sender_id' => $dialog_sender_id,
        ];

        $sql = "INSERT INTO messages(message_dialog_id, message_content, message_sender_id)
                            VALUES(:message_dialog_id, :message_content, :message_sender_id)";

        DB::run($sql, $params_send);

        self::updateDialogCount($messages_dialog_id, $dialog_sender_id);

        /* Отправим на E-mail...
		   UserModel::updateInboxUnread($recipient_id);
		if ($user_info = UserModel::getUser($sender_id, 'id'))
		{
			...
		} */

        $type = 1; // Личные сообщения        
        NotificationsModel::send($dialog_sender_id, $dialog_recipient_id, $type, $messages_dialog_id, '', 1);

        return;
    }

    // Изменение количество сообщений
    public static function updateDialogCount($dialog_id, $user_id)
    {
        if (!$inbox_dialog = self::getDialogById($dialog_id)) {
            return false;
        }

        $sql = "SELECT  
                    dialog_id,
                    dialog_sender_count,
                    dialog_recipient_count
                        FROM messages_dialog
                        WHERE dialog_id = :dialog_id";

        $query = DB::run($sql, ['dialog_id' => $dialog_id])->fetch(PDO::FETCH_ASSOC);

        $dialog_sender_count    = $query['dialog_sender_count'] + 1;
        $dialog_recipient_count = $query['dialog_recipient_count'] + 1;
        $dialog_update_time     = date("Y-m-d H:i:s");

        $params = [
            'dialog_sender_count'       => $dialog_sender_count,
            'dialog_update_time'        => $dialog_update_time,
            'dialog_recipient_count'    => $dialog_recipient_count,
            'dialog_id'                 => $dialog_id,
        ];

        $sql_dialog = "UPDATE messages_dialog SET 
                            dialog_sender_count     = :dialog_sender_count,  
                            dialog_update_time      = :dialog_update_time, 
                            dialog_recipient_count  = :dialog_recipient_count 
                                WHERE dialog_id = :dialog_id";

        DB::run($sql_dialog, $params);

        if ($inbox_dialog['dialog_sender_id'] == $user_id) {

            $recipient_unread = 0;

            $sql = "UPDATE messages_dialog SET dialog_recipient_unread = :recipient 
                                WHERE dialog_id = :dialog_id";

            return  DB::run($sql, ['recipient' => $recipient_unread, 'dialog_id' => $dialog_id]);
        } else {
            $sender_unread = 0;

            $sql = "UPDATE messages_dialog SET dialog_sender_unread = :sender 
                                WHERE dialog_id = :dialog_id";

            return  DB::run($sql, ['sender' => $sender_unread, 'dialog_id' => $dialog_id]);
        }
    }

    // Информация о участнике
    public static function getDialogByUser($dialog_sender_id, $dialog_recipient_id)
    {
        $sql = "SELECT  
                    dialog_id,
                    dialog_sender_id,
                    dialog_sender_unread,
                    dialog_recipient_id,
                    dialog_recipient_unread,
                    dialog_add_time,
                    dialog_update_time,
                    dialog_sender_count,
                    dialog_recipient_count
                        FROM messages_dialog 
                        WHERE dialog_sender_id = :dialog_sender_id AND 
                        dialog_recipient_id = :dialog_recipient_id
                        OR dialog_recipient_id = :dialog_sender_id AND 
                        dialog_sender_id = :dialog_recipient_id";

        return DB::run($sql, ['dialog_sender_id' => $dialog_sender_id, 'dialog_recipient_id' => $dialog_recipient_id])->fetch(PDO::FETCH_ASSOC);
    }
}
