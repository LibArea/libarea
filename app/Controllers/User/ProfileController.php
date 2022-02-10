<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{FacetModel, PostModel, FeedModel, AnswerModel, CommentModel};
use Content, Config, Translate, Tpl, UserData;

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
        $page       = Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;
        $profile    = self::profile();

        if (!$profile['about']) {
            $profile['about'] = Translate::get('riddle') . '...';
        }

        $posts      = FeedModel::feed($page, $this->limit, $this->user, $sheet, $profile['id']);
        $pagesCount = FeedModel::feedCount($this->user, $sheet, $profile['id']);

        $result = [];
        foreach ($posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        return Tpl::agRender(
            '/user/profile/index',
            [
                'meta'  => self::metadata($sheet, $profile),
                'data'  => [
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $page,
                    'created_at'        => lang_date($profile['created_at']),
                    'count'             => UserModel::contentCount($profile['id']),
                    'topics'            => FacetModel::getFacetsAll(1, 10, $profile['id'], 'topics.my'),
                    'blogs'             => FacetModel::getOwnerFacet($profile['id'], 'blog'),
                    'badges'            => BadgeModel::getBadgeUserAll($profile['id']),
                    'profile'           => $profile,
                    'type'              => $type,
                    'posts'             => $result,
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
        $page       = Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;
        $profile    = self::profile();

        $posts      = FeedModel::feed($page, $this->limit, $this->user, $sheet, $profile['id']);
        $pagesCount = FeedModel::feedCount($this->user, $sheet, $profile['id']);

        $result = [];
        foreach ($posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        return Tpl::agRender(
            '/user/profile/post',
            [
                'meta'  => self::metadata($sheet . '.all', $profile),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'posts'         => $result,
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
        $page       = Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;
        $profile    = self::profile();

        $answers    = AnswerModel::userAnswers($page, $this->limit, $profile['id'], $this->user['id']);
        $pagesCount = AnswerModel::userAnswersCount($profile['id']);

        $result = [];
        foreach ($answers as $ind => $row) {
            $row['content'] = Content::text($row['answer_content'], 'text');
            $row['date']    = lang_date($row['answer_date']);
            $result[$ind]   = $row;
        }

        return Tpl::agRender(
            '/user/profile/answer',
            [
                'meta'  => self::metadata($sheet, $profile),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'answers'       => $result,
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
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $profile   = self::profile();

        $comments   = CommentModel::userComments($page, $this->limit, $profile['id'], $this->user['id']);
        $pagesCount = CommentModel::userCommentsCount($profile['id']);

        $result = [];
        foreach ($comments as $ind => $row) {
            $row['comment_content'] = Content::text($row['comment_content'], 'text');
            $row['date']            = lang_date($row['comment_date']);
            $result[$ind]           = $row;
        }

        return Tpl::agRender(
            '/user/profile/comment',
            [
                'meta'  => self::metadata($sheet, $profile),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'comments'      => $result,
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
        pageError404($profile = UserModel::getUser($result, 'slug'));

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

        $title = sprintf(Translate::get($sheet . '.title'), $name);
        $desc  = sprintf(Translate::get($sheet . '.desc'), $name, $information ?? '...');

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/users/avatars/' . $user['avatar'],
            'url'        => getUrlByName('profile', ['login' => $user['login']]),
        ];

        return meta($m, $title, $desc);
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
