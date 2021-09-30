<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\Admin\SpaceModel;
use Agouti\Base;

class SpacesController extends MainController
{
    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit      = 25;
        $pagesCount = SpaceModel::getSpacesCount($sheet);
        $spaces     = SpaceModel::getSpaces($page, $limit, $sheet);

        $meta = [
            'meta_title'    => lang('spaces'),
            'sheet'         => 'spaces',
        ];

        Request::getResources()->addBottomScript('/assets/js/admin.js');
        $data = [
            'sheet'         => $sheet == 'all' ? 'spaces' : 'spaces-ban',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'spaces'        => $spaces,
        ];

        return view('/admin/space/spaces', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    // Удаление / восстановление пространства
    public function delSpace()
    {
        $space_id   = Request::getPostInt('id');

        SpaceModel::SpaceDelete($space_id);

        return true;
    }
}
