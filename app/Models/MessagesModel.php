<?php

namespace App\Models;

use DB;

class MessagesModel extends \Hleb\Scheme\App\Models\MainModel
{
    // All dialogs
    public static function getMessages($uid)
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
                          WHERE dialog_sender_id = $uid OR dialog_recipient_id = :uid
                            ORDER BY dialog_update_time DESC";

        return DB::run($sql, ['uid' => $uid])->fetchAll();
    }

    public static function lastBranches($uid)
    {
        $sql = "SELECT  
                    dialog_id,
                    dialog_recipient_unread,
                    dialog_add_time,
                    dialog_sender_count,
                    dialog_recipient_count,
                    id,
                    login,
                    avatar
                        FROM messages_dialog 
                        LEFT JOIN users ON dialog_sender_id = id OR dialog_recipient_id = id
                          WHERE dialog_sender_id = $uid OR dialog_recipient_id = $uid
                            ORDER BY dialog_update_time DESC LIMIT 15";

        return DB::run($sql)->fetchAll();
    }

    // We get a dialog by id
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

        return DB::run($sql, ['dialog_id' => $dialog_id])->fetch();
    }

    // Recalculation viewed or not
    public static function setMessageRead($dialog_id, $uid, $receipt = true)
    {
        if (!$messages_dialog = self::getDialogById($dialog_id)) {
            return false;
        }

        // Отправитель
        if ($messages_dialog['dialog_sender_id'] == $uid) {

            $sql = "UPDATE messages_dialog SET dialog_sender_unread = :uid 
            WHERE dialog_sender_unread = 0 AND dialog_id = :dialog_id";

            DB::run($sql, ['uid' => $uid, 'dialog_id' => $dialog_id]);

            if ($receipt) {

                $sql = "UPDATE messages_dialog SET dialog_sender_unread = :uid 
                            WHERE dialog_sender_unread = 0 AND dialog_id = :dialog_id";

                DB::run($sql, ['uid' => $uid, 'dialog_id' => $dialog_id]);
            }
        }
        // Получатель
        if ($messages_dialog['dialog_recipient_id'] == $uid) {

            $sql = "UPDATE messages_dialog SET dialog_recipient_unread = :uid 
                        WHERE dialog_recipient_unread = 0 AND dialog_id = :dialog_id";

            DB::run($sql, ['uid' => $uid, 'dialog_id' => $dialog_id]);
        }

        return $dialog_id;
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

        return DB::run($sql, ['dialog_id' => $dialog_id])->fetch();
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

        $query = DB::run($sql, ['dialog_id' => $dialog_id])->fetchAll();

        if ($query) {
            foreach ($query as $key => $val) {
                $message[$val['message_id']] = $val;
            }
        }

        return $message;
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

        $messages_dialog = self::getDialogByUser($dialog_sender_id, $dialog_recipient_id);

        $messages_dialog_id = $messages_dialog['dialog_id'];
        if (!$messages_dialog) {

            // Create a dialog (if there is none)
            $messages_dialog_id = self::createDialog(
                [
                    'dialog_sender_id'          => $dialog_sender_id,
                    'dialog_sender_unread'      => 1,
                    'dialog_recipient_id'       => $dialog_recipient_id,
                    'dialog_recipient_unread'   => 0,
                    'dialog_sender_count'       => 0,
                    'dialog_recipient_count'    => 0,
                ]
            );
        }

        self::createMessage(
            [
                'message_dialog_id' => $messages_dialog_id,
                'message_content'   => $message_content,
                'message_sender_id' => $dialog_sender_id,
            ]
        );

        self::updateDialogCount($messages_dialog_id, $dialog_sender_id);

        return $messages_dialog_id;
    }

    // Creating a dialog
    public static function createDialog($params)
    {
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

        $sql_last_id =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch();

        return $sql_last_id['last_id'];
    }


    // Creating a message
    public static function createMessage($params)
    {
        $sql = "INSERT INTO messages(message_dialog_id, message_content, message_sender_id)
                            VALUES(:message_dialog_id, :message_content, :message_sender_id)";

        DB::run($sql, $params);
    }


    // Changing the number of messages
    public static function updateDialogCount($dialog_id, $uid)
    {
        if (!$inbox_dialog = self::getDialogById($dialog_id)) {
            return false;
        }

        $sql = "SELECT  
                    dialog_id,
                    dialog_sender_count,
                    dialog_recipient_count,
                    dialog_recipient_unread
                        FROM messages_dialog
                            WHERE dialog_id = :dialog_id";

        $query = DB::run($sql, ['dialog_id' => $dialog_id])->fetch();

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

        if ($inbox_dialog['dialog_sender_id'] == $uid) {

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

    // User Information
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
                            WHERE dialog_sender_id  = $dialog_sender_id AND 
                                dialog_recipient_id     = $dialog_recipient_id
                                OR dialog_recipient_id  = $dialog_sender_id AND 
                                dialog_sender_id        = $dialog_recipient_id";

        return DB::run($sql)->fetch();
    }
}
