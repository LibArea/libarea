<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\AuthModel;
use Session;

class SessionController extends MainController
{
    public static function set($user_id)
    {
        $_SESSION['account'] = ['id' => (int) $user_id];

        return true;
    }

    public static function annul($user_id)
    {
        Session::logout();

        AuthModel::deleteTokenByUserId($user_id);

        is_return(__('msg.account_verified'), 'success', '/');
    }
}
