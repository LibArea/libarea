<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\UserModel;
use App\Models\{FeedModel, SubscriptionModel, FacetModel, PostModel};
use Meta;

class TopicFacetController extends Controller
{
    protected $limit = 25;

    // Posts in the topic 
    // Посты по теме
    public function index($sheet, $type)
    {
        $slug   = Request::get('slug');
        $facet  = FacetModel::getFacet($slug, 'slug', 'topic');
        self::error404($facet);

        if ($facet['facet_type'] == 'blog' || $facet['facet_type'] == 'section') {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, $this->user, $sheet, $facet['facet_slug']);
        $pagesCount = FeedModel::feedCount($this->user, $sheet, $facet['facet_slug']);

        $title  = $facet['facet_seo_title'] . ' — ' .  __('app.topic');
        $description   = $facet['facet_description'];

        if ($sheet == 'recommend') {
            $url    =  url('recommend', ['slug' => $facet['facet_slug']]);
            $title  = $facet['facet_seo_title'] . ' — ' .  __('app.rec_posts');
            $description  = __('app.rec_posts_desc', ['name' => $facet['facet_seo_title']]) . $facet['facet_description'];
        }

        $m = [
            'og'         => true,
            'imgurl'     => PATH_FACETS_LOGOS . $facet['facet_img'],
            'url'        => url('topic', ['slug' => $facet['facet_slug']]),
        ];

        return $this->render(
            '/facets/topic',
            'base',
            [
                'meta'  => Meta::get($title, $description, $m),
                'data'  => array_merge(
                    $this->sidebar($facet),
                    [
                        'pagesCount'    => ceil($pagesCount / $this->limit),
                        'pNum'          => $this->pageNumber,
                        'posts'         => $posts,
                        'sheet'         => $sheet,
                        'type'          => $type,
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
        $slug   = Request::get('slug');
        $facet  = FacetModel::getFacet($slug, 'slug', 'topic');
        self::error404($facet);

        $m = [
            'og'         => true,
            'imgurl'     => PATH_FACETS_LOGOS . $facet['facet_img'],
            'url'        => url('topic.info', ['slug' => $facet['facet_slug']]),
        ];

        return $this->render(
            '/facets/info',
            'base',
            [
                'meta'  => Meta::get($facet['facet_seo_title'] . ' — ' .  __('app.info'), $facet['facet_description'], $m),
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

    // Information on the topic 
    // Информация по теме
    public function writers()
    {
        $slug   = Request::get('slug');
        $facet  = FacetModel::getFacet($slug, 'slug', 'topic');
        self::error404($facet);

        $m = [
            'og'         => true,
            'imgurl'     => PATH_FACETS_LOGOS . $facet['facet_img'],
            'url'        => url('topic.writers', ['slug' => $facet['facet_slug']]),
        ];

        return $this->render(
            '/facets/writers',
            'base',
            [
                'meta'  => Meta::get($facet['facet_seo_title'] . ' — ' .  __('app.info'), $facet['facet_description'], $m),

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
            'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], $this->user['id'], 'facet'),
            'related_posts' => PostModel::postRelated($facet['facet_post_related'] ?? null),
            'high_topics'   => FacetModel::getHighLevelList($facet['facet_id']),
            'writers'       => FacetModel::getWriters($facet['facet_id'], 15),
            'low_topics'    => FacetModel::getLowLevelList($facet['facet_id']),
            'user'          => UserModel::getUser($facet['facet_user_id'], 'id'),
        ];
    }
}
