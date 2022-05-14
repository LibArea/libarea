<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\AuthModel;
use Validation;

class SessionController extends Controller
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

        Validation::ComeBack('msg.account_verified', 'success', '/');
    }
}
