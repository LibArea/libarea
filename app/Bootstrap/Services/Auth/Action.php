<?php

declare(strict_types=1);

namespace App\Bootstrap\Services\Auth;

use App\Models\Auth\AuthModel;
use Msg;

class Action
{
    public static function set(int $user_id): bool
    {
        $_SESSION['account'] = ['id' => (int) $user_id];

        return true;
    }

    public static function annul(int $user_id): void
    {
        self::logout();

        AuthModel::deleteTokenByUserId($user_id);

        Msg::redirect(__('msg.account_verified'), 'success', '/');
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE)
            session_destroy();

        setcookie("remember", "", time() - 3600, "/", httponly: true);

        redirect('/');
    }
}
