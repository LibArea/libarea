<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\FacetPresence;
use App\Models\User\UserModel;
use App\Models\{FeedModel, SubscriptionModel, FacetModel};
use Meta, Img;

class BlogFacetController extends Controller
{
    protected $limit = 25;

    // Blog posts
    // Посты в блоге
    public function index($sheet, $type)
    {
        $facet  = FacetPresence::index(Request::get('slug'), 'slug', 'blog');

        if ($facet['facet_type'] == 'topic') {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, $sheet, $facet['facet_slug']);
        $pagesCount = FeedModel::feedCount($sheet, $facet['facet_slug']);

        $url    = url('blog', ['slug' => $facet['facet_slug']]);
        $title  = $facet['facet_seo_title'] . ' — ' .  __('app.blog');
        $description  = $facet['facet_description'];

        if ($facet['facet_is_deleted'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        $m = [
            'og'        => true,
            'imgurl'    => Img::PATH['facets_logo'] . $facet['facet_img'],
            'url'       => $url,
        ];

        return $this->render(
            '/facets/blog',
            [
                'meta'  => Meta::get($title, $description, $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'facet'         => $facet,
                    'posts'         => $posts,
                    'users_team'    => FacetModel::getUsersTeam($facet['facet_id']),
                    'user'          => UserModel::getUser($facet['facet_user_id'], 'id'),
                    'focus_users'   => FacetModel::getFocusUsers($facet['facet_id'], 1, 5),
                    'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], 'facet'),
                    'info'          => markdown($facet['facet_info'] ?? false, 'text'),
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
