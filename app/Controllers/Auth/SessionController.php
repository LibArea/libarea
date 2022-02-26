<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\AuthModel;

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

        addMsg('account.being.verified', 'success');
        redirect('/');
    }
}
