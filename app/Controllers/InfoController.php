<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Content, Base;

class InfoController extends MainController
{
    // Далее методы по названию страниц
    public function index()
    {
        $text = file_get_contents(HLEB_GLOBAL_DIRECTORY . '/resources/views/' . PR_VIEW_DIR . '/info/md/index.md');

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('info'),
        ];
        $meta = meta($m, lang('info'), lang('info-desc'));

        $data = [
            'sheet'         => 'info',
            'content'       => Content::text($text, 'text'),
        ];

        return view('/info/index', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    public function privacy()
    {
        $text = file_get_contents(HLEB_GLOBAL_DIRECTORY . '/resources/views/' . PR_VIEW_DIR . '/info/md/privacy.md');

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('info.privacy'),
        ];
        $meta = meta($m, lang('privacy policy'), lang('privacy-desc'));

        $data = [
            'sheet'         => 'privacy',
            'content'       => Content::text($text, 'text'),
        ];

        return view('/info/privacy', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    public function restriction()
    {
        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('info.restriction'),
        ];
        $meta = meta($m, lang('restriction'), lang('the profile is being checked'));

        $data = [
            'sheet'       => 'restriction',
        ];

        return view('/info/restriction', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
