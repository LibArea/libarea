<?php

namespace App\Models\Admin;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class UserModel extends MainModel
{
    // Страница участников
    public static function getUsers($page, $limit, $sheet)
    {
        $string = "ORDER BY user_id DESC LIMIT";
        if ($sheet == 'users.ban') {
            $string = "WHERE user_ban_list > 0 ORDER BY user_id DESC LIMIT";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT  
                    user_id,
                    user_login,
                    user_email,
                    user_name,
                    user_avatar,
                    user_created_at,
                    user_whisper,
                    user_updated_at,
                    user_whisper,
                    user_trust_level,
                    user_activated,
                    user_invitation_id,
                    user_limiting_mode,
                    user_reg_ip,
                    user_ban_list,
                    user_is_deleted,
                    banlist_user_id,
                    banlist_status,
                    banlist_int_num
                        FROM users 
                        LEFT JOIN users_banlist ON banlist_user_id = user_id
                        $string
                        $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество
    public static function getUsersCount($sheet)
    {
        $string = "";
        if ($sheet == 'users.ban') {
            $string = "WHERE user_ban_list > 0";
        }

        $sql = "SELECT 
                    user_id,
                    user_is_deleted
                        FROM users $string";

        return  DB::run($sql)->rowCount();
    }

    // Информация по участнику (id, slug)
    public static function getUser($params, $name)
    {
        $sort = "user_id = :params";
        if ($name == 'slug') {
            $sort = "user_login = :params";
        }

        $sql = "SELECT 
                    user_id,
                    user_login,
                    user_name,
                    user_whisper,
                    user_activated,
                    user_limiting_mode,
                    user_reg_ip,
                    user_email,
                    user_avatar,
                    user_trust_level,
                    user_cover_art,
                    user_color,
                    user_template,
                    user_lang,
                    user_invitation_available,
                    user_about,
                    user_website,
                    user_location,
                    user_public_email,
                    user_skype,
                    user_twitter,
                    user_telegram,
                    user_vk,
                    user_created_at,
                    user_updated_at,
                    user_my_post,
                    user_ban_list,
                    user_hits_count,
                    user_up_count,
                    user_is_deleted 
                        FROM users WHERE $sort";

        $result = DB::run($sql, ['params' => $params]);

        return $result->fetch(PDO::FETCH_ASSOC);
    }

    // Количество контента участника
    public static function contentCount($user_id)
    {
        $sql = "SELECT 
                    (SELECT COUNT(post_id) 
                        FROM posts 
                        WHERE post_user_id = :user_id and post_draft = 0 and post_is_deleted = 0) 
                            AS count_posts,
                  
                    (SELECT COUNT(answer_id) 
                        FROM answers 
                        WHERE answer_user_id = :user_id and answer_is_deleted = 0) 
                            AS count_answers,
                  
                    (SELECT COUNT(comment_id) 
                        FROM comments 
                        WHERE comment_user_id = :user_id and comment_is_deleted = 0) 
                            AS count_comments";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

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
                    user_trust_level,
                    user_ban_list
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
                    user_ban_list,
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
}
