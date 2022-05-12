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

        $pagesCount = FacetModel::getFacetsAllCount($this->user['id'], $sheet, $type);
        $facets     = FacetModel::getFacetsAll($pageNumber, $this->limit, $this->user['id'], $sheet, $type);

        $Flimit = (new \App\Controllers\Facets\AddFacetController())->limitFacer($type, 'no.redirect');

        $m = [
            'og'    => true,
            'url'   => url($type . 's.' . $sheet),
        ];

        $title = __('meta.' . $sheet . '_' . $type . 's');
        $desc = __('meta.' . $sheet . '_' . $type . 's_desc');

        return Tpl::LaRender(
            '/facets/all',
            [
                'meta'  => Meta::get($title, $desc, $m),
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
