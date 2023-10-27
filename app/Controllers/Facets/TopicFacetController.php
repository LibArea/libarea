<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\FacetPresence;
use App\Services\Meta\Facet;
use App\Models\{FeedModel, SubscriptionModel, FacetModel, PostModel};

class TopicFacetController extends Controller
{
    protected $limit = 25;

    // Posts in the topic 
    // Посты по теме
    public function index($sheet)
    {
        $facet  = FacetPresence::index(Request::get('slug'), 'slug', 'topic');

        if ($facet['facet_type'] == 'blog' || $facet['facet_type'] == 'section') {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, $sheet, $facet['facet_slug']);
        $pagesCount = FeedModel::feedCount($sheet, $facet['facet_slug']);

        return $this->render(
            '/facets/topic',
            [
                'meta'  => Facet::metadata($sheet, $facet),
                'data'  => array_merge(
                    $this->sidebar($facet),
                    [
                        'pagesCount'    => ceil($pagesCount / $this->limit),
                        'pNum'          => $this->pageNumber,
                        'posts'         => $posts,
                        'sheet'         => $sheet,
                        'type'          => 'topic',
                    ]
                ),
                'facet'   => ['facet_id' => $facet['facet_id'], 'facet_type' => $facet['facet_type'], 'facet_user_id' => $facet['facet_user_id']],
            ]
        );
    }

    // Information on the topic 
    // Информация по теме
    public function info()
    {
        $facet  = FacetPresence::index(Request::get('slug'), 'slug', 'topic');

        return $this->render(
            '/facets/info',
            [
                'meta'  => Facet::metadata('info', $facet),
                'data'  => array_merge(
                    $this->sidebar($facet),
                    [
                        'sheet' => 'info',
                        'type'  => 'info',
                    ]
                ),
            ]
        );
    }

    // Users who have contributed 
    // Пользователи внесшие вклад...
    public function writers()
    {
        $facet  = FacetPresence::index(Request::get('slug'), 'slug', 'topic');

        return $this->render(
            '/facets/writers',
            [
                'meta'  => Facet::metadata('writers', $facet),
                'data'  => array_merge(
                    $this->sidebar($facet),
                    [
                        'sheet' => 'writers',
                        'type'  => 'writers',
                    ]
                ),
            ]
        );
    }

    public function sidebar($facet)
    {
        return [
            'facet'         => $facet,
            'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], 'facet'),
            'related_posts' => PostModel::postRelated($facet['facet_post_related'] ?? null),
            'high_topics'   => FacetModel::getHighLevelList($facet['facet_id']),
            'writers'       => FacetModel::getWriters($facet['facet_id'], 15),
            'low_topics'    => FacetModel::getLowLevelList($facet['facet_id']),
            'low_matching'  => FacetModel::getLowMatching($facet['facet_id']),
        ];
    }
}
