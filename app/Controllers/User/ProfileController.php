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
        $user   = self::availability();

        if (!$user['user_about']) {
            $user['user_about'] = Translate::get('riddle') . '...';
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

        return agRender(
            '/user/profile/index',
            [
                'meta'  => self::metadata($sheet, $user),
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
                    'type'              => $type,
                    'posts'             => $result,
                    'sheet'             => $sheet,
                    'participation'     => FacetModel::participation($user['user_id']),
                    'post'              => PostModel::getPost($user['user_my_post'], 'id', $this->uid),
                    'button_pm'         => self::accessPm($this->uid, $user['user_id'], Config::get('general.tl_add_pm')),
                ]
            ]
        );
    }

    public function posts($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $user   = self::availability();

        $posts      = FeedModel::feed($page, $this->limit, $this->uid, $sheet, $user['user_id']);
        $pagesCount = FeedModel::feedCount($this->uid, $sheet, $user['user_id']);

        $result = [];
        foreach ($posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        return agRender(
            '/user/profile/post',
            [
                'meta'  => self::metadata($sheet, $user),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'posts'         => $result,
                    'user'          => $user,
                    'count'         => UserModel::contentCount($user['user_id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $user['user_id'], 'topics.my'),
                    'blogs'         => FacetModel::getOwnerFacet($user['user_id'], 'blog'),
                    'badges'        => BadgeModel::getBadgeUserAll($user['user_id']),
                    'post'          => PostModel::getPost($user['user_my_post'], 'id', $this->uid),
                    'button_pm'     => self::accessPm($this->uid, $user['user_id'], Config::get('general.tl_add_pm')),
                ]
            ]
        );
    }

    public function answers($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $user   = self::availability();

        $answers    = AnswerModel::userAnswers($page, $this->limit, $user['user_id'], $this->uid['user_id']);
        $pagesCount = AnswerModel::userAnswersCount($user['user_id']);

        $result = [];
        foreach ($answers as $ind => $row) {
            $row['content'] = Content::text($row['answer_content'], 'text');
            $row['date']    = lang_date($row['answer_date']);
            $result[$ind]   = $row;
        }

        return agRender(
            '/user/profile/answer',
            [
                'meta'  => self::metadata($sheet, $user),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'answers'       => $result,
                    'user'          => $user,
                    'count'         => UserModel::contentCount($user['user_id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $user['user_id'], 'topics.my'),
                    'blogs'         => FacetModel::getOwnerFacet($user['user_id'], 'blog'),
                    'badges'        => BadgeModel::getBadgeUserAll($user['user_id']),
                    'post'          => PostModel::getPost($user['user_my_post'], 'id', $this->uid),
                    'button_pm'     => self::accessPm($this->uid, $user['user_id'], Config::get('general.tl_add_pm')),
                ]
            ]
        );
    }

    // Комментарии участника
    public function comments($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $user   = self::availability();

        $comments   = CommentModel::userComments($page, $this->limit, $user['user_id'], $this->uid['user_id']);
        $pagesCount = CommentModel::userCommentsCount($user['user_id']);

        $result = [];
        foreach ($comments as $ind => $row) {
            $row['comment_content'] = Content::text($row['comment_content'], 'text');
            $row['date']            = lang_date($row['comment_date']);
            $result[$ind]           = $row;
        }

        return agRender(
            '/user/profile/comment',
            [
                'meta'  => self::metadata($sheet, $user),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'comments'      => $result,
                    'user'          => $user,
                    'count'         => UserModel::contentCount($user['user_id']),
                    'topics'        => FacetModel::getFacetsAll(1, 10, $user['user_id'], 'topics.my'),
                    'blogs'         => FacetModel::getOwnerFacet($user['user_id'], 'blog'),
                    'badges'        => BadgeModel::getBadgeUserAll($user['user_id']),
                    'post'          => PostModel::getPost($user['user_my_post'], 'id', $this->uid),
                    'button_pm'     => self::accessPm($this->uid, $user['user_id'], Config::get('general.tl_add_pm')),
                    'user_login'    => $user['user_login'],
                ]
            ]
        );
    }

    public static function availability()
    {
        $login  = Request::get('login');
        $user   = UserModel::getUser($login, 'slug');
        pageError404($user);

        if ($user['user_ban_list'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        if (!isset($_SESSION['usernumbers'])) {
            $_SESSION['usernumbers'] = [];
        }

        if (!isset($_SESSION['usernumbers'][$user['user_id']])) {
            UserModel::userHits($user['user_id']);
            $_SESSION['usernumbers'][$user['user_id']] = $user['user_id'];
        }

        if (UserData::checkAdmin()) {
            Request::getResources()->addBottomScript('/assets/js/admin.js');
        }

        return $user;
    }

    public static function metadata($sheet, $user)
    {
        if ($sheet == 'profile.posts') {
            $information = $user['user_about'];
        }

        $name = $user['user_login'];
        if ($user['user_name']) {
            $name = $user['user_name'] . ' (' . $user['user_login'] . ') ';
        }

        $title = sprintf(Translate::get($sheet . '.title'), $name);
        $desc  = sprintf(Translate::get($sheet . '.desc'), $name, $information ?? '...');

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/users/avatars/' . $user['user_avatar'],
            'url'        => getUrlByName('profile', ['login' => $user['user_login']]),
        ];

        return meta($m, $title, $desc);
    }

    // Отправки личных сообщений (ЛС)
    // $uid - кто отправляет
    // $user_id - кому
    // $add_tl -  с какого уровня доверия
    public static function accessPm($uid, $user_id, $add_tl)
    {
        // Запретим отправку себе
        if ($uid['user_id'] == $user_id) {
            return false;
        }

        // Если уровень доверия меньше установленного
        if ($add_tl > $uid['user_trust_level']) {
            return false;
        }

        return true;
    }
}
