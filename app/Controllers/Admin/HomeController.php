<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\FacetModel;
use App\Models\Admin\{UserModel, StatsModel};
use Base, Translate;

class HomeController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    public function index()
    {
        $size   = disk_total_space(HLEB_GLOBAL_DIRECTORY);
        $bytes  = number_format($size / 1048576, 2) . ' MB';

        return agRender(
            '/admin/index',
            [
                'meta'  => meta($m = [], Translate::get('admin')),
                'uid'   => $this->uid,
                'data'  => [
                    'count'             => StatsModel::getCount(),
                    'posts_no_topic'    => FacetModel::getNoTopic(),
                    'users_count'       => UserModel::getUsersCount('all'),
                    'last_visit'        => UserModel::getLastVisit(),
                    'bytes'             => $bytes,
                    'type'              => 'admin',
                    'sheet'             => 'admin',
                ]
            ]
        );
    }
}
