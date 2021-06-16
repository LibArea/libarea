<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\ExploreModel;
use Lori\Config;
use Lori\Base;

class ExploreController extends \MainController
{
    public function index()
    {
        Request::getHead()->addStyles('/assets/css/explore.css');
        Request::getHead()->addScript('/assets/js/Chart.js');
        
        $uid    = Base::getUid();
        $data = [
            'h1'            => lang('Explore'),
            'meta_title'    => lang('explore-title') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('explore-desc') .' '. Config::get(Config::PARAM_HOME_TITLE),
            'flow_num'      => ExploreModel::getGraf(),
            'stats'         => ExploreModel::getStats(),
            'sheet'         => 'explore',
            'canonical'     => Config::get(Config::PARAM_URL) . '/explore',
        ];

        return view(PR_VIEW_DIR . '/explore/index', ['data' => $data, 'uid' => $uid]);
    }

}
