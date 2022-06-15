<?php

namespace App\Models\User;

use DB;

class SettingModel extends \Hleb\Scheme\App\Models\MainModel
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
        $sql = "UPDATE users 
                    SET cover_art   = :cover_art, 
                        updated_at  = :updated_at 
                            WHERE id = :id";

        return  DB::run($sql, $params);
    }

    // When removing the cover, we will write down the default
    // При удаление обложки запишем дефолтную
    public static function coverRemove($params)
    {
        $sql = "UPDATE users 
                    SET cover_art = :cover_art, 
                        updated_at = :updated_at 
                            WHERE id = :id";

        return DB::run($sql, $params);
    }

    public static function countNotifications($uid)
    {
        $sql = "SELECT 
                    setting_id, 
                    setting_user_id
                        FROM users_setting WHERE setting_user_id = :uid";

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
                        setting_email_answer    = :setting_email_answer,
                        setting_email_comment   = :setting_email_comment
                            WHERE setting_user_id = :setting_user_id";

        if (!self::countNotifications($params['setting_user_id'])) {

            $sql = "INSERT INTO users_setting(setting_user_id, 
                            setting_email_pm, 
                            setting_email_appealed,
                            setting_email_post,
                            setting_email_answer,
                            setting_email_comment) 
                                VALUES(:setting_user_id, 
                                    :setting_email_pm, 
                                    :setting_email_appealed,
                                    :setting_email_post,
                                    :setting_email_answer,
                                    :setting_email_comment)";
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
}
