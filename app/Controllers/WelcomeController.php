<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Lori\{Content, Config, Base};

class WelcomeController extends MainController
{
    public function index()
    {
        $meta = [
            'sheet'         => 'welcome',
            'meta_title'    => lang('Welcome') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        $data = [
            'content'       => Content::text(lang('welcome-text'), 'text'),
        ];

        return view('/welcome/index', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
