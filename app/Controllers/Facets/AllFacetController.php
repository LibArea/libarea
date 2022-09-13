<?php

namespace App\Controllers\Facets;

use App\Controllers\Controller;
use App\Models\FacetModel;
use Meta;

class AllFacetController extends Controller
{
    protected $limit = 40;

    public function index($sheet, $type)
    {
        $pagesCount = FacetModel::getFacetsAllCount($this->user['id'], $sheet, $type);
        $facets     = FacetModel::getFacetsAll($this->pageNumber, $this->limit, $this->user['id'], $sheet, $type);

        $m = [
            'og'    => true,
            'url'   => url($type . 's.' . $sheet),
        ];

        $title = __('meta.' . $sheet . '_' . $type . 's');
        $desc = __('meta.' . $sheet . '_' . $type . 's_desc');

        return $this->render(
            '/facets/all',
            [
                'meta'  => Meta::get($title, $desc, $m),
                'data'  => [
                    'sheet'             => $sheet,
                    'type'              => $type,
                    'facets'            => $facets,
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $this->pageNumber,
                    'countUserFacet'    => FacetModel::countFacetsUser($this->user['id'], $type)
                ]
            ]
        );
    }

    public static function types()
    {
        return FacetModel::types();
    }
}
