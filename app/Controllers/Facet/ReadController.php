<?php

declare(strict_types=1);

namespace App\Controllers\Facet;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\FacetPresence;
use App\Models\{FacetModel, SubscriptionModel};
use Meta, Html;

class ReadController extends Controller
{
    protected int $limit = 20;

    /**
     * "Read" page in blogs
     * Страница "Читают" в блогах
     *
     * @return void
     */
    public function index()
    {
        $facet  = FacetPresence::index(Request::param('slug')->asString(), 'slug', 'blog');

        $read = FacetModel::getFocusUsers($facet['facet_id'], Html::pageNumber(), $this->limit);
        $pagesCount = FacetModel::getFocusUsersCount($facet['facet_id']);

        render(
            '/facets/read',
            [
                'meta'  => Meta::get(__('app.read') . ' | ' . $facet['facet_title']),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => Html::pageNumber(),
                    'type'          => 'read',
                    'facet'         => $facet,
                    'read'          => $read,
                    'info'          => markdown($facet['facet_info'] ?? '', 'text'),
                    'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], 'facet'),
                ]
            ]
        );
    }
}
