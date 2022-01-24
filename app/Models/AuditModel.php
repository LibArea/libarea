<?php

namespace App\Models;

use DB;

class AuditModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Страница аудита
    public static function getAuditsAll($page, $limit, $sheet)
    {
        $sort = "audit_read_flag = 0";
        if ($sheet == 'audits.ban') {
            $sort = "audit_read_flag = 1";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    audit_id,
                    audit_type,
                    audit_date,
                    audit_user_id,
                    audit_content_id,
                    audit_read_flag,
                    id, login, avatar, limiting_mode
                        FROM audits 
                        LEFT JOIN users ON id = audit_user_id
                        WHERE $sort ORDER BY audit_id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll();
    }

    public static function getAuditsAllCount($sheet)
    {
        $sort = "audit_read_flag = 0";
        if ($sheet == 'audits.ban') {
            $sort = "audit_read_flag = 1";
        }

        $sql = "SELECT audit_id, audit_read_flag FROM audits WHERE $sort";

        return DB::run($sql)->rowCount();
    }

    // Let's approve the audit 
    // Одобрим аудит
    public static function recoveryAudit($id, $type)
    {
        $sql = "UPDATE " . $type . "s SET " . $type . "_published = 1 WHERE " . $type . "_id = :id";

        DB::run($sql, ['id' => $id]);

        self::auditAuthor($id);

        self::auditReadFlag($id);

        return true;
    }

    // change the flag to approved 
    // меняем флаг на одобрен
    public static function auditReadFlag($id)
    {
        $sql = "UPDATE audits
                    SET audit_read_flag = 1 
                        WHERE audit_content_id = :id";

        return  DB::run($sql, ['id' => $id]);
    }

    // Get user id and remove mute mode 
    // Получаем id пользователя и убираем немой режим
    public static function auditAuthor($id)
    {
        $sql = "SELECT audit_user_id FROM audits WHERE audit_content_id = :id";

        $user = DB::run($sql, ['id' => $id])->fetch();

        $usql = "UPDATE users SET limiting_mode = 0 WHERE id = :uid";

        return  DB::run($usql, ['uid' => $user['audit_user_id']]);
    }

    public static function add($params)
    {
        $sql = "INSERT INTO audits(audit_type, audit_user_id, audit_content_id, audit_read_flag) 
                    VALUES(:audit_type, :audit_user_id, :audit_content_id, 0)";

        return DB::run($sql, $params);
    }

    // Total contribution of the participant
    // Общий вклад участника
    public static function ceneralContributionCount($uid)
    {
        $sql = "SELECT
                (SELECT COUNT(*) FROM 
                    posts WHERE post_user_id = :uid and post_is_deleted = 0) AS t1Count,
                (SELECT COUNT(*) FROM 
                    answers WHERE answer_user_id = :uid and answer_is_deleted = 0) AS t2Count,
                (SELECT COUNT(*) FROM 
                    comments WHERE comment_user_id = :uid and comment_is_deleted = 0) AS t3Count";

        $result = DB::run($sql, ['uid' => $uid]);
        $lists  = $result->fetch();

        return $lists['t1Count'] + $lists['t2Count'] + $lists['t3Count'];
    }
}
