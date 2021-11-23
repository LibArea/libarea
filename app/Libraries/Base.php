<?php

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;

class Base
{
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
            $uid['user_id']             = 0;
            $uid['user_trust_level']    = 0;
        }
        
        // Сайт отключен, кроме Tl5 (The site is disabled, except Tl5)
        if (FALSE && $uid['user_trust_level'] != 5) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/site_off.php';
            hl_preliminary_exit();
        }

        return $uid;
    }
}
