<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{NotificationsModel, UserModel, SpaceModel, PostModel};
use Lori\{Content, Config, Base, Validation};

class UserController extends MainController
{
    // Все пользователи
    function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 42;
        $usersCount = UserModel::getUsersAllCount();
        $users      = UserModel::getUsersAll($page, $limit, $uid['user_id']);
        Base::PageError404($users);

        $meta = [
            'canonical'     => Config::get(Config::PARAM_URL) . '/users',
            'sheet'         => 'users',
            'meta_title'    => lang('Users') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('desc-user-all') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'sheet'         => 'users',
            'pagesCount'    => ceil($usersCount / $limit),
            'pNum'          => $page,
            'users'         => $users
        ];

        Request::getHead()->addStyles('/assets/css/users.css');

        return view('/user/users', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Страница участника
    function profile()
    {
        $login = Request::get('login');
        $user  = UserModel::getUser($login, 'slug');

        // Покажем 404
        Base::PageError404($user);

        if (!$user['user_about']) {
            $user['user_about'] = lang('Riddle') . '...';
        }

        $site_name  = Config::get(Config::PARAM_NAME);
        $meta_title = sprintf(lang('title-profile'), $user['user_login'], $user['user_name'], $site_name);
        $meta_desc  = sprintf(lang('desc-profile'), $user['user_login'], $user['user_about'], $site_name);

        Request::getHead()->addStyles('/assets/css/users.css');

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
        $meta = [
            'sheet'             => 'profile',
            'canonical'         => Config::get(Config::PARAM_URL) . '/u/' . $user['user_login'],
            'img'               => Config::get(Config::PARAM_URL) . '/uploads/users/avatars/' . $user['user_avatar'],
            'meta_title'        => $meta_title,
            'meta_desc'         => $meta_desc,
        ];

        $data = [
            'user_created_at'   => lang_date($user['user_created_at']),
            'user_trust_level'  => UserModel::getUserTrust($user['user_id']),
            'count'             => UserModel::contentCount($user['user_id']),
            'spaces_user'       => SpaceModel::getUserCreatedSpaces($user['user_id']),
            'badges'            => UserModel::getBadgeUserAll($user['user_id']),
            'user'              => $user,
            'onepost'           => PostModel::getPostId($user['user_my_post']),
            'button_pm'         => Validation::accessPm($uid, $user['user_id'], Config::get(Config::PARAM_TL_ADD_PM))
        ];

        return view('/user/profile', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Страница закладок участника
    function userFavorites()
    {
        $uid    = Base::getUid();
        $login  = Request::get('login');

        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/favorite');
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

        $meta = [
            'sheet'         => 'favorites',
            'h1'            => lang('Favorites') . ' ' . $uid['user_login'],
            'meta_title'    => lang('Favorites') . ' ' . $uid['user_login'] . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        $data = [
            'sheet'     => 'favorites',
            'favorites' => $result
        ];

        return view('/user/favorite', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Страница черновиков участника
    function userDrafts()
    {
        $uid    = Base::getUid();
        $login  = Request::get('login');

        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/drafts');
        }

        $meta = [
            'sheet'         => 'drafts',
            'h1'            => lang('Drafts') . ' ' . $uid['user_login'],
            'meta_title'    => lang('Drafts') . ' ' . $uid['user_login'] . ' | ' . Config::get(Config::PARAM_NAME)
        ];

        $data = [
            'drafts' => UserModel::userDraftPosts($uid['user_id']),
        ];

        return view('/user/draft-post', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Страница предпочтений пользователя
    public function subscribedPage()
    {
        $uid    = Base::getUid();
        $login  = Request::get('login');

        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/subscribed');
        }

        $focus_posts = NotificationsModel::getFocusPostsListUser($uid['user_id']);

        $result = array();
        foreach ($focus_posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $meta = [
            'sheet'         => 'subscribed',
            'meta_title'    => lang('Subscribed') . ' ' . $uid['user_login'] . ' | ' . Config::get(Config::PARAM_NAME)
        ];

        $data = [
            'h1'    => lang('Subscribed') . ' ' . $uid['user_login'],
            'sheet' => 'subscribed',
            'posts' => $result
        ];

        return view('/user/subscribed', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
