<?php

namespace App\Bootstrap\Services;

use App\Bootstrap\Services\Auth\Action;

class AuthService implements AuthInterface
{
    public function logout(): void
    {
        Action::logout();
    }

    public function get(): array
    {
        throw new \LogicException('The get() method is not used in this implementation, which will be fixed in the future.');
    }
}
