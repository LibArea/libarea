<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{FeedModel, SubscriptionModel, FacetModel};
use Content, Base, Translate;

class BlogFacetController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Посты по теме
    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $slug   = Request::get('slug');
        $facet  = FacetModel::getFacet($slug, 'slug');
        pageError404($facet);

        if ($facet['facet_type'] == 'topic') {
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

        $url    = getUrlByName('blog', ['slug' => $facet['facet_slug']]);
        $title  = $facet['facet_seo_title'] . ' — ' .  Translate::get('topic');
        $descr  = $facet['facet_description'];

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => AG_PATH_FACETS_LOGOS . $facet['facet_img'],
            'url'        => $url,
        ];

        return view(
            '/facets/blog',
            [
                'meta'  => meta($m, $title, $descr),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet,
                    'facet'         => $facet,
                    'posts'         => $result,
                    'user'          => UserModel::getUser($facet['facet_user_id'], 'id'),
                    'focus_users'   => FacetModel::getFocusUsers($facet['facet_id'], 5),
                    'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], $this->uid['user_id'], 'topic'),
                    'info'          => Content::text($facet['facet_info'], 'text')

                ],
                'facet'   => [
                    'facet_id' => $facet['facet_id'],
                    'facet_type' => $facet['facet_type'],
                    'facet_user_id' => $facet['facet_user_id']
                ],
            ]
        );
    }
}
