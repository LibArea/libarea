<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\Admin\BanTopicModel;
use App\Models\FacetModel;
use Base, Translate;

class TopicsController extends MainController
{
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = FacetModel::getFacetsAllCount($uid['user_id'], $sheet);

        return view(
            '/admin/topic/topics',
            [
                'meta'  => meta($m = [], Translate::get('topics')),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => $sheet == 'all' ? 'topics' : $sheet,
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'topics'        => FacetModel::getFacetsAll($page, $limit, $uid['user_id'], $sheet),
                ]
            ]
        );
    }

    // Удалим Фасет
    public function deletes()
    {
        $id = Request::getPostInt('id');

        $topic = FacetModel::getFacet($id, 'id');
        BanTopicModel::setBan($id, $topic['facet_is_deleted']);

        return true;
    }
}
