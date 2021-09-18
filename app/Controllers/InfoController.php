<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Agouti\{Content, Config, Base};

class InfoController extends MainController
{
    // Далее методы по названию страниц
    public function index()
    {
        $text = file_get_contents(HLEB_GLOBAL_DIRECTORY . '/resources/views/' . PR_VIEW_DIR . '/info/md/index.md');
        $meta = [
            'sheet'         => 'info',
            'canonical'     => Config::get(Config::PARAM_URL) . '/info',
            'meta_title'    => lang('Info') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('info-desc') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'sheet'         => 'info',
            'content'       => Content::text($text, 'text'),
        ];

        return view('/info/index', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    public function privacy()
    {
        $text = file_get_contents(HLEB_GLOBAL_DIRECTORY . '/resources/views/' . PR_VIEW_DIR . '/info/md/privacy.md');
        $meta = [
            'content'       => Content::text($text, 'text'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/info/privacy',
            'sheet'         => 'privacy',
            'meta_title'    => lang('Privacy Policy') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('privacy-desc') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'sheet'         => 'privacy',
            'content'       => Content::text($text, 'text'),
        ];

        return view('/info/privacy', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    public function restriction()
    {
        $meta = [
            'canonical'     => Config::get(Config::PARAM_URL) . '/info/restriction',
            'sheet'         => 'restriction',
            'meta_title'    => lang('Restriction') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('Restriction') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'sheet'       => 'restriction',
        ];

        return view('/info/restriction', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
