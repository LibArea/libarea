<?php

namespace App\Controllers;

use Lori\{Content, Config, Base};

class InfoController extends \MainController
{
    // Далее методы по названию страниц
    public function index()
    {
        $text = file_get_contents(TEMPLATE_DIR . '/info/md/index.md');

        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Info'),
            'content'       => Content::text($text, 'text'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/info',
            'sheet'         => 'info',
            'meta_title'    => lang('Info') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('info-desc') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/info/index', ['data' => $data, 'uid' => $uid]);
    }

    public function privacy()
    {
        $text = file_get_contents(TEMPLATE_DIR . '/info/md/privacy.md');

        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Privacy Policy'),
            'content'       => Content::text($text, 'text'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/info/privacy',
            'sheet'         => 'privacy',
            'meta_title'    => lang('Privacy Policy') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('privacy-desc') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/info/privacy', ['data' => $data, 'uid' => $uid]);
    }

    public function restriction()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Restriction'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/info/restriction',
            'sheet'         => 'info-restriction',
            'meta_title'    => lang('Restriction') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('Restriction') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/info/restriction', ['data' => $data, 'uid' => $uid]);
    }
}
