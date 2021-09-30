<?php

namespace App\Models\Admin;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class AuditModel extends MainModel
{
    // Страница аудита
    public static function getAuditsAll($page, $limit, $sheet)
    {
        $sort = "audit_read_flag = 0";
        if ($sheet == 'approved') {
            $sort = "audit_read_flag = 1";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    audit_id,
                    audit_type,
                    audit_date,
                    audit_user_id,
                    audit_content_id,
                    audit_read_flag
                        FROM audits WHERE $sort ORDER BY audit_id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAuditsAllCount($sheet)
    {
        $sort = "audit_read_flag = 0";
        if ($sheet == 'approved') {
            $sort = "audit_read_flag = 1";
        }

        $sql = "SELECT audit_id, audit_read_flag FROM audits WHERE $sort";

        return DB::run($sql)->rowCount();
    }

    // Восстановление
    public static function recoveryAudit($id, $type)
    {
        $sql = "UPDATE " . $type . "s SET " . $type . "_published = 1 WHERE " . $type . "_id = :id";

        DB::run($sql, ['id' => $id]);

        self::auditReadFlag($id);

        return true;
    }


    public static function auditReadFlag($id)
    {
        $sql = "UPDATE audits
                    SET audit_read_flag = 1 
                        WHERE audit_content_id = :id";

        return  DB::run($sql, ['id' => $id]);
    }
}
