<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\SpaceModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;
use Lori\UploadImage;

class UserController extends \MainController
{
    // Все пользователи
    function index()
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 40;
        $usersCount = UserModel::getUsersAllCount();
        $users      = UserModel::getUsersAll($page, $limit, $uid['id']);

        Base::PageError404($users);

        $data = [
            'h1'            => lang('Users'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/users',
            'sheet'         => 'users',
            'pagesCount'    => ceil($usersCount / $limit),
            'pNum'          => $page,
            'meta_title'    => lang('Users') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('desc-user-all') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        Request::getHead()->addStyles('/assets/css/users.css');

        return view(PR_VIEW_DIR . '/user/users', ['data' => $data, 'uid' => $uid, 'users' => $users]);
    }

    // Страница участника
    function profile()
    {
        $login = \Request::get('login');
        $user  = UserModel::getUser($login, 'slug');

        // Покажем 404
        Base::PageError404($user);

        $post = PostModel::getPostId($user['my_post']);

        if (!$user['about']) {
            $user['about'] = lang('Riddle') . '...';
        }

        $site_name = Config::get(Config::PARAM_NAME);
        $meta_title = sprintf(lang('title-profile'), $user['login'], $user['name'], $site_name);
        $meta_desc  = sprintf(lang('desc-profile'), $user['login'], $user['about'], $site_name);

        \Request::getHead()->addStyles('/assets/css/users.css');

        if ($user['ban_list'] == 1) {
            \Request::getHead()->addMeta('robots', 'noindex');
        }

        // Просмотры профиля
        if (!isset($_SESSION['usernumbers'])) {
            $_SESSION['usernumbers'] = array();
        }

        if (!isset($_SESSION['usernumbers'][$user['id']])) {
            UserModel::userHits($user['id']);
            $_SESSION['usernumbers'][$user['id']] = $user['id'];
        }

        $uid        = Base::getUid();

        // Ограничение на показ кнопки отправить Pm (ЛС, личные сообщения)
        $button_pm  = accessPm($uid, $user['id'], Config::get(Config::PARAM_TL_ADD_PM));

        $data = [
            'h1'                => $user['login'],
            'sheet'             => 'profile',
            'created_at'        => lang_date($user['created_at']),
            'trust_level'       => UserModel::getUserTrust($user['id']),
            'posts_count'       => UserModel::contentCount($user['id'], 'posts'),
            'answers_count'     => UserModel::contentCount($user['id'], 'answers'),
            'comments_count'    => UserModel::contentCount($user['id'], 'comments'),
            'spaces_user'       => SpaceModel::getUserCreatedSpaces($user['id']),
            'badges'            => UserModel::getBadgeUserAll($user['id']),
            'canonical'         => Config::get(Config::PARAM_URL) . '/u/' . $user['login'],
            'img'               => Config::get(Config::PARAM_URL) . '/uploads/users/avatars/' . $user['avatar'],
            'meta_title'        => $meta_title,
            'meta_desc'         => $meta_desc,
        ];

        return view(PR_VIEW_DIR . '/user/profile', ['data' => $data, 'uid' => $uid, 'user' => $user, 'onepost' => $post, 'button_pm' => $button_pm]);
    }

    // Форма настройки профиля
    function settingPage()
    {
        // Данные участника
        $login  = \Request::get('login');
        $uid    = Base::getUid();
        $user   = UserModel::getUser($uid['login'], 'slug');

        // Ошибочный Slug в Url
        if ($login != $user['login']) {
            redirect('/u/' . $user['login'] . '/setting');
        }

        $data = [
            'h1'            => lang('Setting profile'),
            'sheet'         => 'setting',
            'meta_title'    => lang('Setting profile'),
        ];

        return view(PR_VIEW_DIR . '/user/setting', ['data' => $data, 'uid' => $uid, 'user' => $user]);
    }

    // Изменение профиля
    function settingEdit()
    {
        $name           = \Request::getPost('name');
        $about          = \Request::getPost('about');
        $color          = \Request::getPost('color');
        $website        = \Request::getPost('website');
        $location       = \Request::getPost('location');
        $public_email   = \Request::getPost('public_email');
        $skype          = \Request::getPost('skype');
        $twitter        = \Request::getPost('twitter');
        $telegram       = \Request::getPost('telegram');
        $vk             = \Request::getPost('vk');


        if (!$color) {
            $color  = '#339900';
        }

        $uid        = Base::getUid();
        $redirect   = '/u/' . $uid['login'] . '/setting';
        Base::Limits($name, lang('Name'), '3', '11', $redirect);
        Base::Limits($about, lang('About me'), '0', '255', $redirect);

        if (!filter_var($public_email, FILTER_VALIDATE_EMAIL)) {
            $public_email = '';
        }

        $skype      = substr($skype, 0, 25);
        $twitter    = substr($twitter, 0, 25);
        $telegram   = substr($telegram, 0, 25);
        $vk         = substr($vk, 0, 25);

        $data = [
            'id'            => $uid['id'],
            'name'          => $name,
            'color'         => $color,
            'about'         => empty($about) ? '' : $about,
            'website'       => empty($website) ? '' : $website,
            'location'      => empty($location) ? '' : $location,
            'public_email'  => empty($public_email) ? '' : $public_email,
            'skype'         => empty($skype) ? '' : $skype,
            'twitter'       => empty($twitter) ? '' : $twitter,
            'telegram'      => empty($telegram) ? '' : $telegram,
            'vk'            => empty($vk) ? '' : $vk,
        ];

        UserModel::editProfile($data);

        Base::addMsg(lang('Changes saved'), 'success');
        redirect($redirect);
    }

    // Форма загрузки аватарки
    function settingAvatarPage()
    {
        $uid    = Base::getUid();
        $login  = \Request::get('login');

        // Ошибочный Slug в Url
        if ($login != $uid['login']) {
            redirect('/u/' . $uid['login'] . '/setting/avatar');
        }

        $userInfo           = UserModel::getUser($uid['login'], 'slug');

        $data = [
            'h1'            => lang('Change avatar'),
            'sheet'         => 'setting-ava',
            'meta_title'    => lang('Change avatar'),
            'meta_desc'     => lang('Change avatar page') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        Request::getHead()->addStyles('/assets/css/image-uploader.css');
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        return view(PR_VIEW_DIR . '/user/setting-avatar', ['data' => $data, 'uid' => $uid, 'user' => $userInfo]);
    }

    // Форма изменение пароля
    function settingSecurityPage()
    {
        $uid  = Base::getUid();
        self::accessPage(\Request::get('login'), $uid, '/setting/security');

        $data = [
            'h1'            => lang('Change password'),
            'password'      => '',
            'password2'     => '',
            'password3'     => '',
            'sheet'         => 'setting-pass',
            'meta_title'    => lang('Change password') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        return view(PR_VIEW_DIR . '/user/setting-security', ['data' => $data, 'uid' => $uid]);
    }

    // Изменение аватарки
    function settingAvatarEdit()
    {
        $uid        = Base::getUid();
        $redirect   = '/u/' . $uid['login'] . '/setting/avatar';

        // Запишем img
        $img        = $_FILES['images'];
        $check_img  = $_FILES['images']['name'][0];
        if ($check_img) {
            UploadImage::img($img, $uid['id'], 'user');
        }

        // Баннер
        $cover          = $_FILES['cover'];
        $check_cover    = $_FILES['cover']['name'][0];
        if ($check_cover) {
            UploadImage::cover($cover, $uid['id'], 'user');
        }

        Base::addMsg(lang('Change saved'), 'success');
        redirect($redirect);
    }

    // Изменение пароля
    function settingSecurityEdit()
    {
        $uid  = Base::getUid();
        $password    = \Request::getPost('password');
        $password2   = \Request::getPost('password2');
        $password3   = \Request::getPost('password3');

        $redirect = '/u/' . $uid['login'] . '/setting/security';
        if ($password2 != $password3) {
            Base::addMsg(lang('pass-match-err'), 'error');
            redirect($redirect);
        }

        if (substr_count($password2, ' ') > 0) {
            Base::addMsg(lang('pass-gap-err'), 'error');
            redirect($redirect);
        }

        if (Base::getStrlen($password2) < 8 || Base::getStrlen($password2) > 32) {
            Base::addMsg(lang('pass-length-err'), 'error');
            redirect($redirect);
        }

        // Данные участника
        $account = \Request::getSession('account');
        $userInfo = UserModel::userInfo($account['email']);

        if (!password_verify($password, $userInfo['password'])) {
            Base::addMsg(lang('old-password-err'), 'error');
            redirect($redirect);
        }

        $newpass = password_hash($password2, PASSWORD_BCRYPT);
        UserModel::editPassword($account['user_id'], $newpass);

        Base::addMsg(lang('Password changed'), 'success');
        redirect($redirect);
    }

    // Страница закладок участника
    function userFavorites()
    {
        $uid    = Base::getUid();
        $user   = UserModel::getUser($uid['login'], 'slug');

        self::accessPage(\Request::get('login'), $uid, '/favorite');

        $fav = UserModel::userFavorite($user['id']);

        $result = array();
        foreach ($fav as $ind => $row) {
            $row['post_date']       = (empty($row['post_date'])) ? $row['post_date'] : lang_date($row['post_date']);
            $row['answer_content']  = Content::text($row['answer_content'], 'text');
            $row['date']            = $row['post_date'];
            $row['post']            = PostModel::getPostId($row['answer_post_id']);
            $result[$ind]           = $row;
        }

        $data = [
            'sheet'         => 'favorites',
            'h1'            => lang('Favorites') . ' ' . $uid['login'],
            'meta_title'    => lang('Favorites') . ' ' . $uid['login'] . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        return view(PR_VIEW_DIR . '/user/favorite', ['data' => $data, 'uid' => $uid, 'favorite' => $result]);
    }

    // Удаление обложки
    function userCoverRemove()
    {
        $uid        = Base::getUid();

        $redirect   = '/u/' . $uid['login'] . '/setting/avatar';
        
        self::accessPage(\Request::get('login'), $uid, $redirect);

        $user = UserModel::getUser($uid['login'], 'slug');

        // Удалять может только автор и админ
        if ($user['id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }

        $path_img = HLEB_PUBLIC_DIR . '/uploads/users/cover/';

        // Удалим, кроме дефолтной
        if ($user['cover_art'] != 'cover_art.jpeg') {
            unlink($path_img . $user['cover_art']);
        }

        UserModel::userCoverRemove($user['id']);
        Base::addMsg(lang('Cover removed'), 'success');

        // Если удаляет администрация
        if ($uid['trust_level'] == 5) {
            redirect('/admin/user/' . $user['id'] . '/edit');
        }

        redirect($redirect);
    }

    // Страница черновиков участника
    function userDrafts()
    {
        $uid    = Base::getUid();
        $user   = UserModel::getUser($uid['login'], 'slug');

        self::accessPage(\Request::get('login'), $uid, '/drafts');

        $drafts = UserModel::userDraftPosts($user['id']);

        $data = [
            'sheet'         => 'drafts',
            'h1'            => lang('Drafts') . ' ' . $user['login'],
            'meta_title'    => lang('Drafts') . ' ' . $user['login'] . ' | ' . Config::get(Config::PARAM_NAME)
        ];

        return view(PR_VIEW_DIR . '/user/draft-post', ['data' => $data, 'uid' => $uid, 'drafts' => $drafts]);
    }


    /////////// СИСТЕМА ИНВАЙТОВ ///////////
    // Показ формы инвайта
    public function inviteForm()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Invite'),
            'sheet'         => 'invite',
            'meta_title'    => lang('Invite'),
        ];

        return view(PR_VIEW_DIR . '/user/invite', ['data' => $data, 'uid' => $uid]);
    }

    // Страница инвайтов пользователя
    function invitationPage()
    {
        // Страница участника и данные
        $uid    = Base::getUid();

        self::accessPage(\Request::get('login'), $uid, '/invitation');

        $Invitation = UserModel::InvitationResult($uid['id']);

        $data = [
            'h1'          => lang('Invites'),
            'sheet'         => 'invites',
            'meta_title'    => lang('Invites') . ' | ' . Config::get(Config::PARAM_NAME)
        ];

        return view(PR_VIEW_DIR . '/user/invitation', ['data' => $data, 'uid' => $uid, 'result' => $Invitation]);
    }

    // Создать инвайт
    function invitationCreate()
    {
        // Данные участника
        $uid    = Base::getUid();

        $invitation_email = \Request::getPost('email');

        $redirect = '/u/' . $uid['login'] . '/invitation';

        if (!filter_var($invitation_email, FILTER_VALIDATE_EMAIL)) {
            Base::addMsg(lang('Invalid') . ' email', 'error');
            redirect($redirect);
        }

        $uInfo = UserModel::userInfo($invitation_email);
        if (!empty($uInfo['email'])) {

            if ($uInfo['email']) {
                Base::addMsg(lang('user-already'), 'error');
                redirect($redirect);
            }
        }

        $inv_user = UserModel::InvitationOne($uid['id']);

        if ($inv_user['invitation_email'] == $invitation_email) {
            Base::addMsg(lang('invate-to-replay'), 'error');
            redirect($redirect);
        }

        // + Повторная отправка
        $add_time           = date('Y-m-d H:i:s');
        $invitation_code    = Base::randomString('crypto', 25);
        $add_ip             = Request::getRemoteAddress();

        UserModel::addInvitation($uid['id'], $invitation_code, $invitation_email, $add_time, $add_ip);

        Base::addMsg(lang('Invite created'), 'success');
        redirect($redirect);
    }
    
    public function preferencesPage() 
    {
        $uid    = Base::getUid();

        self::accessPage(\Request::get('login'), $uid, '/preferences');

        $focus_posts = NotificationsModel::getFocusPostsListUser($uid['id']);
        
        $result = array();
        foreach ($focus_posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }
        
        $data = [
            'h1'          => lang('Preferences'),
            'sheet'         => 'preferences',
            'meta_title'    => lang('Preferences') . ' | ' . Config::get(Config::PARAM_NAME)
        ];

        return view(PR_VIEW_DIR . '/user/preferences', ['data' => $data, 'uid' => $uid, 'posts' => $result]);
        
    }
    
    public function accessPage($login, $uid, $redirect) 
    {
        if ($login != $uid['login']) {
            redirect('/u/' . $uid['login'] . $redirect);
        }    
    }
    
}
