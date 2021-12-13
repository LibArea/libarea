<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{FacetModel, NotificationsModel, PostModel};
use Content, Config, Base, Validation, Translate;

class UserController extends MainController
{
    private $uid;
    
    protected $limit = 42;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }
    
    // Все пользователи
    function index()
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $usersCount = UserModel::getUsersAllCount('all');
        $users      = UserModel::getUsersAll($page, $this->limit, $this->uid['user_id'], 'noban');
        pageError404($users);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('users'),
        ];

        return view(
            '/user/users',
            [
                'meta'  => meta($m, Translate::get('users'), Translate::get('desc-user-all')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => 'users',
                    'pagesCount'    => ceil($usersCount / $this->limit),
                    'pNum'          => $page,
                    'users'         => $users
                ]
            ]
        );
    }

    // Страница участника
    function profile()
    {
        $login = Request::get('login');
        $user  = UserModel::getUser($login, 'slug');

        // Покажем 404
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

        // Просмотры профиля
        if (!isset($_SESSION['usernumbers'])) {
            $_SESSION['usernumbers'] = [];
        }

        if (!isset($_SESSION['usernumbers'][$user['user_id']])) {
            UserModel::userHits($user['user_id']);
            $_SESSION['usernumbers'][$user['user_id']] = $user['user_id'];
        }

        $isBan = '';
        if ($this->uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) {
            Request::getResources()->addBottomScript('/assets/js/admin.js');
            $isBan = UserModel::isBan($user['user_id']);
        }

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/users/avatars/' . $user['user_avatar'],
            'url'        => getUrlByName('user', ['login' => $user['user_login']]),
        ];

        return view(
            '/user/profile',
            [
                'meta'  => meta($m, $meta_title, $meta_desc),
                'uid'   => $this->uid,
                'data'  => [
                    'user_created_at'   => lang_date($user['user_created_at']),
                    'user_trust_level'  => UserModel::getUserTrust($user['user_id']),
                    'count'             => UserModel::contentCount($user['user_id']),
                    'topics'            => FacetModel::getFacetsAll(1, 10, $user['user_id'], 'my'),
                    'badges'            => BadgeModel::getBadgeUserAll($user['user_id']),
                    'user'              => $user,
                    'isBan'             => $isBan,
                    'participation'     => FacetModel::participation($user['user_id']),
                    'post'              => PostModel::getPostId($user['user_my_post']),
                    'button_pm'         => Validation::accessPm($this->uid, $user['user_id'], Config::get('general.tl_add_pm'))
                ]
            ]
        );
    }

    // Страница закладок участника
    function favorites()
    {
        if (Request::get('login') != $this->uid['user_login']) {
            redirect(getUrlByName('user.favorites', ['login' => $this->uid['user_login']]));
        }

        $favorites = UserModel::userFavorite($this->uid['user_id']);

        $result = [];
        foreach ($favorites as $ind => $row) {

            if ($row['favorite_type'] == 1) {
                $row['answer_post_id'] = $row['post_id'];
            }

            $row['answer_content']  = Content::text($row['answer_content'], 'text');
            $row['post']            = PostModel::getPostId($row['answer_post_id']);
            $result[$ind]           = $row;
        }

        return view(
            '/user/favorite',
            [
                'meta'  => meta($m = [], Translate::get('favorites')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'     => 'favorites',
                    'favorites' => $result
                ]
            ]
        );
    }

    // Страница черновиков участника
    function drafts()
    {
        if (Request::get('login') != $this->uid['user_login']) {
            redirect(getUrlByName('user.drafts', ['login' => $this->uid['user_login']]));
        }

        return view(
            '/user/draft',
            [
                'meta'  => meta($m = [], Translate::get('drafts')),
                'uid'   => $this->uid,
                'data'  => [
                    'drafts'    => UserModel::userDraftPosts($this->uid['user_id']),
                    'sheet'     => 'drafts',
                ]
            ]
        );
    }

    // Страница предпочтений пользователя
    public function subscribed()
    {
        if (Request::get('login') != $this->uid['user_login']) {
            redirect(getUrlByName('user.subscribed', ['login' => $this->uid['user_login']]));
        }

        $focus_posts = NotificationsModel::getFocusPostsListUser($this->uid['user_id']);

        $result = [];
        foreach ($focus_posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        return view(
            '/user/subscribed',
            [
                'meta'  => meta($m = [], Translate::get('subscribed')),
                'uid'   => $this->uid,
                'data'  => [
                    'h1'    => Translate::get('subscribed') . ' ' . $this->uid['user_login'],
                    'sheet' => 'subscribed',
                    'posts' => $result
                ]
            ]
        );
    }
    
    public function card()
    {
        $user_id    = Request::getPostInt('user_id');
        $user       = UserModel::getUser($user_id, 'id');
        $post       = PostModel::getPostId($user['user_my_post']);
        $badges     = BadgeModel::getBadgeUserAll($user_id);

        includeTemplate('/content/user/card', ['user' => $user, 'uid' => $this->uid, 'post' => $post, 'badges' => $badges]);  
    }
   
}
