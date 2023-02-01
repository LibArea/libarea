<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\FacetPresence;
use App\Services\Meta\Facet;
use App\Models\User\UserModel;
use App\Models\{FeedModel, SubscriptionModel, FacetModel};

class BlogFacetController extends Controller
{
    protected $limit = 25;

    // Blog posts
    // Посты в блоге
    public function index($sheet)
    {
        $facet  = FacetPresence::index(Request::get('slug'), 'slug', 'blog');

        if ($facet['facet_type'] == 'topic') {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, $sheet, $facet['facet_slug']);
        $pagesCount = FeedModel::feedCount($sheet, $facet['facet_slug']);

        if ($facet['facet_is_deleted'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        return $this->render(
            '/facets/blog',
            [
                'meta'  => Facet::metadata($sheet, $facet, 'blog'),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'sheet'         => $sheet,
                    'type'          => 'blog',
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

    // Topic page that groups posts for the Blog
    // Страница Тем, которая группирует посты для Блога
    public function topic()
    {
        $facet  = FacetPresence::index(Request::get('slug'), 'slug', 'blog');
        $topic  = FacetPresence::index(Request::get('tslug'), 'slug', 'topic');

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, 'facet.feed.topic', $facet['facet_slug'], $topic['facet_slug']);
        $pagesCount = FeedModel::feedCount('facet.feed.topic', $facet['facet_slug'], $topic['facet_slug']);

        return $this->render(
            '/facets/blog-topic',
            [
                'meta'  => Facet::metadata('blog.topics', $facet, 'blog', $topic),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'sheet'         => 'facet.feed.topic',
                    'type'          => 'blog',
                    'posts'         => $posts,
                    'topic'         => $topic,
                    'facet'         => $facet,
                    'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], 'facet'),
                ],
            ]
        );
    }
}
