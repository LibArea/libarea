<?php

declare(strict_types=1);

namespace Modules\Admin\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class LogModel extends Model
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
                    action_type,
                    action_name,
                    url_content,
                    add_date
                        FROM users_action_logs ORDER BY id DESC LIMIT :start, :limit";

        return DB::run($sql, ['start' => $start, 'limit' => $limit])->fetchAll();
    }

    // Get gthe number of records 
    // Получим количество записей  
    public static function getLogsCount()
    {
        return DB::run("SELECT id FROM users_action_logs")->rowCount();
    }

    // Страница аудита
    public static function getAuditsAll($page, $limit, $type)
    { 
      switch ($type) {
            case 'report':
                $sort = '';
                break;
            case 'audit':
                $sort = 'AND a.read_flag = 1';
                break;
            default: // all
                $sort = 'AND a.read_flag = 0';
				$type = 'audit';
        } 

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    a.id as audit_id,
                    a.action_type,
                    a.type_belonging,
                    a.add_date,
                    a.user_id,
                    a.content_id,
                    a.read_flag,
                    u.id, u.login, u.avatar, u.limiting_mode
                        FROM audits a
                        LEFT JOIN users u ON u.id = a.user_id
                            WHERE a.type_belonging = :type $sort ORDER BY a.id DESC LIMIT :start, :limit";

        return DB::run($sql, ['type' => $type, 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getAuditsAllCount($sheet)
    {
        $sort = ($sheet == 'audit') ? "read_flag = 1" : "read_flag = 0";

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
