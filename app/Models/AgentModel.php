<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class AgentModel extends MainModel
{
    public static function getAll($limit)
    {
        $sql = "SELECT log_id,
                        log_date, 
                        log_user_id, 
                        log_user_browser, 
                        log_user_os, 
                        log_user_ip
                            FROM users_agent_logs ORDER BY log_id DESC LIMIT :limit";

        return  DB::run($sql, ['limit' => $limit])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUser($user_id)
    {
        $sql = "SELECT log_id,
                        log_date, 
                        log_user_id, 
                        log_user_browser, 
                        log_user_os, 
                        log_user_ip
                            FROM users_agent_logs WHERE log_user_id = :user_id";

        return  DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    public static function setLog($data)
    {
        $params = [
            'log_date'          => $data['log_date'],
            'log_user_id'       => $data['log_user_id'],
            'log_user_browser'  => $data['log_user_browser'],
            'log_user_os'       => $data['log_user_os'],
            'log_user_ip'       => $data['log_user_ip'],
        ];

        $sql = "INSERT INTO users_agent_logs(log_date, 
                                log_user_id, 
                                log_user_browser, 
                                log_user_os, 
                                log_user_ip) 
                                
                            VALUES(:log_date, 
                                :log_user_id, 
                                :log_user_browser, 
                                :log_user_os, 
                                :log_user_ip)";

        return DB::run($sql, $params);
    }

    // Последние визиты
    public static function getLastVisit()
    {
        $sql = "SELECT 
                    user_id,
                    user_login,
                    user_name,
                    user_email,
                    user_avatar,
                    user_created_at,
                    user_reg_ip,
                    user_invitation_id,
                    user_trust_level,
                    latest_date,
                    os
                        FROM users
                        JOIN 
                        ( SELECT 
                            MAX(log_user_os) as os,
                            MAX(log_date) as latest_date,
                            log_user_id
                            FROM users_agent_logs 
                            GROUP BY log_user_id
                        ) as latest_date
                        ON latest_date.log_user_id = user_id
                        ORDER BY latest_date DESC LIMIT 10 ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
