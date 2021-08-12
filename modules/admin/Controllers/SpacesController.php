<?php

namespace Modules\Admin\Controllers;

use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\SpaceModel;
use Lori\Base;

class SpacesController extends \MainController
{
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 25;
        $pagesCount = SpaceModel::getSpacesCount($sheet);
        $spaces     = SpaceModel::getSpaces($page, $limit, $sheet);

        $data = [
            'meta_title'    => lang('Spaces'),
            'sheet'         => $sheet == 'all' ? 'spaces' : 'spaces-ban',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
        ];

        return view('/templates/spaces', ['data' => $data, 'uid' => $uid, 'spaces' => $spaces]);
    }

    // Удаление / восстановление пространства
    public function delSpace()
    {
        $space_id   = \Request::getPostInt('id');

        SpaceModel::SpaceDelete($space_id);

        return true;
    }
}
