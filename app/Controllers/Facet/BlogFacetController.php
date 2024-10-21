<?php

declare(strict_types=1);

namespace App\Controllers\Facet;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\FacetPresence;
use App\Models\User\UserModel;
use App\Models\{FeedModel, SubscriptionModel, FacetModel};
use Meta, Html;

class BlogFacetController extends Controller
{
    protected $limit = 25;

    public function feed(): void
    {
        $this->callIndex('facet.feed');
    }

    public function questions(): void
    {
        $this->callIndex('questions');
    }

    public function posts(): void
    {
        $this->callIndex('posts');
    }

    /**
     * Blog posts
     * Посты в блоге
     *
     * @param string $sheet
     * @return void
     */
    public function callIndex(string $sheet)
    {
        $facet  = FacetPresence::index(Request::param('slug')->value(), 'slug', 'blog');

        if ($facet['facet_type'] === 'topic') {
            echo view('error', ['httpCode' => 404, 'message' => __('404.page_not') . ' <br> ' . __('404.page_removed')]);
            exit();
        }

        $posts      = FeedModel::feed(Html::pageNumber(), $this->limit, $sheet, $facet['facet_slug']);
        $pagesCount = FeedModel::feedCount($sheet, $facet['facet_slug']);

        render(
            '/facets/blog',
            [
                'meta'  => Meta::facet($sheet, $facet, 'blog'),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => Html::pageNumber(),
                    'sheet'         => $sheet,
                    'type'          => 'user.blog',
                    'facet'         => $facet,
                    'posts'         => $posts,
                    'users_team'    => FacetModel::getUsersTeam($facet['facet_id']),
                    'user'          => UserModel::get($facet['facet_user_id'], 'id'),
                    'focus_users'   => FacetModel::getFocusUsers($facet['facet_id'], 1, 5),
                    'facet_signed'  => SubscriptionModel::getFocus($facet['facet_id'], 'facet'),
                    'info'          => markdown($facet['facet_info'] ?? '', 'text'),
                ],
                'facet'   => ['facet_id' => $facet['facet_id'], 'facet_type' => $facet['facet_type'], 'facet_user_id' => $facet['facet_user_id']],
            ]
        );
    }

    /**
     * Topic page that groups posts for the Blog
     * Страница Тем, которая группирует посты для Блога
     *
     * @return void
     */
    public function topic()
    {
        $facet  = FacetPresence::index(Request::get('slug')->value(), 'slug', 'blog');
        $topic  = FacetPresence::index(Request::get('tslug')->value(), 'slug', 'topic');

        $posts      = FeedModel::feed(Html::pageNumber(), $this->limit, 'facet.feed.topic', $facet['facet_slug'], $topic['facet_slug']);
        $pagesCount = FeedModel::feedCount('facet.feed.topic', $facet['facet_slug'], $topic['facet_slug']);

        render(
            '/facets/blog-topic',
            [
                'meta'  => Meta::facet('blog.topics', $facet, 'blog', $topic),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => Html::pageNumber(),
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
