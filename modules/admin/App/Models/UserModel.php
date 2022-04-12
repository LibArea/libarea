<?php

namespace Modules\Admin\App\Models;

use DB;

class UserModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Users Page
    // Страница участников
    public static function getUsers($page, $limit, $sheet)
    {
        $string = "ORDER BY id DESC LIMIT";
        if ($sheet == 'users.ban') {
            $string = "WHERE ban_list > 0 ORDER BY id DESC LIMIT";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT  
                    id,
                    login,
                    email,
                    name,
                    avatar,
                    created_at,
                    whisper,
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
        $string = "";
        if ($sheet == 'users.ban') {
            $string = "WHERE ban_list > 0";
        }

        $sql = "SELECT 
                    id,
                    is_deleted
                        FROM users $string";

        return  DB::run($sql)->rowCount();
    }

    // Member information (id, slug)
    public static function getUser($params, $name)
    {
        $sort = "id = :params";
        if ($name == 'slug') {
            $sort = "login = :params";
        }

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

    // Number of member content
    public static function contentCount($uid)
    {
        $sql = "SELECT 
                    (SELECT COUNT(post_id) 
                        FROM posts 
                        WHERE post_user_id = $uid and post_draft = 0 and post_is_deleted = 0) 
                            AS count_posts,
                  
                    (SELECT COUNT(answer_id) 
                        FROM answers 
                        WHERE answer_user_id = $uid and answer_is_deleted = 0) 
                            AS count_answers,
                  
                    (SELECT COUNT(comment_id) 
                        FROM comments 
                        WHERE comment_user_id = $uid and comment_is_deleted = 0) 
                            AS count_comments";

        return DB::run($sql)->fetch();
    }

    // Number of IP duplicates by `user_reg_ip` field
    public static function duplicatesRegistrationCount($ip)
    {
        $sql = "SELECT 
                    id, 
                    reg_ip 
                        FROM users WHERE reg_ip = :ip";

        return DB::run($sql, ['ip' => $ip])->rowCount();
    }

    // By logs
    public static function lastVisitLogs($uid)
    {
        $sql = "SELECT 
                    MAX(add_date) as latest_date,
                    MAX(user_ip) as latest_ip,
                    user_id
                    FROM users_agent_logs 
                    WHERE user_id = :uid GROUP BY user_id";

        return DB::run($sql, ['uid' => $uid])->fetch();
    }

    public static function getUserRegsId($ip)
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
                        FROM users WHERE reg_ip = :ip";

        return DB::run($sql, ['ip' => $ip])->fetchAll();
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
                        ) as latest_date
                        ON latest_date.user_id = id";
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
                        ) as latest_date
                        ON latest_date.user_id = id
                        ORDER BY latest_date DESC LIMIT 10 ";

        return DB::run($sql)->fetchAll();
    }
}
