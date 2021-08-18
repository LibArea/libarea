<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;

class ReportModel extends MainModel
{
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
