<?php

declare(strict_types=1);

namespace App\Models\User;

use Hleb\Base\Model;
use Hleb\Static\DB;

class SettingModel extends Model
{
    // Editing a profile
    // Редактирование профиля
    public static function edit($params)
    {
        $sql = "UPDATE users SET 
                    email           = :email,  
                    login           = :login, 
                    whisper         = :whisper, 
                    name            = :name,
                    activated       = :activated,
                    limiting_mode   = :limiting_mode,
                    scroll          = :scroll,
                    nsfw            = :nsfw,
					post_design 	= :post_design,
                    template        = :template,
                    lang            = :lang,
                    updated_at      = :updated_at,
                    color           = :color,
                    about           = :about,
                    trust_level     = :trust_level,
                    website         = :website,
                    location        = :location,
                    public_email    = :public_email,
                    github          = :github,
                    skype           = :skype,
                    telegram        = :telegram,
                    vk              = :vk
                        WHERE id    = :id";

        return DB::run($sql, $params);
    }

    // Changing the password
    // Изменение пароля
    public static function editPassword($params)
    {
        $sql = "UPDATE users SET password = :password WHERE id = :id";

        return  DB::run($sql, $params);
    }

    // Changing the avatar
    // Изменение аватарки
    public static function setImg($params)
    {
        $sql = "UPDATE users SET avatar = :avatar, updated_at = :updated_at WHERE id = :id";

        return  DB::run($sql, $params);
    }

    // Changing the cover
    // Изменение обложки
    public static function setCover($params)
    {
        $sql = "UPDATE users SET cover_art   = :cover_art, updated_at  = :updated_at WHERE id = :id";

        return  DB::run($sql, $params);
    }

    // When removing the cover, we will write down the default
    // При удаление обложки запишем дефолтную
    public static function coverRemove($params)
    {
        $sql = "UPDATE users SET cover_art = :cover_art, updated_at = :updated_at WHERE id = :id";

        return DB::run($sql, $params);
    }

    public static function countNotifications($uid)
    {
        $sql = "SELECT setting_id, setting_user_id FROM users_setting WHERE setting_user_id = :uid";

        return DB::run($sql, ['uid' => $uid])->rowCount();
    }

    // We record personal notification settings, if there are none. Or updating.
    // Записываем личные настройки уведомлений, если их нет. Или обновляем.
    public static function setNotifications($params)
    {
        $sql = "UPDATE users_setting SET 
                        setting_email_pm        = :setting_email_pm,
                        setting_email_appealed  = :setting_email_appealed,
                        setting_email_post      = :setting_email_post,
                        setting_email_answer    = :setting_email_answer
                            WHERE setting_user_id = :setting_user_id";

        if (!self::countNotifications($params['setting_user_id'])) {

            $sql = "INSERT INTO users_setting(setting_user_id, 
                            setting_email_pm, 
                            setting_email_appealed,
                            setting_email_post,
                            setting_email_answer) 
                                VALUES(:setting_user_id, 
                                    :setting_email_pm, 
                                    :setting_email_appealed,
                                    :setting_email_post,
                                    :setting_email_answer)";
        }

        return DB::run($sql, $params);
    }

    public static function getNotifications($uid)
    {
        $sql = "SELECT 
                    setting_id, 
                    setting_user_id,
                    setting_email_pm,
                    setting_email_appealed
                        FROM users_setting WHERE setting_user_id = :uid";

        return DB::run($sql, ['uid' => $uid])->fetch();
    }
    
    // Change of mail
    public static function getNewEmail()
    {
        $sql = "SELECT email FROM users_email_story WHERE user_id = :user_id AND email_activate_flag = :flag";

        return DB::run($sql, ['user_id' => self::container()->user()->id(), 'flag' => 0])->fetch();
    }
    
    public static function setNewEmail($email, $code)
    {
        $params = [
            'user_id'               => self::container()->user()->id(),
            'email'                 => $email,
            'email_code'            => $code,
        ];
        
        $sql = "INSERT INTO users_email_story(user_id, email, email_code) VALUES(:user_id, :email, :email_code)";

        return DB::run($sql, $params);
    }

    public static function available($code)
    {
        $sql = "SELECT email_activate_flag FROM users_email_story WHERE email_code = :code AND user_id = :user_id AND email_activate_flag = :flag";

        return DB::run($sql, ['code' => $code, 'user_id' => self::container()->user()->id(), 'flag' => 0])->fetch();
    }

    public static function editEmail($email)
    {
        DB::run("UPDATE users SET email = :email WHERE id = :user_id", ['user_id' => self::container()->user()->id(), 'email' => $email]);
        
        $sql = "UPDATE users_email_story SET email_activate_flag = :flag WHERE user_id = :user_id AND email = :email";

        return DB::run($sql, ['user_id' => self::container()->user()->id(), 'email' => $email, 'flag' => 1]);
    }
    
    public static function deletionUser($user_id)
    {
        return  DB::run("UPDATE users SET is_deleted = :deleted WHERE id = :user_id", ['user_id' => $user_id, 'deleted' => 1]);
    }
}
