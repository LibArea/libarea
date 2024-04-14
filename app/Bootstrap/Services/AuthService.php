<?php

namespace App\Bootstrap\Services;

use App\Bootstrap\Services\Auth\Action;

class AuthService implements AuthInterface
{
    public function logout(): void
    {
        return Action::logout();
    }
}
