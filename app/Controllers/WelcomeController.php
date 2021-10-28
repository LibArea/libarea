<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Content, Base, Translate;

class WelcomeController extends MainController
{
    public function index()
    {
        return view(
            '/welcome/index',
            [
                'meta'  => meta($m = [], Translate::get('welcome')),
                'uid'   => Base::getUid(),
                'data'  => [
                    'content'   => Content::text(Translate::get('welcome-text'), 'text'),
                ]
            ]
        );
    }
}
