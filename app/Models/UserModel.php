<?php

namespace App\Models;

use Lori\Config;
use DB;
use PDO;

class UserModel extends \MainModel
{
    // Страница участников
    public static function getUsersAll($page, $limit, $user_id)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT  
                    id,
                    login,
                    name, 
                    avatar,
                    is_deleted
                        FROM users 
                        WHERE is_deleted != 1 and ban_list != 1
                        ORDER BY id = :user_id DESC, trust_level DESC LIMIT $start, $limit";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество
    public static function getUsersAllCount()
    {
        $sql = "SELECT 
                    id, 
                    is_deleted
                        FROM users
                        WHERE is_deleted = 0";

        return  DB::run($sql)->rowCount();
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

    // Регистрация участника
    public static function createUser($login, $email, $password, $reg_ip, $invitation_id)
    {
        // количество участников 
        $sql    = "SELECT id, is_deleted FROM users WHERE is_deleted = 0";
        $count  = DB::run($sql)->rowCount();

        // Для "режима запуска" первые 50 участников получают trust_level = 1 
        $trust_level = 0;
        if ($count < 50 && Config::get(Config::PARAM_MODE) == 1) {
            $trust_level = 1;
        }

        $password   = password_hash($password, PASSWORD_BCRYPT);

        $activated = 0; // Требуется активация по e-mail
        if ($invitation_id > 0) {
            $activated = 1;
        }

        $params = [
            'login'         => $login,
            'email'         => $email,
            'password'      => $password,
            'limiting_mode' => 0, // Режим заморозки выключен
            'activated'     => $activated,
            'reg_ip'        => $reg_ip,
            'trust_level'   => $trust_level,
            'invitation_id' => $invitation_id,
        ];

        $sql = "INSERT INTO users(login, 
                                    email, 
                                    password, 
                                    limiting_mode, 
                                    activated, 
                                    reg_ip, 
                                    trust_level, 
                                    invitation_id) 
                                    
                            VALUES(:login, 
                                    :email, 
                                    :password, 
                                    :limiting_mode, 
                                    :activated, 
                                    :reg_ip, 
                                    :trust_level, 
                                    :invitation_id)";

        DB::run($sql, $params);

        $sql_last_id =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch(PDO::FETCH_ASSOC);

        return $sql_last_id['last_id'];
    }

    // Изменение пароля
    public static function editPassword($user_id, $password)
    {
        $sql = "UPDATE users SET password = :password WHERE id = :user_id";

        return  DB::run($sql, ['user_id' => $user_id, 'password' => $password]);
    }

    // Просмотры  
    public static function userHits($user_id)
    {
        $sql = "UPDATE users SET hits_count = (hits_count + 1) WHERE id = :user_id";

        return  DB::run($sql, ['user_id' => $user_id]);
    }

    // Изменение аватарки / обложки
    public static function setImg($user_id, $img)
    {
        $sql = "UPDATE users SET avatar = :img WHERE id = :user_id";

        return  DB::run($sql, ['user_id' => $user_id, 'img' => $img]);
    }

    public static function setCover($user_id, $img)
    {
        $sql = "UPDATE users SET cover_art = :img WHERE id = :user_id";

        return  DB::run($sql, ['user_id' => $user_id, 'img' => $img]);
    }

