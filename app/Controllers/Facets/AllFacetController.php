<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\FacetModel;
use Base, Translate;

class AllFacetController extends MainController
{
    private $uid;

    protected $limit = 40;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    public function index($sheet, $type)
    {   
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = FacetModel::getFacetsAllCount($this->uid['user_id'], $sheet);
        $facets     = FacetModel::getFacetsAll($page, $this->limit, $this->uid['user_id'], $sheet);

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

        return agRender(
            '/facets/all',
            [
                'meta'  => meta($m, Translate::get($sheet) . $num, Translate::get($sheet . '.desc') . $num),
                'uid'   => $this->uid,
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
