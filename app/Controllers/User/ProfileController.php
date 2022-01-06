<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{FacetModel, PostModel, FeedModel, AnswerModel, CommentModel};
use Content, Config, Validation, Translate;

class ProfileController extends MainController
{
    private $uid;

    protected $limit = 20;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    // Member page (profile) 
    // Страница участника (профиль)
    function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $login = Request::get('login');
        $user  = UserModel::getUser($login, 'slug');
        pageError404($user);

        if (!$user['user_about']) {
            $user['user_about'] = Translate::get('riddle') . '...';
        }

        $site_name  = Config::get('meta.name');
        $meta_title = sprintf(Translate::get('title-profile'), $user['user_login'], $user['user_name'], $site_name);
        $meta_desc  = sprintf(Translate::get('desc-profile'), $user['user_login'], $user['user_about'], $site_name);

        if ($user['user_ban_list'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        // Profile Views (просмотры профиля)
        if (!isset($_SESSION['usernumbers'])) {
            $_SESSION['usernumbers'] = [];
        }

        if (!isset($_SESSION['usernumbers'][$user['user_id']])) {
            UserModel::userHits($user['user_id']);
            $_SESSION['usernumbers'][$user['user_id']] = $user['user_id'];
        }

        $isBan = '';
        if (UserData::checkAdmin()) {
            Request::getResources()->addBottomScript('/assets/js/admin.js');
            $isBan = UserModel::isBan($user['user_id']);
        }

        $posts      = FeedModel::feed($page, $this->limit, $this->uid, $sheet, $user['user_id']);
        $pagesCount = FeedModel::feedCount($this->uid, $sheet, $user['user_id']);

        $result = [];
        foreach ($posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }


        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/users/avatars/' . $user['user_avatar'],
            'url'        => getUrlByName('profile', ['login' => $user['user_login']]),
        ];

        return agRender(
            '/user/profile/index',
            [
                'meta'  => meta($m, $meta_title, $meta_desc),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $page,
                    'user_created_at'   => lang_date($user['user_created_at']),
                    'count'             => UserModel::contentCount($user['user_id']),
                    'topics'            => FacetModel::getFacetsAll(1, 10, $user['user_id'], 'topics.my'),
                    'blogs'             => FacetModel::getOwnerFacet($user['user_id'], 'blog'),
                    'badges'            => BadgeModel::getBadgeUserAll($user['user_id']),
                    'user'              => $user,
                    'isBan'             => $isBan,
                    'type'              => $type,
                    'posts'             => $result,
                    'sheet'             => $sheet,
                    'participation'     => FacetModel::participation($user['user_id']),
                    'post'              => PostModel::getPost($user['user_my_post'], 'id', $this->uid),
                    'button_pm'         => Validation::accessPm($this->uid, $user['user_id'], Config::get('general.tl_add_pm')),
                ]
            ]
        );
    }

    // Посты участника
    public function posts($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $login  = Request::get('login');
        $user   = UserModel::getUser($login, 'slug');
        pageError404($user);

        $posts      = FeedModel::feed($page, $this->limit, $this->uid, $sheet, $user['user_id']);
        $pagesCount = FeedModel::feedCount($this->uid, $sheet, $user['user_id']);

        $result = [];
        foreach ($posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/users/avatars/' . $user['user_avatar'],
            'url'        => getUrlByName('profile.posts', ['login' => $login]),
        ];

        return agRender(
            '/user/profile/post',
            [
                'meta'  => meta($m, Translate::get('posts') . ' ' . $login, Translate::get('participant posts') . ' ' . $login),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => 'user-post',
                    'type'          => 'posts.user',
                    'posts'         => $result,
                    'user'          => $user,
                    'count'         => UserModel::contentCount($user['user_id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $user['user_id'], 'topics.my'),
                    'blogs'         => FacetModel::getOwnerFacet($user['user_id'], 'blog'),
                    'badges'        => BadgeModel::getBadgeUserAll($user['user_id']),
                    'post'          => PostModel::getPost($user['user_my_post'], 'id', $this->uid),
                    'button_pm'     => Validation::accessPm($this->uid, $user['user_id'], Config::get('general.tl_add_pm')),
                ]
            ]
        );
    }

    // Ответы участника
    public function answers()
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $login  = Request::get('login');
        $user   = UserModel::getUser($login, 'slug');
        pageError404($user);

        $answers    = AnswerModel::userAnswers($page, $this->limit, $user['user_id'], $this->uid['user_id']);
        $pagesCount = AnswerModel::userAnswersCount($user['user_id']);

        $result = [];
        foreach ($answers as $ind => $row) {
            $row['content'] = Content::text($row['answer_content'], 'text');
            $row['date']    = lang_date($row['answer_date']);
            $result[$ind]   = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('profile.answers', ['login' => $user['user_login']]),
        ];

        return agRender(
            '/user/profile/answer',
            [
                'meta'  => meta($m, Translate::get('answers') . ' ' . $user['user_login'], Translate::get('responses-members') . ' ' . $user['user_login']),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => 'user-answers',
                    'type'          => 'answers.user',
                    'answers'       => $result,
                    'user'          => $user,
                    'count'         => UserModel::contentCount($user['user_id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $user['user_id'], 'topics.my'),
                    'blogs'         => FacetModel::getOwnerFacet($user['user_id'], 'blog'),
                    'badges'        => BadgeModel::getBadgeUserAll($user['user_id']),
                    'post'          => PostModel::getPost($user['user_my_post'], 'id', $this->uid),
                    'button_pm'     => Validation::accessPm($this->uid, $user['user_id'], Config::get('general.tl_add_pm')),
                ]
            ]
        );
    }

    // Комментарии участника
    public function comments()
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $login  = Request::get('login');
        $user   = UserModel::getUser($login, 'slug');
        pageError404($user);

        $comments   = CommentModel::userComments($page, $this->limit, $user['user_id'], $this->uid['user_id']);
        $pagesCount = CommentModel::userCommentsCount($user['user_id']);

        $result = [];
        foreach ($comments as $ind => $row) {
            $row['comment_content'] = Content::text($row['comment_content'], 'text');
            $row['date']            = lang_date($row['comment_date']);
            $result[$ind]           = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('comments.user', ['login' => $user['user_login']]),
        ];

        return agRender(
            '/user/profile/comment',
            [
                'meta'  => meta($m, Translate::get('comments') . ' ' . $user['user_login'], Translate::get('comments') . ' ' . $user['user_login']),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => 'user-comments',
                    'type'          => 'comments.user',
                    'comments'      => $result,
                    'user'          => $user,
                    'count'         => UserModel::contentCount($user['user_id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $user['user_id'], 'topics.my'),
                    'blogs'         => FacetModel::getOwnerFacet($user['user_id'], 'blog'),
                    'badges'        => BadgeModel::getBadgeUserAll($user['user_id']),
                    'post'          => PostModel::getPost($user['user_my_post'], 'id', $this->uid),
                    'button_pm'     => Validation::accessPm($this->uid, $user['user_id'], Config::get('general.tl_add_pm')),
                    'user_login'    => $user['user_login'],
                ]
            ]
        );
    }
}