    // TL - название
    public static function getUserTrust($user_id)
    {
        $sql = "SELECT 
                    id,
                    trust_level, 
                    trust_id,
                    trust_name                    
                        FROM users_trust_level
                        LEFT JOIN users ON trust_level = trust_id
                        WHERE id = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Страница закладок участника (комментарии и посты)
    public static function userFavorite($user_id)
    {
        $sql = "SELECT 
                    favorite_id,
                    favorite_user_id, 
                    favorite_type,
                    favorite_tid,
                    id, 
                    login,
                    avatar, 
                    post_id,
                    post_title,
                    post_slug,
                    post_date,
                    post_space_id,
                    post_answers_count,
                    answer_id,
                    answer_post_id,
                    answer_content,
                    space_id,
                    space_name,
                    space_slug
                        FROM favorites
                        LEFT JOIN users ON id = favorite_user_id
                        LEFT JOIN posts ON post_id = favorite_tid AND favorite_type = 1
                        LEFT JOIN answers ON answer_id = favorite_tid AND favorite_type = 2
                        LEFT JOIN spaces ON  space_id = post_space_id
                        WHERE favorite_user_id = :user_id ORDER BY favorite_id DESC LIMIT 100";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Страница черновиков
    public static function userDraftPosts($user_id)
    {
        $sql = "SELECT
                   post_id,
                   post_title,
                   post_slug,
                   post_user_id,
                   post_draft,
                   post_is_deleted,
                   post_date,
                   id, 
                   login,
                   name,
                   avatar
                       FROM posts
                       LEFT JOIN users ON id = post_user_id
                           WHERE id = :user_id 
                           AND post_draft = 1 AND post_is_deleted = 0
                           ORDER BY post_id DESC";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Информация участника
    public static function userInfo($email)
    {
        $sql = "SELECT 
                   id, 
                   email, 
                   password,
                   login,
                   name,
                   avatar,
                   trust_level,
                   ban_list,
                   limiting_mode
                        FROM users 
                        WHERE email = :email";

        return DB::run($sql, ['email' => $email])->fetch(PDO::FETCH_ASSOC);
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

    // Редактирование профиля
    public static function editProfile($data)
    {
        $params = [
            'name'          => $data['name'],
            'color'         => $data['color'],
            'about'         => $data['about'],
            'website'       => $data['website'],
            'location'      => $data['location'],
            'public_email'  => $data['public_email'],
            'skype'         => $data['skype'],
            'twitter'       => $data['twitter'],
            'telegram'      => $data['telegram'],
            'vk'            => $data['vk'],
            'id'            => $data['id'],
        ];

        $sql = "UPDATE users SET 
                    name            = :name,
                    color           = :color,
                    about           = :about,
                    website         = :website,
                    location        = :location,
                    public_email    = :public_email,
                    skype           = :skype,
                    twitter         = :twitter,
                    telegram        = :telegram,
                    vk              = :vk
                        WHERE id    = :id";

        return DB::run($sql, $params);
    }

    // Удалим обложку для профиля
    public static function userCoverRemove($user_id)
    {
        $sql = "UPDATE users SET cover_art = 'cover_art.jpeg' WHERE id = :user_id";

        return DB::run($sql, ['user_id' => $user_id]);
    }

    // Записываем последние данные авторизации
    public static function setUserLastLogs($user_id, $login, $trust_level, $last_ip)
    {
        $params = [
            'logs_user_id'      => $user_id,
            'logs_login'        => $login,
            'logs_trust_level'  => $trust_level,
            'logs_ip_address'   => $last_ip,
        ];

        $sql = "INSERT INTO users_logs(logs_user_id, logs_login, logs_trust_level, logs_ip_address) 
                       VALUES(:logs_user_id, :logs_login, :logs_trust_level, :logs_ip_address)";

        return DB::run($sql, $params);
    }

    // Находит ли пользователь в бан- листе
    public static function isBan($user_id)
    {
        $sql = "SELECT
                    banlist_user_id,
                    banlist_status
                        FROM users_banlist
                        WHERE banlist_user_id = :user_id AND banlist_status = 1";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Находит ли пользователь в бесшумном режиме
    public static function isLimitingMode($user_id)
    {
        $sql = "SELECT
                    id,
                    limiting_mode
                        FROM users
                        WHERE id = :user_id AND limiting_mode = 1";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Активирован ли пользователь (e-mail)
    public static function isActivated($user_id)
    {
        $sql = "SELECT
                    id,
                    activated
                        FROM users
                        WHERE id = :user_id AND activated = 1";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Восстановления пароля
    public static function initRecover($user_id, $code)
    {
        $params = [
            'activate_date'     => date('Y-m-d H:i:s'),
            'activate_user_id'  => $user_id,
            'activate_code'     => $code,
        ];

        $sql = "INSERT INTO users_activate(activate_date, activate_user_id, activate_code) 
                       VALUES(:activate_date, :activate_user_id, :activate_code)";

        return DB::run($sql, $params);
    }

    // Для одноразового использования кода восстановления
    public static function editRecoverFlag($user_id)
    {
        $sql = "UPDATE users_activate SET activate_flag = 1 WHERE activate_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id]);
    }

    // Проверяем код смены пароля (использовали его или нет)
    public static function getPasswordActivate($code)
    {
        $sql = "SELECT
                    activate_code,
                    activate_flag
                        FROM users_activate
                        WHERE activate_code = :code AND activate_flag != 1";

        return DB::run($sql, ['code' => $code])->fetch(PDO::FETCH_ASSOC);
    }

    // Создадим инвайт для участника
    public static function addInvitation($user_id, $invitation_code, $invitation_email, $add_time, $add_ip)
    {
        $sql = "UPDATE users SET invitation_available = (invitation_available + 1) WHERE id = :user_id";

        DB::run($sql, ['user_id' => $user_id]);

        $params = [
            'uid'               => $user_id,
            'invitation_code'   => $invitation_code,
            'invitation_email'  => $invitation_email,
            'add_time'          => $add_time,
            'add_ip'            => $add_ip,
        ];

        $sql = "INSERT INTO invitations(uid, invitation_code, invitation_email, add_time, add_ip) 
                       VALUES(:uid, :invitation_code, :invitation_email, :add_time, :add_ip)";

        return DB::run($sql, $params);
    }

    // Проверим на повтор
    public static function InvitationOne($user_id)
    {
        $sql = "SELECT
                    uid,
                    invitation_email
                        FROM invitations
                        WHERE uid = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Все инвайты участинка
    public static function InvitationResult($user_id)
    {
        $sql = "SELECT 
                   uid, 
                   active_uid,
                   active_status,
                   add_time,
                   invitation_email,
                   invitation_code,                  
                   id,
                   avatar,
                   login
                        FROM invitations
                            LEFT JOIN users ON id = active_uid
                            WHERE uid = :user_id
                            ORDER BY add_time DESC";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Проверим не активированный инвайт
    public static function InvitationAvailable($invitation_code)
    {
        $sql = "SELECT
                    uid,
                    active_status,
                    invitation_code,
                    invitation_email
                        FROM invitations
                        WHERE invitation_code = :code AND active_status = 0";

        return DB::run($sql, ['code' => $invitation_code])->fetch(PDO::FETCH_ASSOC);
    }

    // Проверим не активированный инвайт и поменяем статус
    public static function sendInvitationEmail($inv_code, $inv_uid, $reg_ip, $active_uid)
    {
        $params = [
            'active_status'     => 1,
            'active_ip'         => $reg_ip,
            'active_time'       => date('Y-m-d H:i:s'),
            'active_uid'        => $active_uid,
            'invitation_code'   => $inv_code,
            'uid'               => $inv_uid,
        ];

        $sql = "UPDATE invitations SET 
                    active_status   = :active_status,
                    active_ip       = :active_ip,
                    active_time     = :active_time,
                    active_uid      = :active_uid
                        WHERE invitation_code = :invitation_code
                            AND uid = :uid";

        DB::run($sql, $params);
    }

    // Делаем запись в таблицу активации e-mail
    public static function sendActivateEmail($user_id, $email_code)
    {
        $params = [
            'pubdate'       => date("Y-m-d H:i:s"),
            'user_id'       => $user_id,
            'email_code'    => $email_code,
        ];

        $sql = "INSERT INTO users_email_activate(pubdate, user_id, email_code) 
                       VALUES(:pubdate, :user_id, :email_code)";

        return DB::run($sql, $params);
    }

    // Проверяем код активации e-mail
    public static function getEmailActivate($code)
    {
        $sql = "SELECT
                    user_id,
                    email_code,
                    email_activate_flag
                        FROM users_email_activate
                        WHERE email_code = :code AND email_activate_flag != :flag";

        return DB::run($sql, ['code' => $code, 'flag' => 1])->fetch(PDO::FETCH_ASSOC);
    }

    // Активируем e-mail
    public static function EmailActivate($user_id)
    {
        $sql = "UPDATE users_email_activate SET email_activate_flag = :flag WHERE user_id = :user_id";

        DB::run($sql, ['user_id' => $user_id, 'flag' => 1]);

        $sql = "UPDATE users SET activated = :flag WHERE id = :user_id";

        return DB::run($sql, ['user_id' => $user_id, 'flag' => 1]);
    }

    // Все награды участника
    public static function getBadgeUserAll($user_id)
    {
        $sql = "SELECT 
                   badge_id,
                   badge_icon,
                   badge_title,
                   badge_description,
                   bu_badge_id,                   
                   bu_user_id
                        FROM badges_user
                            LEFT JOIN badges ON badge_id = bu_badge_id
                            WHERE bu_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }
}
