<?php

namespace App\Middleware\Before;

use Hleb\Scheme\App\Middleware\MainMiddleware;

class UserAuth extends MainMiddleware
{
    function index()
    {
        return (new \UserData)->checkActiveUser();
    }
}
