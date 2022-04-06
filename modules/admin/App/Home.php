<?php

namespace Modules\Admin\App;

use Modules\Admin\App\Models\{UserModel, StatsModel, FacetModel};
use Translate, Meta;

class Home
{
    public function index()
    {
        $size   = disk_total_space(HLEB_GLOBAL_DIRECTORY);
        $bytes  = number_format($size / 1048576, 2) . ' MB';

        return view(
            '/view/default/index',
            [
                'meta'  => Meta::get(Translate::get('admin')),
                'data'  => [
                    'count'             => StatsModel::getCount(),
                    'posts_no_topic'    => FacetModel::getNoTopic(),
                    'users_count'       => UserModel::getUsersCount('all'),
                    'last_visit'        => UserModel::getLastVisit(),
                    'logs'              => (new \Modules\Search\App\Search())->getLogs(10),
                    'replys'            => StatsModel::getReplys(10),
                    'bytes'             => $bytes,
                    'type'              => 'admin',
                    'sheet'             => 'admin',
                ]
            ]
        );
    }

    public function css()
    {
        return view(
            '/view/default/css',
            [
                'meta'  => Meta::get(Translate::get('Css')),
                'data'  => [
                    'type'  => 'css',
                    'sheet' => 'css',
                ]
            ]
        );
    }
}
