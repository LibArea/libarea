<?php

namespace App\Middleware\Before;

use Hleb\Scheme\App\Middleware\MainMiddleware;

class Designator extends MainMiddleware
{
    function index(int $type = 0, string $compare = '>=')
    {
        (new \UserData())->checkAccordance($type, $compare);
    }
} 
