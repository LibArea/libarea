<?php

namespace Modules\Admin\Models;

use DB;
use PDO;

class UserModel extends \MainModel
{
    // Страница участников
    public static function getUsersListForAdmin($page, $limit, $sheet)
    {
        $string = "WHERE ban_list > 0";
        if ($sheet == 'all') {
            $string = "";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    id,
                    login,
                    email,
                    name,
                    avatar,
                    created_at,
                    trust_level,
                    activated,
                    invitation_id,
                    limiting_mode,
                    reg_ip,
                    ban_list
                        FROM users $string ORDER BY id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество участинков
    public static function getUsersListForAdminCount($sheet)
    {
        $string = "WHERE ban_list > 0";
        if ($sheet == 'all') {
            $string = "";
        }

        $sql = "SELECT id FROM users $string";

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
                    id
                        FROM users_logs 
                        LEFT JOIN users ON id = logs_user_id
                        WHERE logs_ip_address = :ip";

        return DB::run($sql, ['ip' => $ip])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Проверка IP на дубликаты
    public static function replayIp($ip)
    {
        $sql = "SELECT 
                    id, 
                    reg_ip 
                        FROM users WHERE reg_ip = :ip";

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
                        id, 
                        reg_ip
                            FROM users WHERE id = :user_id";

            $user = DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);

            $params = [
                'banlist_user_id'       => $user_id,
                'banlist_ip'            => $user['reg_ip'],
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
                    SET ban_list = :status
                        WHERE id = :user_id";

        return  DB::run($sql, ['status' => $status, 'user_id' => $user_id]);
    }

    // Дерева инвайтов
    public static function getInvitations()
    {
        $sql = "SELECT 
                    id,
                    login,
                    avatar,
                    uid,
                    active_uid,
                    active_time
                        FROM invitations
                        LEFT JOIN users ON active_uid = id ORDER BY id DESC";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Редактирование участника
    public static function setUserEdit($data)
    {
        $params = [
            'id'            => $data['id'],
            'email'         => $data['email'],
            'login'         => $data['login'],
            'name'          => $data['name'],
            'activated'     => $data['activated'],
            'limiting_mode' => $data['limiting_mode'],
            'about'         => $data['about'],
            'trust_level'   => $data['trust_level'],
            'website'       => $data['website'],
            'location'      => $data['location'],
            'public_email'  => $data['public_email'],
            'skype'         => $data['skype'],
            'twitter'       => $data['twitter'],
            'telegram'      => $data['telegram'],
            'vk'            => $data['vk'],
        ];

        $sql = "UPDATE users 
                    SET email       = :email,  
                    login           = :login, 
                    name            = :name,
                    activated       = :activated,
                    limiting_mode   = :limiting_mode,
                    about           = :about,
                    trust_level     = :trust_level,
                    website         = :website,
                    location        = :location,
                    public_email    = :public_email,
                    skype           = :skype,
                    twitter         = :twitter,
                    telegram        = :telegram,
                    vk              =:vk
                        WHERE id = :id";

        return  DB::run($sql, $params);
    }

    // Информация по участнику (id, slug)
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
                    activated,
                    limiting_mode,
                    reg_ip,
                    email,
                    avatar,
                    trust_level,
                    cover_art,
                    color,
                    invitation_available,
                    about,
                    website,
                    location,
                    public_email,
                    skype,
                    twitter,
                    telegram,
                    vk,
                    created_at,
                    my_post,
                    ban_list,
                    hits_count,
                    is_deleted 
                        FROM users WHERE $sort";

        $result = DB::run($sql, ['params' => $params]);

        return $result->fetch(PDO::FETCH_ASSOC);
    }

    // Количество контента участника
    public static function contentCount($user_id, $type)
    {
        if ($type == 'posts') {

            $sql = "SELECT post_id, post_draft, post_is_deleted 
                    FROM posts WHERE post_user_id = :user_id and post_draft = 0 and post_is_deleted = 0";
        } elseif ($type == 'comments') {
            $sql = "SELECT comment_id, comment_user_id, comment_is_deleted FROM comments WHERE comment_user_id = :user_id and comment_is_deleted = 0";
        } else {
            $sql = "SELECT answer_id, answer_user_id, answer_is_deleted FROM answers WHERE answer_user_id = :user_id and answer_is_deleted = 0";
        }

        return  DB::run($sql, ['user_id' => $user_id])->rowCount();
    }
}
