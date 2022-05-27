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

        $url    = url('topic', ['slug' => $facet['facet_slug']]);
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
            'url'        => $url,
        ];

        return $this->render(
            '/facets/topic',
            'base',
            [
                'meta'  => Meta::get($title, $description, $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'facet'         => $facet,
                    'posts'         => $posts,
                    'focus_users'   => FacetModel::getFocusUsers($facet['facet_id'], 5),
                    'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], $this->user['id'], 'facet'),
                    'user'          => UserModel::getUser($facet['facet_user_id'], 'id'),
                    'high_topics'   => FacetModel::getHighLevelList($facet['facet_id']),
                    'low_topics'    => FacetModel::getLowLevelList($facet['facet_id']),
                    'low_matching'  => FacetModel::getLowMatching($facet['facet_id']),
                    'writers'       => FacetModel::getWriters($facet['facet_id'], 5),
                ],
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

        $facet_related = $facet['facet_post_related'] ?? null;

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
                'data'  => [
                    'sheet'         => 'info',
                    'type'          => 'info',
                    'facet'         => $facet,
                    'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], $this->user['id'], 'facet'),
                    'focus_users'   => FacetModel::getFocusUsers($facet['facet_id'], 5),
                    'related_posts' => PostModel::postRelated($facet_related),
                    'high_topics'   => FacetModel::getHighLevelList($facet['facet_id']),
                    'low_topics'    => FacetModel::getLowLevelList($facet['facet_id']),
                    'user'          => UserModel::getUser($facet['facet_user_id'], 'id'),
                ]
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

        $facet_related = $facet['facet_post_related'] ?? null;

        $m = [
            'og'         => true,
            'imgurl'     => PATH_FACETS_LOGOS . $facet['facet_img'],
            'url'        => url('topic.info', ['slug' => $facet['facet_slug']]),
        ];

        return $this->render(
            '/facets/writers',
            'base',
            [
                'meta'  => Meta::get($facet['facet_seo_title'] . ' — ' .  __('app.info'), $facet['facet_description'], $m),
                'data'  => [
                    'sheet'         => 'writers',
                    'type'          => 'writers',
                    'facet'         => $facet,
                    'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], $this->user['id'], 'facet'),
                    'focus_users'   => FacetModel::getFocusUsers($facet['facet_id'], 5),
                    'related_posts' => PostModel::postRelated($facet_related),
                    'high_topics'   => FacetModel::getHighLevelList($facet['facet_id']),
                    'writers'       => FacetModel::getWriters($facet['facet_id'], 15),
                    'low_topics'    => FacetModel::getLowLevelList($facet['facet_id']),
                    'user'          => UserModel::getUser($facet['facet_user_id'], 'id'),
                ]
            ]
        );
    }

    // Subscribed (25) 
    // Подписаны (25)
    public function followers()
    {
        $topic_id   = Request::getInt('id');

        $users      = FacetModel::getFocusUsers($topic_id, 15);

        return insert('/content/facets/followers', ['users' => $users]);
    }
}
