<?php

declare(strict_types=1);

namespace App\Controllers\Facet;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\FacetPresence;
use App\Models\{FeedModel, SubscriptionModel, FacetModel, PostModel};
use Html, Meta;

class TopicFacetController extends Controller
{
    protected int $limit = 25;

    public function recommend(): void
    {
        $this->callIndex('recommend');
    }

    public function questions(): void
    {
        $this->callIndex('questions');
    }

    public function top(): void
    {
        $this->callIndex('top');
    }

    public function posts(): void
    {
        $this->callIndex('posts');
    }

    public function feed(): void
    {
        $this->callIndex('facet.feed');
    }

    /**
     * Posts in the topic 
     * Посты по теме
     *
     * @param [type] $sheet
     * @return void
     */
    public function callIndex($sheet)
    {
        $facet  = $this->presence();

        if ($facet['facet_type'] == 'blog' || $facet['facet_type'] == 'section') {
            echo view('error', ['httpCode' => 404, 'message' => __('404.page_not') . ' <br> ' . __('404.page_removed')]);
            exit();
        }

        $posts      = FeedModel::feed(Html::pageNumber(), $this->limit, $sheet, $facet['facet_slug']);
        $pagesCount = FeedModel::feedCount($sheet, $facet['facet_slug']);

        render(
            '/facets/topic',
            [
                'meta'  => Meta::facet($sheet, $facet),
                'data'  => array_merge(
                    $this->sidebar(),
                    [
                        'pagesCount'    => ceil($pagesCount / $this->limit),
                        'pNum'          => Html::pageNumber(),
                        'posts'         => $posts,
                        'sheet'         => $sheet,
                        'type'          => 'topic',
                    ]
                ),
                'facet'   => ['facet_id' => $facet['facet_id'], 'facet_type' => $facet['facet_type'], 'facet_user_id' => $facet['facet_user_id']],
            ]
        );
    }

    /**
     * Information on the topic 
     * Информация по теме
     *
     * @return void
     */
    public function info()
    {
        render(
            '/facets/info',
            [
                'meta'  => Meta::facet('info', $this->presence()),
                'data'  => array_merge(
                    $this->sidebar(),
                    [
                        'sheet' => 'info',
                        'type'  => 'info',
                    ]
                ),
            ]
        );
    }

    /**
     * Users who have contributed 
     * Пользователи внесшие вклад...
     *
     * @return void
     */
    public function writers()
    {
        render(
            '/facets/writers',
            [
                'meta'  => Meta::facet('writers', $this->presence()),
                'data'  => array_merge(
                    $this->sidebar(),
                    [
                        'sheet' => 'writers',
                        'type'  => 'writers',
                    ]
                ),
            ]
        );
    }

    public function sidebar()
    {
        $facet = $this->presence();

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

    public function presence(): array
    {
        return FacetPresence::index(Request::param('slug')->asString(), 'slug', 'topic');
    }
}
