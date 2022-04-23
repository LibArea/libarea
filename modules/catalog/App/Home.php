<?php

namespace Modules\Catalog\App;

use Modules\Catalog\App\Models\{WebModel, UserAreaModel};
use Config, UserData, Meta;

class Home
{
    private $user;

    protected $limit = 15;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet, $type)
    {
        $m = [
            'og'         => true,
            'imgurl'     => Config::get('meta.img_path_web'),
            'url'        => getUrlByName($sheet),
        ];

        $count_site = UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        return view(
            '/view/default/home',
            [
                'meta'  => Meta::get(__($sheet . '.home.title'), __($sheet . '.home.desc'), $m),
                'user'  => $this->user,
                'data'  => [
                    'screening'         => 'all',
                    'items'             => WebModel::getItemsAll(1, $this->limit, $this->user, $sheet),
                    'user_count_site'   => $count_site,
                    'type'              => $type,
                    'sheet'             => $sheet,
                    'audit_count'       => UserAreaModel::auditCount(),
                ]
            ]
        );
    }
}
