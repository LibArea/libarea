<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\FacetModel;
use Translate, Tpl;

class FacetsController extends MainController
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = FacetModel::getFacetsAllCount($this->user['id'], $sheet);

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return Tpl::agRender(
            '/admin/facet/facets',
            [
                'meta'  => meta($m = [], Translate::get('topics')),
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'facets'        => FacetModel::getFacetsAll($page, $this->limit, $this->user['id'], $sheet),
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

        return Tpl::agRender(
            '/admin/page/pages',
            [
                'meta'  => meta($m = [], Translate::get('topics')),
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
