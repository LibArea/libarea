<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{FeedModel, SubscriptionModel, FacetModel};
use Content, Translate, Tpl, UserData;

class BlogFacetController extends MainController
{
    protected $limit = 25;

    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Blog posts
    // Посты в блоге
    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $slug   = Request::get('slug');
        $facet  = FacetModel::getFacet($slug, 'slug', 'blog');
        pageError404($facet);

        if ($facet['facet_type'] == 'topic') {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $facet['facet_add_date']    = lang_date($facet['facet_add_date']);

        $posts      = FeedModel::feed($page, $this->limit, $this->user, $sheet, $facet['facet_slug']);
        $pagesCount = FeedModel::feedCount($this->user, $sheet, $facet['facet_slug']);

        $result = [];
        foreach ($posts as $ind => $row) {
            $text = fragment($row['post_content']);
            $row['post_content_preview']    = Content::text($text, 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $url    = getUrlByName('blog', ['slug' => $facet['facet_slug']]);
        $title  = $facet['facet_seo_title'] . ' — ' .  Translate::get('blog');
        $descr  = $facet['facet_description'];

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => AG_PATH_FACETS_LOGOS . $facet['facet_img'],
            'url'        => $url,
        ];

        return Tpl::agRender(
            '/facets/blog',
            [
                'meta'  => meta($m, $title, $descr),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'facet'         => $facet,
                    'posts'         => $result,
                    'user'          => UserModel::getUser($facet['facet_user_id'], 'id'),
                    'focus_users'   => FacetModel::getFocusUsers($facet['facet_id'], 5),
                    'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], $this->user['id'], 'topic'),
                    'info'          => Content::text($facet['facet_info'], 'text'),
                    'pages'         => (new \App\Controllers\PageController())->last($facet['facet_id']),

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
