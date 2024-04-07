<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use Hleb\Base\Controller;

class LogoutController extends Controller
{
    /**
     * Log out of the system
     * Выход из системы
     *
     * @return void
     */
    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE)
            session_destroy();

        setcookie("remember", "", time() - 3600, "/", httponly: true);

        redirect('/');
    }
}
