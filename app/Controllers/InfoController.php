<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Lori\{Content, Config, Base};

class InfoController extends MainController
{
    // Далее методы по названию страниц
    public function index()
    {
        $text = file_get_contents(TEMPLATE_DIR . '/info/md/index.md');

        $uid  = Base::getUid();
        $meta = [
            'sheet'         => 'info',
            'canonical'     => Config::get(Config::PARAM_URL) . '/info',
            'meta_title'    => lang('Info') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('info-desc') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'content'       => Content::text($text, 'text'),
        ];

        return view('/info/index', ['data' => $data, 'meta' => $meta, 'uid' => $uid]);
    }

    public function privacy()
    {
        $text = file_get_contents(TEMPLATE_DIR . '/info/md/privacy.md');

        $uid  = Base::getUid();
        $meta = [
            'content'       => Content::text($text, 'text'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/info/privacy',
            'sheet'         => 'privacy',
            'meta_title'    => lang('Privacy Policy') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('privacy-desc') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'content'       => Content::text($text, 'text'),
        ];

        return view('/info/privacy', ['data' => $data, 'meta' => $meta, 'uid' => $uid]);
    }

    public function restriction()
    {
        $uid  = Base::getUid();
        $meta = [
            'canonical'     => Config::get(Config::PARAM_URL) . '/info/restriction',
            'sheet'         => 'info-restriction',
            'meta_title'    => lang('Restriction') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('Restriction') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view('/info/restriction', ['meta' => $meta, 'uid' => $uid]);
    }
}
