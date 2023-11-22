<?php

namespace App\Controllers\Item;

use App\Controllers\Controller;
use App\Models\Item\{WebModel, UserAreaModel};
use UserData, Meta;

class HomeController extends Controller
{
    public function index()
    {
        return $this->render(
            '/item/home',
            [
                'meta'  => self::metadata(),
                'data'  => [
                    'sheet'             => 'main',
                    'items'             => WebModel::feedItem(false, false, $this->pageNumber, 'main', false),
                    'user_count_site'   => UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount(),
                    'audit_count'       => UserAreaModel::auditCount(),
                ]
            ],
            'item',
        );
    }

    public static function metadata()
    {
        return Meta::get(
            __('web.main_title'),
            __('web.main_desc'),
            [
                'og'         => true,
                'imgurl'     => config('meta.img_path_web'),
                'url'        => url('main'),
            ]
        );
    }
}
