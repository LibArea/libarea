<?php

namespace App\Middleware\Before;

class Designator extends \MainMiddleware
{
    function index(int $type = 0, string $compare = '>=')
    {
        UserData::checkAccordance($type, $compare);
    }
} 
