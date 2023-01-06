<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{FacetModel, FeedModel, AnswerModel, CommentModel, PostModel, IgnoredModel};
use Meta, UserData;

use App\Traits\Views;

class ProfileController extends Controller
{
    use Views;

    protected $limit = 15;

    // Member page (profile) 
    // Страница участника (профиль)
    function index()
    {
        $profile    = $this->profile();

        if (!$profile['about']) {
            $profile['about'] = __('app.riddle') . '...';
        }

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, 'profile.posts', $profile['id']);
        $pagesCount = FeedModel::feedCount('profile.posts', $profile['id']);

        $amount = UserModel::contentCount($profile['id'], 'active');
        if (($amount['count_answers'] + $amount['count_comments']) < 3) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        return $this->render(
            '/user/profile/index',
            [
                'meta'  => self::metadata('profile', $profile),
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

    // User posts
    public function posts()
    {
        $profile    = $this->profile();

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, 'profile.posts', $profile['id']);
        $pagesCount = FeedModel::feedCount('profile.posts', $profile['id']);

        return $this->render(
            '/user/profile/post',
            [
                'meta'  => self::metadata('profile_posts', $profile),
                'data'  => array_merge($this->sidebar($pagesCount, $profile), ['posts' => $posts]),
            ]
        );
    }

    // User comments
    public function comments()
    {
        $profile   = $this->profile();

        $answers    = AnswerModel::userAnswers($this->pageNumber, $this->limit, $profile['id'], $this->user['id']);
        $answerCount = AnswerModel::userAnswersCount($profile['id']);

        $comments   = CommentModel::userComments($this->pageNumber, $this->limit, $profile['id'], $this->user['id']);
        $commentCount = CommentModel::userCommentsCount($profile['id']);

        $pagesCount = $answerCount + $commentCount;

        $mergedArr = array_merge($comments, $answers);
        usort($mergedArr, function ($a, $b) {
            return ($b['comment_date'] ?? $b['answer_date']) <=> ($a['comment_date'] ?? $a['answer_date']);
        });

        return $this->render(
            '/user/profile/comment',
            [
                'meta'  => self::metadata('profile_comments', $profile),
                'data'  => array_merge($this->sidebar($pagesCount, $profile), ['comments' => $mergedArr]),
            ]
        );
    }

    public function sidebar($pagesCount, $profile)
    {
        return [
            'pagesCount'    => ceil($pagesCount / $this->limit),
            'pNum'          => $this->pageNumber,
            'profile'       => $profile,
            'delet_count'   => UserModel::contentCount($profile['id'], 'remote'),
            'counts'        => UserModel::contentCount($profile['id'], 'active'),
            'topics'        => FacetModel::getFacetsTopicProfile($profile['id']),
            'blogs'         => FacetModel::getOwnerFacet($profile['id'], 'blog'),
            'badges'        => BadgeModel::getBadgeUserAll($profile['id']),
            'my_post'       => PostModel::getPost($profile['my_post'], 'id', $this->user),
            'button_pm'     => $this->accessPm($profile['id']),
            'ignored'       => IgnoredModel::getUserIgnored($profile['id']),
        ];
    }

    public function profile()
    {
        $result = Request::get('login');
        notEmptyOrView404($profile = UserModel::getUser($result, 'slug'));

        if ($profile['ban_list'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        $this->setProfileView($profile['id']);

        if (UserData::checkAdmin()) {
            Request::getResources()->addBottomScript('/assets/js/admin.js');
        }

        return $profile;
    }

    public static function metadata($sheet, $user)
    {
        if ($sheet == 'profile') {
            $information = $user['about'];
        }

        $name = $user['login'];
        if ($user['name']) {
            $name = $user['name'] . ' (' . $user['login'] . ') ';
        }

        $title = __('meta.' . $sheet . '_title', ['name' => $name]);
        $description  = __('meta.' . $sheet . '_desc', ['name' => $name, 'information' => $information ?? '...']);

        $m = [
            'og'        => true,
            'imgurl'    => '/uploads/users/avatars/' . $user['avatar'],
            'url'       => url('profile', ['login' => $user['login']]),
        ];

        return Meta::get($title, $description, $m);
    }

    // Sending personal messages
    public function accessPm($for_user_id)
    {
        // We forbid sending to ourselves
        if ($this->user['id'] == $for_user_id) {
            return false;
        }

        // If the trust level is less than the established one
        if ($this->user['trust_level'] < config('trust-levels.tl_add_pm')) {
            return false;
        }

        return true;
    }
}
