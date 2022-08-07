<?php

namespace App\Controllers\Item;

use App\Controllers\Controller;
use App\Models\Item\{WebModel, UserAreaModel};
use UserData, Meta;

class HomeController extends Controller
{
    protected $limit = 15;

    public function index($sheet)
    {
        $m = [
            'og'         => true,
            'imgurl'     => config('meta.img_path_web'),
            'url'        => url($sheet),
        ];

        $count_site = UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        return $this->render(
            '/item/home',
            'item',
            [
                'meta'  => Meta::get(__('web.' . $sheet . '_title'), __('web.' . $sheet . '_desc'), $m),
                'data'  => [
                    'screening'         => 'all',
                    'items'             => WebModel::feedItem(1, $this->limit, false, $this->user, false, $sheet, false),
                    'user_count_site'   => $count_site,
                    'sheet'             => $sheet == 'main' ? 'new_sites' : $sheet,
                    'audit_count'       => UserAreaModel::auditCount(),
                ]
            ]
        );
    }
}
