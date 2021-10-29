<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{TopicModel, NotificationsModel, PostModel};
use Content, Config, Base, Validation, Translate;

class UserController extends MainController
{
    // Все пользователи
    function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 42;
        $usersCount = UserModel::getUsersAllCount('all');
        $users      = UserModel::getUsersAll($page, $limit, $uid['user_id'], 'noban');
        Base::PageError404($users);

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
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => 'users',
                    'pagesCount'    => ceil($usersCount / $limit),
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
        Base::PageError404($user);

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
            $_SESSION['usernumbers'] = array();
        }

        if (!isset($_SESSION['usernumbers'][$user['user_id']])) {
            UserModel::userHits($user['user_id']);
            $_SESSION['usernumbers'][$user['user_id']] = $user['user_id'];
        }

        $uid = Base::getUid();
        $isBan = '';
        if ($uid['user_trust_level'] > 4) {
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
                'uid'   => $uid,
                'data'  => [
                    'user_created_at'   => lang_date($user['user_created_at']),
                    'user_trust_level'  => UserModel::getUserTrust($user['user_id']),
                    'count'             => UserModel::contentCount($user['user_id']),
                    'topics'            => TopicModel::getTopicsAll(1, 10, $user['user_id'], 'subscription'),
                    'badges'            => BadgeModel::getBadgeUserAll($user['user_id']),
                    'user'              => $user,
                    'isBan'             => $isBan,
                    'participation'     => TopicModel::participation($user['user_id']),
                    'post'              => PostModel::getPostId($user['user_my_post']),
                    'button_pm'         => Validation::accessPm($uid, $user['user_id'], Config::get('general.tl_add_pm'))
                ]
            ]
        );
    }

    // Страница закладок участника
    function userFavorites()
    {
        $uid    = Base::getUid();
        $login  = Request::get('login');

        if ($login != $uid['user_login']) {
            redirect(getUrlByName('favorites', ['login' => $uid['user_login']]));
        }

        $favorites = UserModel::userFavorite($uid['user_id']);

        $result = array();
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
                'uid'   => $uid,
                'data'  => [
                    'sheet'     => 'favorites',
                    'favorites' => $result
                ]
            ]
        );
    }

    // Страница черновиков участника
    function userDrafts()
    {
        $uid    = Base::getUid();
        $login  = Request::get('login');

        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/drafts');
        }

        return view(
            '/user/draft-post',
            [
                'meta'  => meta($m = [], Translate::get('drafts')),
                'uid'   => $uid,
                'data'  => [
                    'drafts' => UserModel::userDraftPosts($uid['user_id']),
                ]
            ]
        );
    }

    // Страница предпочтений пользователя
    public function subscribedPage()
    {
        $uid    = Base::getUid();
        $login  = Request::get('login');

        if ($login != $uid['user_login']) {
            redirect(getUrlByName('subscribed', ['login' => $uid['user_login']]));
        }

        $focus_posts = NotificationsModel::getFocusPostsListUser($uid['user_id']);

        $result = array();
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
                'uid'   => $uid,
                'data'  => [
                    'h1'    => Translate::get('subscribed') . ' ' . $uid['user_login'],
                    'sheet' => 'subscribed',
                    'posts' => $result
                ]
            ]
        );
    }
}
