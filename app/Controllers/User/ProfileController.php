<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{FacetModel, PostModel, FeedModel, AnswerModel, CommentModel};
use Config, Tpl, Meta, Html, UserData;

class ProfileController extends MainController
{
    private $user;

    protected $limit = 20;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Member page (profile) 
    // Страница участника (профиль)
    function index($sheet, $type)
    {
        $pageNumber = Tpl::pageNumber();
        $profile    = self::profile();

        if (!$profile['about']) {
            $profile['about'] = __('riddle') . '...';
        }

        $posts      = FeedModel::feed($pageNumber, $this->limit, $this->user, $sheet, $profile['id']);
        $pagesCount = FeedModel::feedCount($this->user, $sheet, $profile['id']);

        $count = UserModel::contentCount($profile['id']);
        if (($count['count_answers'] + $count['count_comments']) < 3) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        return Tpl::LaRender(
            '/user/profile/index',
            [
                'meta'  => self::metadata($sheet, $profile),
                'data'  => [
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $pageNumber,
                    'created_at'        => $profile['created_at'],
                    'count'             => $count,
                    'topics'            => FacetModel::getFacetsAll(1, 10, $profile['id'], 'topics.my'),
                    'blogs'             => FacetModel::getOwnerFacet($profile['id'], 'blog'),
                    'badges'            => BadgeModel::getBadgeUserAll($profile['id']),
                    'profile'           => $profile,
                    'type'              => $type,
                    'posts'             => $posts,
                    'sheet'             => $sheet,
                    'participation'     => FacetModel::participation($profile['id']),
                    'post'              => PostModel::getPost($profile['my_post'], 'id', $this->user),
                    'button_pm'         => $this->accessPm($profile['id']),
                ]
            ]
        );
    }

    public function posts($sheet, $type)
    {
        $pageNumber = Tpl::pageNumber();
        $profile    = self::profile();

        $posts      = FeedModel::feed($pageNumber, $this->limit, $this->user, $sheet, $profile['id']);
        $pagesCount = FeedModel::feedCount($this->user, $sheet, $profile['id']);

        return Tpl::LaRender(
            '/user/profile/post',
            [
                'meta'  => self::metadata($sheet . '.all', $profile),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'posts'         => $posts,
                    'profile'       => $profile,
                    'count'         => UserModel::contentCount($profile['id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $profile['id'], 'topics.my'),
                    'blogs'         => FacetModel::getOwnerFacet($profile['id'], 'blog'),
                    'badges'        => BadgeModel::getBadgeUserAll($profile['id']),
                    'post'          => PostModel::getPost($profile['my_post'], 'id', $this->user),
                    'button_pm'     => $this->accessPm($profile['id']),
                ]
            ]
        );
    }

    public function answers($sheet, $type)
    {
        $pageNumber = Tpl::pageNumber();
        $profile    = self::profile();

        $answers    = AnswerModel::userAnswers($pageNumber, $this->limit, $profile['id'], $this->user['id']);
        $pagesCount = AnswerModel::userAnswersCount($profile['id']);

        return Tpl::LaRender(
            '/user/profile/answer',
            [
                'meta'  => self::metadata($sheet, $profile),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'answers'       => $answers,
                    'profile'       => $profile,
                    'count'         => UserModel::contentCount($profile['id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $profile['id'], 'topics.my'),
                    'blogs'         => FacetModel::getOwnerFacet($profile['id'], 'blog'),
                    'badges'        => BadgeModel::getBadgeUserAll($profile['id']),
                    'post'          => PostModel::getPost($profile['my_post'], 'id', $this->user),
                    'button_pm'     => $this->accessPm($profile['id']),
                ]
            ]
        );
    }

    // Комментарии участника
    public function comments($sheet, $type)
    {
        $pageNumber = Tpl::pageNumber();
        $profile   = self::profile();

        $comments   = CommentModel::userComments($pageNumber, $this->limit, $profile['id'], $this->user['id']);
        $pagesCount = CommentModel::userCommentsCount($profile['id']);

        return Tpl::LaRender(
            '/user/profile/comment',
            [
                'meta'  => self::metadata($sheet, $profile),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'comments'      => $comments,
                    'profile'       => $profile,
                    'count'         => UserModel::contentCount($profile['id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $profile['id'], 'topics.my'),
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
        if ($sheet == 'profile.posts') {
            $information = $user['about'];
        }

        $name = $user['login'];
        if ($user['name']) {
            $name = $user['name'] . ' (' . $user['login'] . ') ';
        }

        $title = __($sheet . '.title', ['name' => $name]);
        $description  = __($sheet . '.desc', ['name' => $name, 'information' => $information ?? '...']);

        $m = [
            'og'        => true,
            'imgurl'    => '/uploads/users/avatars/' . $user['avatar'],
            'url'       => getUrlByName('profile', ['login' => $user['login']]),
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
        if ($this->user['trust_level'] < Config::get('trust-levels.tl_add_pm')) {
            return false;
        }

        return true;
    }
}
