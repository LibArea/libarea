<?php

namespace Modules\Admin\App;

use Modules\Admin\App\Models\{UserModel, StatsModel, FacetModel};
use App\Models\SearchModel;
use Meta;

class Home
{
    public function index()
    {
        return view(
            '/view/default/index',
            [
                'meta'  => Meta::get(__('admin.home')),
                'data'  => [
                    'count'             => StatsModel::getCount(),
                    'posts_no_topic'    => FacetModel::getNoTopic(),
                    'users_count'       => UserModel::getUsersCount('all'),
                    'last_visit'        => UserModel::getLastVisit(),
                    'logs'              => SearchModel::getSearchLogs(10),
                    'replys'            => StatsModel::getReplys(10),
                    'bytes'             => self::freeDiskSpace(),
                    'type'              => 'admin',
                    'sheet'             => 'admin',
                ]
            ]
        );
    }

    public static function freeDiskSpace()
    {
        $size   = disk_total_space(HLEB_GLOBAL_DIRECTORY);
        return number_format($size / 1048576, 2) . ' MB';
    }
}
