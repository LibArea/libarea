<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Base\Module;
use Hleb\Constructor\Data\View;
use Modules\Admin\Models\{StatsModel, FacetModel, UserModel, SearchModel};
use Meta;

class AdminController extends Module
{
    public function index(): View
    {
        return view(
            'index',
            [
                'meta'  => Meta::get(__('admin.home')),
                'data'  => [
                    'count'             => StatsModel::getCount(),
                    'posts_no_topic'    => FacetModel::getNoTopic(),
                    'users_count'       => UserModel::getUsersCount('all'),
                    'last_visit'        => UserModel::getLastVisit(),
                    'logs'              => SearchModel::getSearchLogs(10),
                    'bytes'             => self::freeDiskSpace(),
                    'type'              => 'admin',
                    'sheet'             => 'admin',
                ]
            ]
        );
    }

    public static function freeDiskSpace(): string
    {
        $size   = disk_total_space(HLEB_GLOBAL_DIR);
        return number_format($size / 1048576, 2) . ' MB';
    }
}
