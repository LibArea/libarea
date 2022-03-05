<?php

namespace App\Models;

use DB;

class NotificationModel extends \Hleb\Scheme\App\Models\MainModel
{
    // $action_type
    // 1 - сообщение 
    // 2 - пост
    // 3 - ответ на пост
    // 4 - комментарий на ответ в посте
    // 5 - публикация в чат
    // 6 - понравился пост
    // 7 - понравился ответ на пост
    // 8 - понравился комментарий
    // 10 - обращение в постах (@login)
    // 11 - в ответах  к постам (@login)
    // 12 - в комментариях к постам (@login)
    // 20 - флаг система
    // 21 - аудит (бывший 15)
    // 30 - сайт добавлен (бывший 32)
    // 31 - сайт изменен и поменял статус (бывший 33)
    // 32 - ваш сайт утвержден (бывший 34)
    // 33 - был добавлен новый сайт в категорию на которую подписан
    // 34 - был добавлен новый комментарий на сайт
    // 35 - на ваш комментарий был дан ответ
    // 36 - обращение (@) к комментариях на сайт

    const TYPE_AMSWER_POST          = 3;  // answer to question (post)
    const TYPE_COMMENT_ANSWER       = 4;  // comment on answer

    const TYPE_ADDRESSED_POST       = 10; // addressed the participant in the post 
    const TYPE_ADDRESSED_ANSWER     = 11; // addressed to the participant in the answer
    const TYPE_ADDRESSED_COMMENT    = 12; // addressed the participant in the comment 

    const TYPE_REPORT               = 20; // complaints system 
    const TYPE_AUDIT                = 21; // system audit

    const TYPE_ADD_WEBSITE          = 30; // site added
    const TYPE_EDIT_WEBSITE         = 31; // changed and status changed
    const WEBSITE_APPROVED          = 32; // approved

    // Лист уведомлений
    public static function listNotification($uid)
    {
        $sql = "SELECT
                    n.id as notif_id,
                    n.sender_id,
                    n.recipient_id,
                    n.action_type as type,
                    n.url,
                    n.add_time as time,
                    n.read_flag as flag,
                    n.is_deleted,
                    u.id, 
                    u.login, 
                    u.avatar
                        FROM notifications n
                        JOIN users u ON u.id = n.sender_id
                        WHERE n.recipient_id = :uid
                        ORDER BY n.id DESC LIMIT 100";

        return DB::run($sql, ['uid' => $uid])->fetchAll();
    }

    // Уведомления
    public static function bell($uid)
    {
        $sql = "SELECT
                    recipient_id,
                    action_type,
                    read_flag
                        FROM notifications
                        WHERE recipient_id = :uid
                        AND read_flag = 0";

        return DB::run($sql, ['uid' => $uid])->fetch();
    }

    // Отправка
    public static function send($params)
    {
        $sql = "INSERT INTO notifications(sender_id, 
                                recipient_id, 
                                action_type, 
                                url) 
                       VALUES(:sender_id, 
                               :recipient_id, 
                               :action_type, 
                               :url)";

        return DB::run($sql, $params);
    }

    // Оповещение просмотрено
    public static function updateMessagesUnread($uid, $notif_id)
    {
        $sql = "UPDATE notifications SET read_flag = 1 WHERE recipient_id = :uid AND id = :notif_id";

        return DB::run($sql, ['uid' => $uid, 'notif_id' => $notif_id]);
    }

    public static function getNotification($id)
    {
        $sql = "SELECT
                    id,
                    sender_id,
                    recipient_id,
                    action_type,
                    url,
                    add_time,
                    read_flag,
                    is_deleted
                        FROM notifications
                            WHERE id = :id";

        return DB::run($sql, ['id' => $id])->fetch();
    }

    public static function setRemove($uid)
    {
        $sql = "UPDATE notifications SET read_flag = 1 WHERE recipient_id = :uid";

        return DB::run($sql, ['uid' => $uid]);
    }
}
