<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\FacetModel;
use Tpl, Meta, UserData;

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
            'url'   => url($sheet),
        ];

        return Tpl::LaRender(
            '/facets/all',
            [
                'meta'  => Meta::get(__('meta.' . $sheet), __('meta.' . $sheet . '_desc'), $m),
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
