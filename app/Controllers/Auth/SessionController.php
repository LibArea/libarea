<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\AuthModel;
use Validation;

class SessionController extends MainController
{
    public static function set($user_id)
    {
        $_SESSION['account'] = ['id' => (int) $user_id];

        return true;
    }

    public static function annul($user_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        session_destroy();

        AuthModel::deleteTokenByUserId($user_id);

        Validation::comingBack(__('msg.account_verified'), 'success', '/');
    }
}
