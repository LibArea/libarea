<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\FacetModel;
use Translate, Tpl, Meta, UserData;

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
        $pageNumber = Tpl::pageNumber();

        $pagesCount = FacetModel::getFacetsAllCount($this->user['id'], $sheet);
        $facets     = FacetModel::getFacetsAll($pageNumber, $this->limit, $this->user['id'], $sheet);

        $Flimit = (new \App\Controllers\Facets\AddFacetController())->limitFacer($type, 'no.redirect');

        $m = [
            'og'    => true,
            'url'   => getUrlByName($sheet),
        ];

        return Tpl::agRender(
            '/facets/all',
            [
                'meta'  => Meta::get(Translate::get($sheet), Translate::get($sheet . '.desc'), $m),
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'facets'        => $facets,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'limit'         => $Flimit,
                ]
            ]
        );
    }

    public static function types()
    {
        return FacetModel::types();
    }
}
