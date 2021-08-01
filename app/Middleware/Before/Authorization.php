<?php

namespace App\Middleware\Before;

use Hleb\Constructor\Handlers\Request;

class Authorization extends \MainMiddleware
{
    public function noAuth()
    {
        if (!Request::getSession('account')) {
            redirect('/');
        }
    }

    public function yesAuth()
    {
        if (Request::getSession('account')) {
            redirect('/');
        }
    }

    public function admin()
    {
        $account = Request::getSession('account');
        if ($account['trust_level'] != 5) {
            redirect('/');
        }
    }
}
