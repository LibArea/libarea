<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\AuthModel;

class SessionController extends MainController
{
    public static function set($uid)
    {
        $_SESSION['account'] = ['user_id' => (int) $uid['user_id']];

        return true;
    }

    public static function annul($user_id)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        session_destroy();

        AuthModel::deleteTokenByUserId($user_id);

        redirect(getUrlByName('info.restriction'));
    }
}
