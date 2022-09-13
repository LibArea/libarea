<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{FacetModel, SubscriptionModel};
use Meta, Content;

class ReadController extends Controller
{
    protected $limit = 20;

    public function index()
    {
        $slug   = Request::get('slug');
        $facet  = FacetModel::getFacet($slug, 'slug', 'blog');
        self::error404($facet);

        $read = FacetModel::getFocusUsers($facet['facet_id'], $this->pageNumber, $this->limit);
        $pagesCount = FacetModel::getFocusUsersCount($facet['facet_id']);

        // Запретим индексацию, тут нет ценной информации
        Request::getHead()->addMeta('robots', 'noindex');

        return $this->render(
            '/facets/read',
            [
                'meta'  => Meta::get(__('app.read') . ' | ' . $facet['facet_title']),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'type'          => 'read',
                    'facet'         => $facet,
                    'read'          => $read,
                    'info'          => Content::text($facet['facet_info'] ?? false, 'text'),
                    'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], 'facet'),
                ]
            ]
        );
    }
}
