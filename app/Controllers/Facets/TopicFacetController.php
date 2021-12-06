<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{FeedModel, SubscriptionModel, FacetModel, PostModel};
use Content, Base, Translate;

class TopicFacetController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid = Base::getUid();
    }

    // Посты по теме
    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $slug   = Request::get('slug');
        $facet  = FacetModel::getFacet($slug, 'slug');
        pageError404($facet);

        if ($facet['facet_type'] == 'blog') {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $facet['facet_add_date']    = lang_date($facet['facet_add_date']);

        $limit = 25;
        $data       = ['facet_slug' => $facet['facet_slug']];
        $posts      = FeedModel::feed($page, $limit, $this->uid, $sheet, 'topic', $data);
        $pagesCount = FeedModel::feedCount($this->uid, $sheet, 'topic', $data);

        $result = [];
        foreach ($posts as $ind => $row) {
            $text = fragment($row['post_content']);
            $row['post_content_preview']    = Content::text($text, 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $url    = getUrlByName('topic', ['slug' => $facet['facet_slug']]);
        $title  = $facet['facet_seo_title'] . ' — ' .  Translate::get('topic');
        $descr  = $facet['facet_description'];
        if ($sheet == 'recommend') {
            $url =  getUrlByName('recommend', ['slug' => $facet['facet_slug']]);
            $title  = $facet['facet_seo_title'] . ' — ' .  Translate::get('recommended posts');
            $descr  = sprintf(Translate::get('recommended-posts-desc'), $facet['facet_seo_title']) . $facet['facet_description'];
        }

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => AG_PATH_FACETS_LOGOS . $facet['facet_img'],
            'url'        => $url,
        ];

        return view(
            '/facets/topic',
            [
                'meta'  => meta($m, $title, $descr),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet == 'feed' ? 'topic' : 'recommend',
                    'facet'         => $facet,
                    'posts'         => $result,
                    'focus_users'   => FacetModel::getFocusUsers($facet['facet_id'], 5),
                    'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], $this->uid['user_id'], 'topic'),
                    'user'          => UserModel::getUser($facet['facet_user_id'], 'id'),
                    'high_topics'   => FacetModel::getHighLevelList($facet['facet_id']),
                    'low_topics'    => FacetModel::getLowLevelList($facet['facet_id']),
                    'low_matching'  => FacetModel::getLowMatching($facet['facet_id']),
                    'writers'       => FacetModel::getWriters($facet['facet_id']),
                ],
                'facet'   => ['facet_id' => $facet['facet_id'], 'facet_type' => $facet['facet_type'], 'facet_user_id' => $facet['facet_user_id']],
            ]
        );
    }

    // Информация по теме
    public function info()
    {
        $slug   = Request::get('slug');
        $facet  = FacetModel::getFacet($slug, 'slug');
        pageError404($facet);

        $facet['facet_add_date']    = lang_date($facet['facet_add_date']);

        $facet['facet_info']   = Content::text($facet['facet_info'], 'text');

        $facet_related = empty($facet['facet_post_related']) ? 0 : $facet['facet_post_related'];

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/topics/logos/' . $facet['facet_img'],
            'url'        => getUrlByName('topic.info', ['slug' => $facet['facet_slug']]),
        ];

        return view(
            '/facets/info',
            [
                'meta'  => meta($m, $facet['facet_seo_title'] . ' — ' .  Translate::get('topic'), $facet['facet_description']),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => 'info',
                    'facet'         => $facet,
                    'related_posts' => PostModel::postRelated($facet_related),
                    'high_topics'   => FacetModel::getHighLevelList($facet['facet_id']),
                    'low_topics'    => FacetModel::getLowLevelList($facet['facet_id']),
                    'user'          => UserModel::getUser($facet['facet_user_id'], 'id'),
                ]
            ]
        );
    }

    // Подписаны (25)
    public function followers()
    {
        $topic_id   = Request::getInt('id');
   
        $users      = FacetModel::getFocusUsers($topic_id, 25);

        return includeTemplate('/content/facets/followers', ['users' => $users]);
    }
}
