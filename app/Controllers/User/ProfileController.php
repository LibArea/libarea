<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{FacetModel, FeedModel, CommentModel, PostModel, IgnoredModel};
use Html, Meta;

use App\Traits\Views;

class ProfileController extends Controller
{
    use Views;

    protected $limit = 15;

    /**
     * Member page (profile) 
     * Страница участника (профиль)
     *
     * @return void
     */
    function index(): void
    {
        $profile = $this->profile();

        if (!$profile['about']) {
            $profile['about'] = __('app.riddle') . '...';
        }

        $posts      = FeedModel::feed(Html::pageNumber(), $this->limit, 'profile.posts', $profile['id']);
        $pagesCount = FeedModel::feedCount('profile.posts', $profile['id']);

        render(
            '/user/profile/index',
            [
                'meta'  => Meta::profile('profile', $profile),
                'data'  => array_merge(
                    $this->sidebar($pagesCount, $profile),
                    [
                        'posts' => $posts,
                        'participation' => FacetModel::participation($profile['id'])
                    ]
                ),
            ]
        );
    }

    /**
     * User posts
     *
     * @return void
     */
    public function posts()
    {
        $profile    = $this->profile();

        $posts      = FeedModel::feed(Html::pageNumber(), $this->limit, 'profile.posts', $profile['id']);
        $pagesCount = FeedModel::feedCount('profile.posts', $profile['id']);

        render(
            '/user/profile/posts',
            [
                'meta'  => Meta::profile('profile_posts', $profile),
                'data'  => array_merge($this->sidebar($pagesCount, $profile), ['posts' => $posts]),
            ]
        );
    }

    /**
     * User comments
     *
     * @return void
     */
    public function comments()
    {
        $profile    = $this->profile();
        $comments    = CommentModel::userComments(Html::pageNumber(), $profile['id'], $this->container->user()->id());
        $commentsCount    = CommentModel::userCommentsCount($profile['id']);

        render(
            '/user/profile/comments',
            [
                'meta'  => Meta::profile('profile_comments', $profile),
                'data'  => array_merge($this->sidebar((int)$commentsCount, $profile), ['comments' => $comments]),
            ]
        );
    }

    public function sidebar(int $pagesCount, array $profile)
    {
        return [
            'pagesCount'    => ceil($pagesCount / $this->limit),
            'pNum'          => Html::pageNumber(),
            'profile'       => $profile,
            'type'          => 'profile',
            'delet_count'   => UserModel::contentCount($profile['id'], 'remote'),
            'counts'        => UserModel::contentCount($profile['id'], 'active'),
            'topics'        => FacetModel::getFacetsTopicProfile($profile['id']),
            'blogs'         => FacetModel::getOwnerFacet($profile['id'], 'blog'),
            'badges'        => BadgeModel::getBadgeUserAll($profile['id']),
            'my_post'       => PostModel::getPost($profile['my_post'], 'id'),
            'button_pm'     => $this->accessPm($profile['id']),
            'ignored'       => IgnoredModel::getUserIgnored($profile['id']),
        ];
    }

    public function profile()
    {
        $result = Request::param('login')->value();

        notEmptyOrView404($profile = UserModel::get($result, 'slug'));

        $this->setProfileView($profile['id']);

        return $profile;
    }

    /**
     * Sending personal messages
     *
     * @param integer $for_user_id
     * @return void
     */
    public function accessPm(int $for_user_id)
    {
        // We forbid sending to ourselves
        if ($this->container->user()->id() == $for_user_id) {
            return false;
        }

        // If the trust level is less than the established one
        if ($this->container->user()->tl() < config('trust-levels', 'tl_add_pm')) {
            return false;
        }

        return true;
    }
}
