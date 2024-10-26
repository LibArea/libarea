<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Base\Module;
use Hleb\Constructor\Data\View;
use Meta;

class CssController extends Module
{
    public function index(): View
    {
        return view(
            'css',
            [
                'meta'  => Meta::get(__('admin.css')),
                'data'  => [
                    'type'  => 'css',
                    'sheet' => 'css',
                    'lists' => self::iconList()
                ]
            ]
        );
    }

    public static function iconList(): array
    {
        $iconList = [];

        $sprite = file_get_contents(HLEB_PUBLIC_DIR . config('main', 'svg_path'));

        $result = preg_match_all("/<symbol[^>]*id=\"([-_a-z0-9]+)\"[^>]*>/i", $sprite, $matches);

        if ($result > 0) {
            $iconList = $matches[1];
        }

        return $iconList;
    }
}
