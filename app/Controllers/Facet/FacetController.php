<?php

declare(strict_types=1);

namespace App\Controllers\Facet;

use Hleb\Base\Controller;
use App\Models\FacetModel;
use Meta, Html;

class FacetController extends Controller
{
    protected int $limit = 40;

    public function blogAll(): void
    {
        $this->callIndex('all', 'blogs');
    }

    public function topicAll(): void
    {
        $this->callIndex('all', 'topics');
    }

    public function blogNew(): void
    {
        $this->callIndex('new', 'blogs');
    }

    public function topicNew(): void
    {
        $this->callIndex('new', 'topics');
    }

    public function blogMy(): void
    {
        $this->callIndex('my', 'blogs');
    }

    public function  topicMy(): void
    {
        $this->callIndex('my', 'topics');
    }

    public function callIndex($sheet, $type)
    {
        $pagesCount = FacetModel::getFacetsAllCount($sheet, $type);
        $facets     = FacetModel::getFacetsAll(Html::pageNumber(), $this->limit, $sheet, $type);

        $m = [
            'og'    => true,
            'url'   => url($type . '.' . $sheet),
        ];

        $title = __('meta.' . $sheet . '_' . $type);
        $desc = __('meta.' . $sheet . '_' . $type . '_desc');

        render(
            '/facets/all',
            [
                'meta'  => Meta::get($title, $desc, $m),
                'data'  => [
                    'sheet'             => $sheet,
                    'type'              => $type,
                    'facets'            => $facets,
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => Html::pageNumber(),
                    'countUserFacet'    => FacetModel::countFacetsUser($this->container->user()->id(), $type)
                ]
            ]
        );
    }

    public static function types()
    {
        return FacetModel::types();
    }
}
