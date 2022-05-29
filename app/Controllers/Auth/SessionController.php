<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\AuthModel;
use Validation;

class SessionController extends MainController
{
    public static function set($user)
    {
        $_SESSION['account'] = ['id' => (int) $user['id']];

        return true;
    }

    public static function annul($uid)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        session_destroy();

        AuthModel::deleteTokenByUserId($uid);

        Validation::comingBack('msg.account_verified', 'success', '/');
    }
}
