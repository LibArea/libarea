<?php

namespace App\Middleware\Before;

use Hleb\Scheme\App\Middleware\MainMiddleware;
use Access, UserData, Msg;

class Restrictions extends MainMiddleware
{
    function index()
    {
        if (UserData::checkAdmin()) {
            return;
        }

        // Check for silent mode
        // Проверим на немой режим
        if (UserData::getLimitingMode() == UserData::MUTE_MODE_USER) {
            Msg::add(__('msg.silent_mode',), 'error');
            redirect('/');
        }
        
        Access::limitForMiddleware();
    }
}
