<?php

declare(strict_types=1);

namespace App\Bootstrap\Services\Auth;

use App\Models\User\UserModel;
use App\Bootstrap\Services\Auth\RegType;

class Register
{
    public static function trustLevel()
    {
        // For "launch mode", the first 50 members get trust_level = 2
        // Для "режима запуска" первые 50 участников получают trust_level = 2 
        $trust_level = RegType::USER_FIRST_LEVEL;
        if (UserModel::getUsersAllCount() < 50 && config('general', 'mode') == true) {
            $trust_level = RegType::USER_SECOND_LEVEL;
        }

        return $trust_level;
    }

    public static function activated(int $inv_uid)
    {
        // If there is no invite, then activation
        // Если инвайта нет, то активация
        $activ = $inv_uid > 0 ? 1 : 0;

        // If email verification is disabled at all
        // Если проверка email вообще выключена
        return (config('general', 'mail_check') === true) ? $activ : 1;
    }
}
