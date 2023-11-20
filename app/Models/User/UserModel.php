<?php

namespace App\Models\User;

use UserData;
use DB;

class UserModel extends \Hleb\Scheme\App\Models\MainModel
{
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

    public static function getUsersAll($page, $limit, $type)
    {
        $user_id = UserData::getUserId();
        $sort = ($type == 'new') ? "ORDER BY created_at DESC" : "ORDER BY id = $user_id DESC, avatar ASC";

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
                                    LIMIT :start, :limit";

        return DB::run($sql, ['start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getUsersAllCount()
    {
        return  DB::run("SELECT id, is_deleted FROM users WHERE ban_list = 0")->rowCount();
    }

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
                    scroll,
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
                    github,
                    skype,
                    telegram,
                    vk,
                    created_at,
                    updated_at,
                    my_post,
                    ban_list,
                    nsfw,
                    hits_count,
                    up_count,
                    is_deleted 
                        FROM users WHERE $sort"; // BINARY

        return DB::run($sql, ['params' => $params])->fetch();
    }

    // Profile views
    // Просмотры профиля
    public static function userHits($user_id)
    {
        $sql = "UPDATE users SET hits_count = (hits_count + 1) WHERE id = :user_id";

        return  DB::run($sql, ['user_id' => $user_id]);
    }

    // For bookmarks: posts, comments and sites
    // Для закладок: посты, комментарии и сайты
    public static function userFavorite($tag_id = null)
    {
        $user_id = UserData::getUserId();
        
        $tag = '';
        if ($tag_id) $tag = 'AND fol.id = ' . $tag_id;

        $sql = "SELECT 
                    fav.id,
                    fav.user_id, 
                    fav.action_type,
                    fav.tid,
                    fol.id as tag_id,
                    fol.title as tag_title,
                    post_id,
                    post_title,
                    post_slug,
                    comment_id,
                    comment_post_id,
                    comment_content,
                    item_id,
                    item_title,
                    item_url,
                    item_slug,
                    item_content,
                    item_domain
                        FROM favorites fav
                            LEFT JOIN posts ON post_id = fav.tid AND fav.action_type = 'post'
                            LEFT JOIN comments ON comment_id = fav.tid AND fav.action_type = 'comment'
                            LEFT JOIN items ON item_id = fav.tid AND fav.action_type = 'website'
                              LEFT JOIN folders_relation fr ON fr.tid = fav.tid
                              LEFT JOIN folders fol ON folder_id = fol.id AND fol.user_id = :uid2
                                WHERE fav.user_id = :user_id $tag ORDER BY fav.id DESC LIMIT 100";

        return DB::run($sql, ['user_id' => $user_id, 'uid2' => $user_id])->fetchAll();
    }

    // Draft page
    // Страница черновиков
    public static function userDraftPosts()
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

        return DB::run($sql, ['user_id' => UserData::getUserId()])->fetchAll();
    }

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

    // Amount of member content
    // Количество контента участника
    public static function contentCount($user_id, $type = 0)
    {
        $condition = $type == 'remote' ? 1 : 0;

        $sql = "SELECT 
                    (SELECT COUNT(post_id) FROM posts WHERE post_user_id = $user_id and post_draft = 0 and post_is_deleted = $condition) AS count_posts,
                  
                    (SELECT COUNT(comment_id) FROM comments WHERE comment_user_id = $user_id and comment_is_deleted = $condition) AS count_comments,
                            
                    (SELECT COUNT(item_id) FROM items WHERE item_user_id = $user_id and item_is_deleted = $condition) AS count_items";

        return DB::run($sql)->fetch();
    }

    // Does the user find in the ban list
    // Находит ли пользователь в бан- листе
    public static function isBan($user_id)
    {
        $sql = "SELECT
                    banlist_user_id,
                    banlist_status
                        FROM users_banlist
                            WHERE banlist_user_id = :user_id AND banlist_status = 1";

        return DB::run($sql, ['user_id' => $user_id])->fetch();
    }

    // Whether the user is in silent mode
    // Находит ли пользователь в бесшумном режиме
    public static function isLimitingMode($user_id)
    {
        $sql = "SELECT id, limiting_mode FROM users WHERE id = :user_id AND limiting_mode = 1";

        return DB::run($sql, ['user_id' => $user_id])->fetch();
    }

    // Is the user activated (e-mail)
    // Активирован ли пользователь (e-mail)
    public static function isActivated($user_id)
    {
        $sql = "SELECT id, activated FROM users WHERE id = :user_id AND activated = 1";

        return DB::run($sql, ['user_id' => $user_id])->fetch();
    }

    // Has the user been deleted?
    // Удален ли пользователь?
    public static function isDeleted($user_id)
    {
        $sql = "SELECT id, is_deleted FROM users WHERE id = :user_id AND is_deleted = 1";

        return DB::run($sql, ['user_id' => $user_id])->fetch();
    }

    // Password Recovery
    public static function initRecover($params)
    {
        $sql = "INSERT INTO users_activate(activate_date, activate_user_id, activate_code) 
                       VALUES(:activate_date, :activate_user_id, :activate_code)";

        return DB::run($sql, $params);
    }

    // For a one-time use of the recovery code
    // Для одноразового использования кода восстановления
    public static function editRecoverFlag($user_id)
    {
        $sql = "UPDATE users_activate SET activate_flag = 1 WHERE activate_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id]);
    }

    // Check the password change code (whether it was used or not)
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
        $sql = "INSERT INTO users_email_activate(user_id, email_code) VALUES(:user_id, :email_code)";

        return DB::run($sql, $params);
    }

    // Check activation code e-mail
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

    // Activate email
    // Активируем e-mail
    public static function EmailActivate($user_id)
    {
        $sql = "UPDATE users_email_activate SET email_activate_flag = :flag WHERE user_id = :user_id";

        DB::run($sql, ['user_id' => $user_id, 'flag' => 1]);

        $sql = "UPDATE users SET activated = :flag WHERE id = :user_id";

        return DB::run($sql, ['user_id' => $user_id, 'flag' => 1]);
    }

    public static function setLogAgent($params)
    {
        $sql = "INSERT INTO users_agent_logs(user_id, user_browser, user_os, user_ip) 
                    VALUES(:user_id, :user_browser, :user_os, :user_ip)";

        return DB::run($sql, $params);
    }

}
