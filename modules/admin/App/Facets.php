<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Models\FacetModel;
use Translate, UserData;

class Facets
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

        return view(
            '/view/default/facet/facets',
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

        $pages =  (new \App\Controllers\PageController())->lastAll();

        return view(
            '/view/default/page/pages',
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
