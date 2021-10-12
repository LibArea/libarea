<?php

namespace App\Models\User;

use Hleb\Scheme\App\Models\MainModel;
use DB, PDO;

class SettingModel extends MainModel
{
    // Изменение пароля
    public static function editPassword($user_id, $password)
    {
        $sql = "UPDATE users SET user_password = :password WHERE user_id = :user_id";

        return  DB::run($sql, ['user_id' => $user_id, 'password' => $password]);
    }

    // Изменение аватарки / обложки
    public static function setImg($user_id, $new_img, $date)
    {
        $params = [
            'user_id'           => $user_id,
            'user_avatar'       => $new_img,
            'user_updated_at'   => $date,
        ];
        
        $sql = "UPDATE users 
                    SET user_avatar = :user_avatar, user_updated_at = :user_updated_at 
                    WHERE user_id = :user_id";

        return  DB::run($sql, $params);
    }

    public static function setCover($user_id, $new_cover, $date)
    {
        $params = [
            'user_id'           => $user_id,
            'user_cover_art'    => $new_cover,
            'user_updated_at'   => $date,
        ];
        
        $sql = "UPDATE users 
                    SET user_cover_art = :user_cover_art, user_updated_at = :user_updated_at 
                    WHERE user_id = :user_id";

        return  DB::run($sql, $params);
    }
    
    // Редактирование профиля
    public static function editProfile($data)
    {
        $params = [
            'user_id'            => $data['user_id'],
            'user_email'         => $data['user_email'],
            'user_login'         => $data['user_login'],
            'user_whisper'       => $data['user_whisper'],
            'user_name'          => $data['user_name'],
            'user_activated'     => $data['user_activated'],
            'user_limiting_mode' => $data['user_limiting_mode'],
            'user_color'         => $data['user_color'],
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
                    user_whisper         = :user_whisper, 
                    user_name            = :user_name,
                    user_activated       = :user_activated,
                    user_limiting_mode   = :user_limiting_mode,
                    user_color           = :user_color,
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


    // При удаление обложки запишем дефолтную
    public static function coverRemove($user_id, $date)
    {
        $params = [
            'user_id'           => $user_id,
            'user_updated_at'   => $date,
        ];
        
        $sql = "UPDATE users 
                    SET user_cover_art = 'cover_art.jpeg', user_updated_at = :user_updated_at 
                    WHERE user_id = :user_id";

        return DB::run($sql, $params);
    }

    // Есть или нет записи в личных настройках
    public static function countNotifications($user_id)
    {
                $sql = "SELECT 
                            setting_id, 
                            setting_user_id
                                FROM users_setting WHERE setting_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->rowCount();

    }
    
    // Записываем личные настройки уведомлений, если их нет. Или обновляем.
    public static function setNotifications($data, $user_id)
    {

        $params = [
            'setting_user_id'           => $user_id,
            'setting_email_pm'          => $data['setting_email_pm'],
            'setting_email_appealed'    => $data['setting_email_appealed'],
            'setting_email_post'        => 0,
            'setting_email_answer'      => 0,
            'setting_email_comment'     => 0,
        ];
       
        if (!self::countNotifications($user_id)) {
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

            DB::run($sql, $params);
            
        } else {
            $sql = "UPDATE users_setting SET 
                            setting_email_pm        = :setting_email_pm,
                            setting_email_appealed  = :setting_email_appealed,
                            setting_email_post      = :setting_email_post,
                            setting_email_answer    = :setting_email_answer,
                            setting_email_comment   = :setting_email_comment
                                WHERE setting_user_id = :setting_user_id";

            DB::run($sql, $params);
        }

        return true;
    }

    // Получаем настроки уведомлений
    public static function getNotifications($user_id)
    {
        $sql = "SELECT 
                    setting_id, 
                    setting_user_id,
                    setting_email_pm,
                    setting_email_appealed
                        FROM users_setting WHERE setting_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC); 
       
    }

}
