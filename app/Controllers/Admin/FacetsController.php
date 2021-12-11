<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\FacetModel;
use Base, Translate;

class FacetsController extends MainController
{
    public function index($sheet, $type)
    {
       //  print_r($sheet);  
        
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = FacetModel::getFacetsAllCount($uid['user_id'], $sheet);
        
        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return view(
            '/admin/facet/facets',
            [
                'meta'  => meta($m = [], Translate::get('topics')),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'facets'        => FacetModel::getFacetsAll($page, $limit, $uid['user_id'], $sheet),
                ]
            ]
        );
    }

    // Удалим Фасет
    public function deletes()
    {
        $id = Request::getPostInt('id');

        $topic = FacetModel::getFacet($id, 'id');
        FacetModel::ban($id, $topic['facet_is_deleted']);

        return true;
    }
}
