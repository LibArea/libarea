<?php

namespace Modules\Admin\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class AgentModel extends MainModel
{
    // Количество дублей IP по полю user_reg_ip
    public static function duplicatesRegistrationCount($ip)
    {
        $sql = "SELECT 
                    user_id, 
                    user_reg_ip 
                        FROM users WHERE user_reg_ip = :ip";

        return DB::run($sql, ['ip' => $ip])->rowCount();
    }
    
    // По логам
    public static function lastVisitLogs($user_id)
    {
        $sql = "SELECT 
                    MAX(log_date) as latest_date,
                    MAX(log_user_ip) as latest_ip,
                    log_user_id
                    FROM users_agent_logs 
                    WHERE log_user_id = :user_id GROUP BY log_user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserRegsId($ip)
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
                    user_trust_level
                        FROM users WHERE user_reg_ip = :ip";
       return DB::run($sql, ['ip' => $ip])->fetchAll(PDO::FETCH_ASSOC);
    }

    // ip для логов
    public static function getUserLogsId($ip)
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
                    latest_date
                        FROM users
                        JOIN 
                        ( SELECT 
                            MAX(log_date) as latest_date,
                            log_user_id
                            FROM users_agent_logs 
                            WHERE log_user_ip = :ip GROUP BY log_user_id
                        ) as latest_date
                        ON latest_date.log_user_id = user_id";
        return DB::run($sql, ['ip' => $ip])->fetchAll(PDO::FETCH_ASSOC);
    }

}
