<?php

namespace App\Middleware\Before;

use Hleb\Scheme\App\Middleware\MainMiddleware;
use Access;

class Restrictions extends MainMiddleware
{
    /**
     * Check for limits and general freezing of the participant (silent mode)
     *
     * Проверим на лимиты и общую заморозку участника (немой режим)
     */
    function index()
    {
        Access::limitForMiddleware();
    }

}
