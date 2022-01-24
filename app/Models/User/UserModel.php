<?php

namespace App\Models\User;

use DB;

class UserModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Регистрация участника
    public static function create($params)
    {
        $sql = "INSERT INTO users(login, 
                                    email,
                                    template,
                                    lang,
                                    whisper,
                                    password, 
                                    limiting_mode, 
                                    activated, 
                                    reg_ip, 
                                    trust_level, 
                                    invitation_id) 
                                    
                            VALUES(:login, 
                                    :email, 
                                    :template,
                                    :lang,
                                    :whisper,
                                    :password, 
                                    :limiting_mode, 
                                    :activated, 
                                    :reg_ip, 
                                    :trust_level, 
                                    :invitation_id)";

        DB::run($sql, $params);

        $sql_last_id =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch();

        return $sql_last_id['last_id'];
    }

    // Страница участников
    public static function getUsersAll($page, $limit, $uid, $type)
    {
        $sort = "ORDER BY id = :uid DESC, trust_level DESC";
        if ($type == 'users.new') {
            $sort = "ORDER BY created_at DESC";
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
                    trust_level,
                    activated,
                    invitation_id,
                    limiting_mode,
                    reg_ip,
                    ban_list,
                    is_deleted
                        FROM users 
                        WHERE is_deleted != 1 and ban_list != 1
                            $sort
                            LIMIT $start, $limit";

        return DB::run($sql, ['uid' => $uid])->fetchAll();
    }

    // Количество
    public static function getUsersAllCount()
    {
        $sql = "SELECT 
                    id,
                    is_deleted
                        FROM users WHERE ban_list = 0";

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
                    whisper,
                    activated,
                    limiting_mode,
                    reg_ip,
                    email,
                    avatar,
                    trust_level,
                    cover_art,
                    color,
                    template,
                    lang,
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
                    updated_at,
                    my_post,
                    ban_list,
                    hits_count,
                    up_count,
                    is_deleted 
                        FROM users WHERE $sort"; // BINARY

        return DB::run($sql, ['params' => $params])->fetch();
    }

    // Просмотры  
    public static function userHits($uid)
    {
        $sql = "UPDATE users SET hits_count = (hits_count + 1) WHERE id = :uid";

        return  DB::run($sql, ['uid' => $uid]);
    }

    // Страница закладок участника (комментарии и посты)
    public static function userFavorite($uid)
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
                    post_answers_count,
                    answer_id,
                    answer_post_id,
                    answer_content
                        FROM favorites
                        LEFT JOIN users ON id = favorite_user_id
                        LEFT JOIN posts ON post_id = favorite_tid AND favorite_type = 1
                        LEFT JOIN answers ON answer_id = favorite_tid AND favorite_type = 2
                        WHERE favorite_user_id = :uid ORDER BY favorite_id DESC LIMIT 100";

        return DB::run($sql, ['uid' => $uid])->fetchAll();
    }

    // Страница черновиков
    public static function userDraftPosts($uid)
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
                           WHERE id = :uid 
                           AND post_draft = 1 AND post_is_deleted = 0
                           ORDER BY post_id DESC";

        return DB::run($sql, ['uid' => $uid])->fetchAll();
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
                   template,
                   lang,
                   avatar,
                   trust_level,
                   ban_list,
                   limiting_mode
                        FROM users 
                        WHERE email = :email";

        return DB::run($sql, ['email' => $email])->fetch();
    }

    // Количество контента участника
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

    // Находит ли пользователь в бан- листе
    public static function isBan($uid)
    {
        $sql = "SELECT
                    banlist_user_id,
                    banlist_status
                        FROM users_banlist
                        WHERE banlist_user_id = :uid AND banlist_status = 1";

        return DB::run($sql, ['uid' => $uid])->fetch();
    }

    // Находит ли пользователь в бесшумном режиме
    public static function isLimitingMode($uid)
    {
        $sql = "SELECT
                    id,
                    limiting_mode
                        FROM users
                        WHERE id = :uid AND limiting_mode = 1";

        return DB::run($sql, ['uid' => $uid])->fetch();
    }

    // Активирован ли пользователь (e-mail)
    public static function isActivated($uid)
    {
        $sql = "SELECT
                    id,
                    activated
                        FROM users
                        WHERE id = :uid AND activated = 1";

        return DB::run($sql, ['uid' => $uid])->fetch();
    }

    // Password Recovery
    public static function initRecover($params)
    {
        $sql = "INSERT INTO users_activate(activate_date, activate_user_id, activate_code) 
                       VALUES(:activate_date, :activate_user_id, :activate_code)";

        return DB::run($sql, $params);
    }

    // Для одноразового использования кода восстановления
    public static function editRecoverFlag($uid)
    {
        $sql = "UPDATE users_activate SET activate_flag = 1 WHERE activate_user_id = :uid";

        return DB::run($sql, ['uid' => $uid]);
    }

    // Проверяем код смены пароля (использовали его или нет)
    public static function getPasswordActivate($code)
    {
        $sql = "SELECT
                    activate_id,
                    activate_date,
                    activate_user_id,
                    activate_code,
                    activate_flag
                        FROM users_activate
                        WHERE activate_code = :code AND activate_flag != 1";

        return DB::run($sql, ['code' => $code])->fetch();
    }

    // Email Activation
    public static function sendActivateEmail($params)
    {
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

        return DB::run($sql, ['code' => $code, 'flag' => 1])->fetch();
    }

    // Активируем e-mail
    public static function EmailActivate($uid)
    {
        $sql = "UPDATE users_email_activate SET email_activate_flag = :flag WHERE user_id = :uid";

        DB::run($sql, ['uid' => $uid, 'flag' => 1]);

        $sql = "UPDATE users SET activated = :flag WHERE id = :uid";

        return DB::run($sql, ['uid' => $uid, 'flag' => 1]);
    }
    
    public static function setLogAgent($params)
    {
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
