<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Content, Base, Translate;

class WelcomeController extends MainController
{
    public function index()
    {
        $meta = meta($m = [], Translate::get('welcome'));
        $data = [
            'content'   => Content::text(Translate::get('welcome-text'), 'text'),
        ];

        return view('/welcome/index', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
