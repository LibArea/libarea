<?php

namespace App\Controllers\Item;

use App\Controllers\Controller;
use App\Models\Item\{WebModel, UserAreaModel};
use UserData, Meta;

class HomeController extends Controller
{
    protected $limit = 15;
    protected $first_page = 1;

    public function index()
    {
        $m = [
            'og'         => true,
            'imgurl'     => config('meta.img_path_web'),
            'url'        => url('main'),
        ];

        return $this->render(
            '/item/home',
            'item',
            [
                'meta'  => Meta::get(__('web.main_title'), __('web.main_desc'), $m),
                'data'  => [
                    'items'             => WebModel::feedItem($this->first_page, $this->limit, false, $this->user, false, 'main', false),
                    'user_count_site'   => UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount($this->user['id']),
                    'audit_count'       => UserAreaModel::auditCount(),
                ]
            ]
        );
    }
}
