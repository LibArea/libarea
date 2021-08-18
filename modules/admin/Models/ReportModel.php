<?php

namespace Modules\Admin\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class ReportModel extends MainModel
{
    public static function get($page, $limit)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    report_id,
                    report_user_id, 
                    report_type, 
                    report_content_id, 
                    report_reason, 
                    report_url, 
                    report_date, 
                    report_status 
                        FROM reports ORDER BY report_id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCount()
    {
        return DB::run("SELECT report_id FROM reports")->rowCount();
    }

    // Изменим отмеку о занесении в бан-лист
    public static function setStatus($report_id)
    {
        $sql = "UPDATE reports 
                    SET report_status = 1
                        WHERE report_id = :report_id";

        return  DB::run($sql, ['report_id' => $report_id]);
    }
}
