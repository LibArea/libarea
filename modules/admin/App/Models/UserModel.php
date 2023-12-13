<?php

namespace Modules\Admin\App\Models;

use DB;

class UserModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Users Page
    // Страница участников
    public static function getUsers($page, $limit, $sheet)
    {
        $string = ($sheet == 'ban') ? "WHERE ban_list > 0 ORDER BY id DESC LIMIT" : "ORDER BY id DESC LIMIT";

        $start  = ($page - 1) * $limit;
        $sql = "SELECT  
                    id,
                    login,
                    email,
                    name,
                    avatar,
                    created_at,
                    updated_at,
                    whisper,
                    scroll,
                    trust_level,
                    activated,
                    invitation_id,
                    limiting_mode,
                    reg_ip,
                    ban_list,
                    is_deleted,
                    banlist_user_id,
                    banlist_status,
                    banlist_int_num
                        FROM users 
                        LEFT JOIN users_banlist ON banlist_user_id = id
                        $string
                        :start, :limit";

        return DB::run($sql, ['start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getUsersCount($sheet)
    {
        $string = ($sheet == 'ban') ? 'WHERE ban_list > 0' : '';

        $sql = "SELECT id, is_deleted FROM users $string";

        return  DB::run($sql)->rowCount();
    }

    // Member information (id, slug)
    public static function getUser($params, $name)
    {
        $sort = ($name == 'slug') ? "login = :params" : "id = :params";

        $sql = "SELECT 
                    id,
                    login,
                    name,
                    whisper,
                    activated,
                    limiting_mode,
                    reg_ip,
                    email,
                    avatar,
                    trust_level,
                    cover_art,
                    scroll,
                    color,
                    template,
                    lang,
                    invitation_available,
                    about,
                    website,
                    location,
                    public_email,
                    github,
                    skype,
                    telegram,
                    vk,
                    created_at,
                    updated_at,
                    my_post,
                    ban_list,
                    hits_count,
                    up_count,
                    is_deleted 
                        FROM users WHERE $sort";

        $result = DB::run($sql, ['params' => $params]);

        return $result->fetch();
    }

    // Number of IP duplicates by `user_reg_ip` field
    public static function duplicatesRegistrationCount($ip)
    {
        $sql = "SELECT id, reg_ip FROM users WHERE reg_ip = :ip";

        return DB::run($sql, ['ip' => $ip])->rowCount();
    }

    // By logs
    public static function lastVisitLogs($uid)
    {
        $sql = "SELECT add_date as latest_date, user_ip as latest_ip, device_id, user_id FROM users_agent_logs WHERE user_id = :uid ORDER BY id DESC";

        return DB::run($sql, ['uid' => $uid])->fetch();
    }

    public static function getUserSearchDeviceID($item)
    {
        $sql = "SELECT 
                    log.device_id,
                    log.add_date,
                    u.id,
					u.login,
                    u.name,
                    u.email,
                    u.avatar,
                    u.created_at,
                    u.reg_ip,
                    u.invitation_id,
                    u.trust_level,
                    u.ban_list
                        FROM users_agent_logs log
						    LEFT JOIN users u ON u.id = log.user_id 
								WHERE log.device_id = :item";

        return DB::run($sql, ['item' => $item])->fetchAll();
    }

    public static function getUserSearchRegIp($item)
    {
        $sql = "SELECT 
                    id,
                    login,
                    name,
                    email,
                    avatar,
                    created_at,
                    reg_ip,
                    invitation_id,
                    trust_level,
                    ban_list
                        FROM users WHERE reg_ip = :item";

        return DB::run($sql, ['item' => $item])->fetchAll();
    }

    // ip for logs
    public static function getUserLogsId($ip)
    {
        $sql = "SELECT 
                    id,
                    login,
                    name,
                    email,
                    avatar,
                    created_at,
                    reg_ip,
                    invitation_id,
                    trust_level,
                    ban_list,
                    latest_date
                        FROM users
                            JOIN 
                            ( SELECT 
                                MAX(add_date) as latest_date,
                                user_id
                                FROM users_agent_logs 
                                WHERE user_ip = :ip GROUP BY user_id
                            ) as latest_date ON latest_date.user_id = id";
                            
        return DB::run($sql, ['ip' => $ip])->fetchAll();
    }

    // Recent visits
    public static function getLastVisit()
    {
        $sql = "SELECT 
                    id,
                    login,
                    name,
                    email,
                    avatar,
                    created_at,
                    reg_ip,
                    invitation_id,
                    trust_level,
                    latest_date,
                    os
                        FROM users
                            JOIN 
                            ( SELECT 
                                MAX(user_os) as os,
                                MAX(add_date) as latest_date,
                                user_id
                                FROM users_agent_logs 
                                GROUP BY user_id
                            ) as latest_date ON latest_date.user_id = id ORDER BY latest_date DESC LIMIT 10 ";

        return DB::run($sql)->fetchAll();
    }
}
