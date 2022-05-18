<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{FacetModel, PostModel, FeedModel, AnswerModel, CommentModel};
use Meta, Html, UserData;

class ProfileController extends Controller
{
    protected $limit = 20;

    // Member page (profile) 
    // Страница участника (профиль)
    function index()
    {
        $profile    = self::profile();

        if (!$profile['about']) {
            $profile['about'] = __('app.riddle') . '...';
        }

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, $this->user, 'profile.posts', $profile['id']);
        $pagesCount = FeedModel::feedCount($this->user, 'profile.posts', $profile['id']);

        //[count_posts] => 244 [count_answers] => 408 [count_comments] => 580 [count_items] => 65 ) 
        $count = UserModel::contentCount($profile['id']);
        
        if (($count['count_answers'] + $count['count_comments']) < 3) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        return $this->render(
            '/user/profile/index',
            [
                'meta'  => self::metadata('profile_posts', $profile),
                'data'  => [
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $this->pageNumber,
                    'created_at'        => $profile['created_at'],
                    'count'             => $count,
                    'topics'            => FacetModel::getFacetsAll(1, 10, $profile['id'], 'my', 'topic'),
                    'blogs'             => FacetModel::getOwnerFacet($profile['id'], 'blog'),
                    'badges'            => BadgeModel::getBadgeUserAll($profile['id']),
                    'profile'           => $profile,
                    'posts'             => $posts,
                    'participation'     => FacetModel::participation($profile['id']),
                    'post'              => PostModel::getPost($profile['my_post'], 'id', $this->user),
                    'button_pm'         => $this->accessPm($profile['id']),
                ]
            ]
        );
    }

    public function posts()
    {
        $profile    = self::profile();

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, $this->user, 'profile.posts', $profile['id']);
        $pagesCount = FeedModel::feedCount($this->user, 'profile.posts', $profile['id']);

        return $this->render(
            '/user/profile/post',
            [
                'meta'  => self::metadata('profile_posts_all', $profile),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'posts'         => $posts,
                    'profile'       => $profile,
                    'count'         => UserModel::contentCount($profile['id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $profile['id'], 'my', 'topic'),
                    'blogs'         => FacetModel::getOwnerFacet($profile['id'], 'blog'),
                    'badges'        => BadgeModel::getBadgeUserAll($profile['id']),
                    'post'          => PostModel::getPost($profile['my_post'], 'id', $this->user),
                    'button_pm'     => $this->accessPm($profile['id']),
                ]
            ]
        );
    }

    public function answers()
    {
        $profile    = self::profile();

        $answers    = AnswerModel::userAnswers($this->pageNumber, $this->limit, $profile['id'], $this->user['id']);
        $pagesCount = AnswerModel::userAnswersCount($profile['id']);

        return $this->render(
            '/user/profile/answer',
            [
                'meta'  => self::metadata('profile_answers', $profile),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'answers'       => $answers,
                    'profile'       => $profile,
                    'count'         => UserModel::contentCount($profile['id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $profile['id'], 'my', 'topic'),
                    'blogs'         => FacetModel::getOwnerFacet($profile['id'], 'blog'),
                    'badges'        => BadgeModel::getBadgeUserAll($profile['id']),
                    'post'          => PostModel::getPost($profile['my_post'], 'id', $this->user),
                    'button_pm'     => $this->accessPm($profile['id']),
                ]
            ]
        );
    }

    // Комментарии участника
    public function comments()
    {
        $profile   = self::profile();

        $comments   = CommentModel::userComments($this->pageNumber, $this->limit, $profile['id'], $this->user['id']);
        $pagesCount = CommentModel::userCommentsCount($profile['id']);

        return $this->render(
            '/user/profile/comment',
            [
                'meta'  => self::metadata('profile_comments', $profile),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'comments'      => $comments,
                    'profile'       => $profile,
                    'count'         => UserModel::contentCount($profile['id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $profile['id'], 'my', 'topic'),
                    'blogs'         => FacetModel::getOwnerFacet($profile['id'], 'blog'),
                    'badges'        => BadgeModel::getBadgeUserAll($profile['id']),
                    'post'          => PostModel::getPost($profile['my_post'], 'id', $this->user),
                    'button_pm'     => $this->accessPm($profile['id']),
                    'login'         => $profile['login'],
                ]
            ]
        );
    }

    public static function profile()
    {
        $result = Request::get('login');
        Html::pageError404($profile = UserModel::getUser($result, 'slug'));

        if ($profile['ban_list'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        if (!isset($_SESSION['usernumbers'])) {
            $_SESSION['usernumbers'] = [];
        }

        if (!isset($_SESSION['usernumbers'][$profile['id']])) {
            UserModel::userHits($profile['id']);
            $_SESSION['usernumbers'][$profile['id']] = $profile['id'];
        }

        if (UserData::checkAdmin()) {
            Request::getResources()->addBottomScript('/assets/js/admin.js');
        }

        return $profile;
    }

    public static function metadata($sheet, $user)
    {
        if ($sheet == 'profile_posts') {
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
