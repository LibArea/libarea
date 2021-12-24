<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\FacetModel;
use Base, Translate;

class FacetsController extends MainController
{
    private $uid;

    protected $limit = 25;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = FacetModel::getFacetsAllCount($this->uid['user_id'], $sheet);

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return agRender(
            '/admin/facet/facets',
            [
                'meta'  => meta($m = [], Translate::get('topics')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'facets'        => FacetModel::getFacetsAll($page, $this->limit, $this->uid['user_id'], $sheet),
                ]
            ]
        );
    }
    
    public function pages($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = 0;

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        $pages =  (new \App\Controllers\PageController())->lastAll();   

        return agRender(
            '/admin/page/pages',
            [
                'meta'  => meta($m = [], Translate::get('topics')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'pages'         => $pages,
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
