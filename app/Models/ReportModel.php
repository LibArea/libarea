<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use PDO;
use DB;

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

    // Записываем флаг
    public static function send($data)
    {
        $params = [
            'report_user_id'    => $data['report_user_id'],
            'report_type'       => $data['report_type'],
            'report_content_id' => $data['report_content_id'],
            'report_reason'     => $data['report_reason'],
            'report_url'        => $data['report_url'],
            'report_date'       => $data['report_date'],
            'report_status'     => $data['report_status'],
        ];

        $sql = "INSERT INTO reports(report_user_id, 
                                    report_type, 
                                    report_content_id, 
                                    report_reason, 
                                    report_url, 
                                    report_date, 
                                    report_status) 
                                    
                            VALUES(:report_user_id, 
                                    :report_type, 
                                    :report_content_id, 
                                    :report_reason, 
                                    :report_url, 
                                    :report_date,
                                    :report_status)";
        return DB::run($sql, $params);
    }

    // Частота размещения флагов
    public static function getSpeed($user_id)
    {
        $sql = "SELECT 
                    report_id, 
                    report_user_id, 
                    report_date
                        FROM reports 
                            WHERE report_user_id = :user_id
                            AND report_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['user_id' => $user_id])->rowCount();
    }
}
