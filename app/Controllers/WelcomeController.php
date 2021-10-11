<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Content, Base;

class WelcomeController extends MainController
{
    public function index()
    {
        $meta = meta($m = [], lang('welcome'));
        $data = [
            'content'       => Content::text(lang('welcome-text'), 'text'),
        ];

        return view('/welcome/index', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
