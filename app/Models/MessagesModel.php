<?php

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class MessagesModel extends Model
{
    /**
     * All dialogs
     * Все диалоги
     */
    public static function getMessages()
    {
        $user_id = self::container()->user()->id();
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
								WHERE dialog_sender_id = $user_id OR dialog_recipient_id = $user_id
									ORDER BY dialog_update_time DESC";

        return DB::run($sql)->fetchAll();
    }

    /**
     * Check if the dialog exists or not
     * Проверим, существует ли диалог или нет
     *
     * @param integer $user_id
     */
    public static function availability(int $user_id)
    {
        $id = self::container()->user()->id();
        $sql = "SELECT dialog_id FROM messages_dialog 
                    WHERE (dialog_sender_id = $id AND dialog_recipient_id = $user_id) 
                        OR (dialog_sender_id = $user_id AND dialog_recipient_id = $id)";

        return DB::run($sql)->fetch();
    }

    /**
     * We get a dialog by id
     * Получаем диалог по id
     *
     * @param integer $dialog_id
     */
    public static function getDialogById(int $dialog_id)
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
    public static function setMessageRead($dialog_id, $receipt = true)
    {
        $user_id = self::container()->user()->id();

        if (!$messages_dialog = self::getDialogById($dialog_id)) {
            return false;
        }

        // Отправитель
        if ($messages_dialog['dialog_sender_id'] == $user_id) {

            $sql = "UPDATE messages_dialog SET dialog_sender_unread = :user_id WHERE dialog_sender_unread = 0 AND dialog_id = :dialog_id";

            DB::run($sql, ['user_id' => $user_id, 'dialog_id' => $dialog_id]);

            if ($receipt) {

                $sql = "UPDATE messages_dialog SET dialog_sender_unread = :user_id 
                            WHERE dialog_sender_unread = 0 AND dialog_id = :dialog_id";

                DB::run($sql, ['user_id' => $user_id, 'dialog_id' => $dialog_id]);
            }
        }
        // Получатель
        if ($messages_dialog['dialog_recipient_id'] == $user_id) {

            $sql = "UPDATE messages_dialog SET dialog_recipient_unread = :user_id WHERE dialog_recipient_unread = 0 AND dialog_id = :dialog_id";

            DB::run($sql, ['user_id' => $user_id, 'dialog_id' => $dialog_id]);
        }

        return $dialog_id;
    }

    public static function getMessage(int $id)
    {
        return DB::run("SELECT message_content, message_sender_id, message_dialog_id FROM messages WHERE message_id = ?", [$id])->fetch();
    }

    /**
     * Last message in the conversation
     * Последнее сообщение в диалоге
     *
     * @param integer $dialog_id
     */
    public static function getMessageOne(int $dialog_id)
    {
        $sql = "SELECT  
                    message_id,
                    message_sender_id,
                    message_dialog_id,
                    message_content,
                    message_date,
                    message_sender_remove,
                    message_recipient_remove,
                    message_receipt
                        FROM messages 
                            WHERE message_dialog_id = :dialog_id
                                ORDER BY message_id DESC";

        return DB::run($sql, ['dialog_id' => $dialog_id])->fetch();
    }

    public static function getMessageByDialogId(int $dialog_id)
    {
        $sql = "SELECT  
                    message_id,
                    message_sender_id,
                    message_dialog_id,
                    message_content,
                    message_date,
					message_modified,
                    message_sender_remove,
                    message_recipient_remove,
                    message_receipt
                        FROM messages 
                            WHERE message_dialog_id = :dialog_id
                                ORDER BY message_id DESC";

        $query = DB::run($sql, ['dialog_id' => $dialog_id])->fetchAll();

        if ($query) {
            foreach ($query as $val) {
                $message[$val['message_id']] = $val;
            }
        }

        return $message;
    }

    /**
     * Recording a personal message
     * Записываем личное сообщение
     *
     * @param [type] $dialog_recipient_id
     * @param [type] $message_content
     */
    public static function sendMessage($dialog_recipient_id, $message_content)
    {
        $dialog_sender_id = self::container()->user()->id();

        if (!$dialog_sender_id or !$dialog_recipient_id or !$message_content) {
            return false;
        }

        $messages_dialog = self::getDialogByUser($dialog_sender_id, $dialog_recipient_id);

        $messages_dialog_id = $messages_dialog['dialog_id'] ?? null;
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

    /**
     * Creating a dialog
     * Создание диалога
     *
     * @param array $params
     */
    public static function createDialog(array $params)
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

    /**
     * Creating a message
     * Создание сообщения
     *
     * @param array $params
     * @return void
     */
    public static function createMessage(array $params)
    {
        $sql = "INSERT INTO messages(message_dialog_id, message_content, message_sender_id) VALUES(:message_dialog_id, :message_content, :message_sender_id)";

        DB::run($sql, $params);
    }

    /**
     * Changing the number of messages
     * Изменение количества сообщений
     *
     * @param integer $dialog_id
     * @param integer $user_id
     */
    public static function updateDialogCount(int $dialog_id, int $user_id)
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

        if ($inbox_dialog['dialog_sender_id'] == $user_id) {
            $sql = "UPDATE messages_dialog SET dialog_recipient_unread = :recipient WHERE dialog_id = :dialog_id";
            return  DB::run($sql, ['recipient' => 0, 'dialog_id' => $dialog_id]);
        }

        $sql = "UPDATE messages_dialog SET dialog_sender_unread = :sender WHERE dialog_id = :dialog_id";
        return  DB::run($sql, ['sender' => 0, 'dialog_id' => $dialog_id]);
    }

    /**
     * Receiving data from a dialogue
     * Получение данных по диалогу
     *
     * @param integer $dialog_sender_id
     * @param integer $dialog_recipient_id
     */
    public static function getDialogByUser(int $dialog_sender_id, int $dialog_recipient_id)
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
                            WHERE dialog_sender_id      = $dialog_sender_id AND 
                                dialog_recipient_id     = $dialog_recipient_id
                                OR dialog_recipient_id  = $dialog_sender_id AND 
                                dialog_sender_id        = $dialog_recipient_id";

        return DB::run($sql)->fetch();
    }

    /**
     * Editing
     * Редактирование
     *
     * @param integer $id
     * @param string $content
     * @return void
     */
    public static function edit(int $id, string $content)
    {
        DB::run("UPDATE messages SET message_content = :content, message_modified = :modified WHERE message_id = :id", ['content' => $content, 'modified' => date("Y-m-d H:i:s"), 'id' => $id]);
    }
}
