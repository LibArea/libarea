<?php

declare(strict_types=1);

namespace App\Controllers\Facet;

use Hleb\Base\Controller;
use App\Models\FacetModel;
use Meta, Html;

class FacetController extends Controller
{
    protected $limit = 40;

    public function blogAll(): void
    {
        $this->callIndex('all', 'blog');
    }

    public function topicAll(): void
    {
        $this->callIndex('all', 'topic');
    }

    public function blogNew(): void
    {
        $this->callIndex('new', 'blog');
    }

    public function topicNew(): void
    {
        $this->callIndex('new', 'topic');
    }

    public function blogMy(): void
    {
        $this->callIndex('my', 'blog');
    }

    public function  topicMy(): void
    {
        $this->callIndex('my', 'topic');
    }

    public function callIndex($sheet, $type)
    {
        $pagesCount = FacetModel::getFacetsAllCount($sheet, $type);
        $facets     = FacetModel::getFacetsAll(Html::pageNumber(), $this->limit, $sheet, $type);

        $m = [
            'og'    => true,
            'url'   => url($type . 's.' . $sheet),
        ];

        $title = __('meta.' . $sheet . '_' . $type . 's');
        $desc = __('meta.' . $sheet . '_' . $type . 's_desc');

        return render(
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
