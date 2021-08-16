<?php

namespace Modules\Admin\Models;

use DB;
use PDO;

class UserModel extends \MainModel
{
    // Страница участников
    public static function getUsersListForAdmin($page, $limit, $sheet)
    {
        $string = "WHERE user_ban_list > 0";
        if ($sheet == 'all') {
            $string = "";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    user_id,
                    user_login,
                    user_email,
                    user_name,
                    user_avatar,
                    user_created_at,
                    user_trust_level,
                    user_activated,
                    user_invitation_id,
                    user_limiting_mode,
                    user_reg_ip,
                    user_ban_list
                        FROM users $string ORDER BY user_id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество участинков
    public static function getUsersListForAdminCount($sheet)
    {
        $string = "WHERE user_ban_list > 0";
        if ($sheet == 'all') {
            $string = "";
        }

        $sql = "SELECT user_id FROM users $string";

        return DB::run($sql)->rowCount();
    }

    // По логам
    public static function userLogId($user_id)
    {
        $sql = "SELECT 
                    logs_id,
                    logs_user_id,
                    logs_login,
                    logs_trust_level,
                    logs_ip_address,
                    logs_date
                        FROM users_logs 
                        WHERE logs_user_id = :user_id ORDER BY logs_user_id DESC";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Получение информации по ip для сопоставления
    public static function getUserLogsId($ip)
    {
        $sql = "SELECT 
                    logs_id,
                    logs_user_id,
                    logs_login,
                    logs_trust_level,
                    logs_ip_address,
                    logs_date,
                    user_id,
                    user_login,
                    user_name,
                    user_email,
                    user_avatar,
                    user_created_at,
                    user_reg_ip,
                    user_invitation_id,
                    user_trust_level
                        FROM users_logs 
                        LEFT JOIN users ON user_id = logs_user_id
                        WHERE logs_ip_address = :ip OR user_reg_ip = :ip";

        return DB::run($sql, ['ip' => $ip])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Проверка IP на дубликаты
    public static function replayIp($ip)
    {
        $sql = "SELECT 
                    user_id, 
                    user_reg_ip 
                        FROM users WHERE user_reg_ip = :ip";

        return DB::run($sql, ['ip' => $ip])->rowCount();
    }

    // Находит ли пользователь в бан- листе и рабанен ли был он
    public static function isBan($user_id)
    {
        $sql = "SELECT 
                    banlist_user_id, 
                    banlist_status,
                    banlist_int_num
                        FROM users_banlist 
                        WHERE banlist_user_id = :user_id AND banlist_status = 1";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    public static function setBanUser($user_id)
    {
        $sql = "SELECT 
                    banlist_user_id,
                    banlist_status
                        FROM users_banlist WHERE banlist_user_id = :user_id ";

        $sample = DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
        $num    = DB::run($sql, ['user_id' => $user_id])->rowCount();

        if ($num != 0) {

            $result = array();
            foreach ($sample as $row) {
                $status = $row['banlist_status'];
            }

            if ($status == 0) {
                // Забанить повторно
                // Проставляем в banlist_int_num 2, что пока означет: возможно > 2
                $sql = "UPDATE users_banlist
                            SET banlist_int_num = 2, banlist_status = 1
                                WHERE banlist_user_id = :user_id";

                DB::run($sql, ['user_id' => $user_id]);

                self::setUserBanList($user_id, 1);
            } else {
                // Разбанить
                $sql = "UPDATE users_banlist
                            SET banlist_status = 0
                                WHERE banlist_user_id = :user_id";

                DB::run($sql, ['user_id' => $user_id]);

                self::setUserBanList($user_id, 0);
            }
        } else {
            // Занесем ip регистрации    
            $sql = "SELECT 
                        user_id, 
                        user_reg_ip
                            FROM users WHERE user_id = :user_id";

            $user = DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);

            $params = [
                'banlist_user_id'       => $user_id,
                'banlist_ip'            => $user['user_reg_ip'],
                'banlist_bandate'       => date("Y-m-d H:i:s"),
                'banlist_int_num'       => 1,
                'banlist_int_period'    => '',
                'banlist_status'        => 1,
                'banlist_autodelete'    => 0,
                'banlist_cause'         => '',
            ];

            $sql = "INSERT INTO users_banlist(banlist_user_id, 
                        banlist_ip, 
                        banlist_bandate, 
                        banlist_int_num,
                        banlist_int_period,
                        banlist_status,
                        banlist_autodelete,
                        banlist_cause) 
                            VALUES(:banlist_user_id, 
                                :banlist_ip, 
                                :banlist_bandate, 
                                :banlist_int_num,
                                :banlist_int_period,
                                :banlist_status,
                                :banlist_autodelete,
                                :banlist_cause)";

            DB::run($sql, $params);

            self::setUserBanList($user_id, 1);
        }

        return true;
    }

    // Изменим отмеку о занесении в бан-лист
    public static function setUserBanList($user_id, $status)
    {
        $sql = "UPDATE users 
                    SET user_ban_list = :status
                        WHERE user_id = :user_id";

        return  DB::run($sql, ['status' => $status, 'user_id' => $user_id]);
    }

    // Дерева инвайтов
    public static function getInvitations()
    {
        $sql = "SELECT 
                    user_id,
                    user_login,
                    user_avatar,
                    user_uid,
                    user_active_uid,
                    user_active_time
                        FROM invitations
                        LEFT JOIN users ON active_uid = user_id ORDER BY id DESC";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Редактирование участника
    public static function setUserEdit($data)
    {
        $params = [
            'user_id'            => $data['user_id'],
            'user_email'         => $data['user_email'],
            'user_login'         => $data['user_login'],
            'user_name'          => $data['user_name'],
            'user_activated'     => $data['user_activated'],
            'user_limiting_mode' => $data['user_limiting_mode'],
            'user_about'         => $data['user_about'],
            'user_trust_level'   => $data['user_trust_level'],
            'user_website'       => $data['user_website'],
            'user_location'      => $data['user_location'],
            'user_public_email'  => $data['user_public_email'],
            'user_skype'         => $data['user_skype'],
            'user_twitter'       => $data['user_twitter'],
            'user_telegram'      => $data['user_telegram'],
            'user_vk'            => $data['user_vk'],
        ];

        $sql = "UPDATE users SET 
                    user_email           = :user_email,  
                    user_login           = :user_login, 
                    user_name            = :user_name,
                    user_activated       = :user_activated,
                    user_limiting_mode   = :user_limiting_mode,
                    user_about           = :user_about,
                    user_trust_level     = :user_trust_level,
                    user_website         = :user_website,
                    user_location        = :user_location,
                    user_public_email    = :user_public_email,
                    user_skype           = :user_skype,
                    user_twitter         = :user_twitter,
                    user_telegram        = :user_telegram,
                    user_vk              = :user_vk
                        WHERE user_id    = :user_id";

        return  DB::run($sql, $params);
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
                    user_activated,
                    user_limiting_mode,
                    user_reg_ip,
                    user_email,
                    user_avatar,
                    user_trust_level,
                    user_cover_art,
                    user_color,
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
                    user_my_post,
                    user_ban_list,
                    user_hits_count,
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
}
