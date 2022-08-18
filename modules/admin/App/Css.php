<?php

namespace Modules\Admin\App;

use Meta;

class Css
{
    public function index()
    {
        return view(
            '/view/default/css',
            [
                'meta'  => Meta::get(__('admin.css')),
                'data'  => [
                    'type'  => 'css',
                    'sheet' => 'css',
                    'lists' => self::iconList(),
                ]
            ]
        );
    }

    public static function iconList()
    {
        $iconList = [];

        $sprite = file_get_contents(config('assembly-js-css.svg_path'));
        $result = preg_match_all("/<symbol[^>]*id=\"([-_a-z0-9]+)\"[^>]*>/i", $sprite, $matches);

        if ($result > 0) {
            $iconList = $matches[1];
        }

        return $iconList;
    }
}
