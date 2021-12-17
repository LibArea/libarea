<?php

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;

class Base
{
    // Initial level of trust
    // Начальные уровень домерия
    const ELEMENTARY_USER = 0;
    
    // User (level of trust 0)
    // Пользователь (уровень доверия 0)
    const USER_ZERO_LEVEL = 0;
    
    // User (level of trust 1)
    // Пользователь (уровень доверия 1)
    const USER_FIRST_LEVEL = 1;
   
    // User (level of trust 2)
    // Пользователь (уровень доверия 2)
    const USER_SECOND_LEVEL = 2;
    
    // User (level of trust 3)
    // Пользователь (уровень доверия 3)
    const USER_THIRD_LEVEL = 3;
   
    // User (level of trust 4)
    // Пользователь (уровень доверия 4)
    const USER_FOURTH_LEVEL = 4;
   
    // Administrator (level of trust 5)
    // Администратор (уровень доверия 5)
    const USER_LEVEL_ADMIN = 5;
    
    public static function getUid()
    {
        $account = Request::getSession('account') ?? [];
        $uid = [];

        if (!empty($account['user_id'])) {
            $uid['user_id']                 = $account['user_id'];
            $uid['user_login']              = $account['user_login'];
            $uid['user_template']           = $account['user_template'] ?? 'default'; // after a day to remove
            $uid['user_lang']               = $account['user_lang'] ?? 'ru'; // after a day to remove
            $uid['user_trust_level']        = $account['user_trust_level'];
            $uid['user_avatar']             = $account['user_avatar'];
            $uid['user_ban_list']           = $account['user_ban_list'];
            $uid['notif']                   = NotificationsModel::usersNotification($account['user_id']);

            Translate::setLang($uid['user_lang']);
            
            Request::getResources()->addBottomScript('/assets/js/app.js');
            
        } else {

            (new \App\Controllers\Auth\LoginController())->check();
            $uid['user_id']             = self::ELEMENTARY_USER;
            $uid['user_trust_level']    = self::ELEMENTARY_USER;
            $uid['user_template']       = Config::get('general.template');
            
            Translate::setLang(Config::get('general.lang'));
            
        }
        
        // Сайт отключен, кроме Tl5 (The site is disabled, except Tl5)
        if (FALSE && $uid['user_trust_level'] != self::USER_LEVEL_ADMIN) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/site_off.php';
            hl_preliminary_exit();
        }

        return $uid;
    }
}
