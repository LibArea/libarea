<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Admin\App\Models\{UserModel, StatsModel};
use App\Models\FacetModel;
use Translate;

class Home
{
    public function index()
    {
        $size   = disk_total_space(HLEB_GLOBAL_DIRECTORY);
        $bytes  = number_format($size / 1048576, 2) . ' MB';

        return view(
            '/view/default/index',
            [
                'meta'  => meta($m = [], Translate::get('admin')),
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

    public function css()
    {
        Request::getResources()->addBottomStyles('/assets/css/color-help.css');

        $bg_file = HLEB_GLOBAL_DIRECTORY . '/public/assets/css/color-help.css';
        $bg_array = file_get_contents($bg_file);
        preg_match_all('/\.([\w\d\.-]+)[^{}]*{[^}]*}/', $bg_array, $matches);

        return view(
            '/view/default/css',
            [
                'meta'  => meta($m = [], Translate::get('admin')),
                'data'  => [
                    'type'  => 'Css',
                    'sheet' => 'Css',
                    'bg'    => $matches[1],
                ]
            ]
        );
    }
}
