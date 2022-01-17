<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\FacetModel;
use Translate, Tpl;

class AllFacetController extends MainController
{
    private $user;

    protected $limit = 40;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = FacetModel::getFacetsAllCount($this->user['id'], $sheet);
        $facets     = FacetModel::getFacetsAll($page, $this->limit, $this->user['id'], $sheet);

        $num = ' ';
        if ($page > 1) {
            $num = sprintf(Translate::get('page-number'), $page);
        }

        $Flimit = (new \App\Controllers\Facets\AddFacetController())->limitFacer($type, 'no.redirect');

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName($sheet),
        ];

        return Tpl::agRender(
            '/facets/all',
            [
                'meta'  => meta($m, Translate::get($sheet) . $num, Translate::get($sheet . '.desc') . $num),
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'facets'        => $facets,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'limit'         => $Flimit,
                ]
            ]
        );
    }
}
