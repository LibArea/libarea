<?php

namespace App\Models;

use DB;

class AuditModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Add an entry to the audit table (audit or flag)
    // Добавим запись в таблицу аудита (аудит или флаг)
    public static function add($params)
    {
        $sql = "INSERT INTO audits(type_content, type_belonging, user_id, content_id, read_flag) 
                    VALUES(:type_content, :type_belonging, :user_id, :content_id, 0)";

        return DB::run($sql, $params);
    }

    // Total contribution of the participant
    // Общий вклад участника
    public static function ceneralContributionCount($uid)
    {
        $sql = "SELECT
                (SELECT COUNT(*) FROM 
                    posts WHERE post_user_id = $uid and post_is_deleted = 0) AS t1Count,
                (SELECT COUNT(*) FROM 
                    answers WHERE answer_user_id = $uid and answer_is_deleted = 0) AS t2Count,
                (SELECT COUNT(*) FROM 
                    comments WHERE comment_user_id = $uid and comment_is_deleted = 0) AS t3Count";

        $lists  = DB::run($sql)->fetch();

        return $lists['t1Count'] + $lists['t2Count'] + $lists['t3Count'];
    }
    
    // Let's limit How many complaints are filed today (for frequency limitation)
    // Сколько жалоб подано сегодня (для ограничение частоты)
    public static function getSpeedReport($uid)
    {
        $sql = "SELECT id FROM audits
                    WHERE user_id = :uid AND type_belonging = 'report'
                        AND add_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['uid' => $uid])->rowCount();
    }
}
