<?php

namespace Modules\Admin\App\Models;

use DB;

class LogModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Get the logs
    // Получим логи  
    public static function getLogs($page, $limit)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    id,
                    user_id,
                    user_login,
                    id_content,
                    type_content,
                    action_name,
                    url_content,
                    add_date
                        FROM users_action_logs ORDER BY id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll();
    }

    // Get gthe number of records 
    // Получим количество записей  
    public static function getLogsCount()
    {
        return DB::run("SELECT id FROM users_action_logs")->rowCount();
    }

    // Страница аудита
    public static function getAuditsAll($page, $limit, $sheet, $type)
    {

        $sort   = $sheet == 'audits.ban' ? 'AND a.read_flag = 1' : 'AND a.read_flag = 0';
        $type   = $type == 'audits' ? 'audit' : 'report';
        $sort   = $type == 'report' ? '' : $sort;

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    a.id as audit_id,
                    a.type_content,
                    a.type_belonging,
                    a.add_date,
                    a.user_id,
                    a.content_id,
                    a.read_flag,
                    u.id, u.login, u.avatar, u.limiting_mode
                        FROM audits a
                        LEFT JOIN users u ON u.id = a.user_id
                        WHERE a.type_belonging = :type $sort ORDER BY a.id DESC LIMIT $start, $limit";

        return DB::run($sql, ['type' => $type])->fetchAll();
    }

    public static function getAuditsAllCount($sheet, $type)
    {
        $sort = "read_flag = 0";
        if ($sheet == 'audits.ban') {
            $sort = "read_flag = 1";
        }

        $sql = "SELECT id, read_flag FROM audits WHERE $sort";

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
        $sql = "UPDATE audits SET read_flag = 1 WHERE content_id = :id";

        return  DB::run($sql, ['id' => $id]);
    }

    // Get user id and remove mute mode 
    // Получаем id пользователя и убираем немой режим
    public static function auditAuthor($id)
    {
        $sql = "SELECT user_id FROM audits WHERE content_id = :id";

        $user = DB::run($sql, ['id' => $id])->fetch();

        $usql = "UPDATE users SET limiting_mode = 0 WHERE id = :uid";

        return  DB::run($usql, ['uid' => $user['user_id']]);
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

    // Частота размещения флагов
    public static function getSpeedReport($uid)
    {
        $sql = "SELECT id FROM audits
                    WHERE user_id = :uid AND type_belonging = 'report'
                        AND add_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['uid' => $uid])->rowCount();
    }

    // Флаг просмотрен
    public static function setSaw($id)
    {
        $sql = "UPDATE audits SET read_flag = 1 WHERE id = :id";

        return  DB::run($sql, ['id' => $id]);
    }
}
