<?php

namespace Modules\Catalog\App;

use Modules\Catalog\App\Models\{WebModel, UserAreaModel};
use UserData, Meta;

class Home
{
    private $user;

    protected $limit = 15;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet)
    {
        $m = [
            'og'         => true,
            'imgurl'     => config('meta.img_path_web'),
            'url'        => url($sheet),
        ];

        $count_site = UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        return view(
            '/view/default/home',
            [
                'meta'  => Meta::get(__('web.' . $sheet . '_title'), __('web.' . $sheet . '_desc'), $m),
                'data'  => [
                    'screening'         => 'all',
                    'items'             => WebModel::getItemsAll(1, $this->limit, $this->user, $sheet),
                    'user_count_site'   => $count_site,
                    'sheet'             => $sheet == 'main' ? 'new_sites' : $sheet,
                    'audit_count'       => UserAreaModel::auditCount(),
                ]
            ]
        );
    }
}
