<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Content, Base, Translate, Config;

class InfoController extends MainController
{
    static protected $path  = HLEB_GLOBAL_DIRECTORY . '/resources/views/';

    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Далее методы по названию страниц
    public function index()
    {
        $text = file_get_contents(static::$path . Config::get('general.template') . '/content/info/md/index.md');

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('info'),
        ];

        return view(
            '/info/index',
            [
                'meta'  => meta($m, Translate::get('info'), Translate::get('info-desc')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'     => 'info',
                    'content'   => Content::text($text, 'text'),
                ]
            ]
        );
    }

    public function privacy()
    {
        $text = file_get_contents(static::$path . Config::get('general.template') . '/content/info/md/privacy.md');

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('info.privacy'),
        ];

        return view(
            '/info/privacy',
            [
                'meta'  => meta($m, Translate::get('privacy policy'), Translate::get('privacy-desc')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'     => 'privacy',
                    'content'   => Content::text($text, 'text'),
                ]
            ]
        );
    }

    public function restriction()
    {
        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('info.restriction'),
        ];

        return view(
            '/info/restriction',
            [
                'meta'  => meta($m, Translate::get('restriction'), Translate::get('the profile is being checked')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'  => 'restriction',
                ]
            ]
        );
    }
    
}
