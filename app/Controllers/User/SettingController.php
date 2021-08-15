<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use Lori\Config;
use Lori\Base;
use Lori\UploadImage;

class SettingController extends \MainController
{
    // Форма настройки профиля
    function settingForm()
    {
        // Данные участника
        $login  = \Request::get('login');
        $uid    = Base::getUid();
        $user   = UserModel::getUser($uid['user_login'], 'slug');

        // Ошибочный Slug в Url
        if ($login != $user['user_login']) {
            redirect('/u/' . $user['user_login'] . '/setting');
        }

        // Если пользователь забанен
        $user = UserModel::getUser($uid['user_id'], 'id');
        Base::accountBan($user);

        $data = [
            'h1'            => lang('Setting profile'),
            'sheet'         => 'setting',
            'meta_title'    => lang('Setting profile'),
        ];

        return view(PR_VIEW_DIR . '/user/setting', ['data' => $data, 'uid' => $uid, 'user' => $user]);
    }

    // Изменение профиля
    function edit()
    {
        $name           = \Request::getPost('name');
        $about          = \Request::getPost('about');
        $public_email   = \Request::getPost('public_email');

        $uid            = Base::getUid();
        $redirect       = '/u/' . $uid['user_login'] . '/setting';
        
        Base::Limits($name, lang('Name'), '3', '11', $redirect);
        Base::Limits($about, lang('About me'), '0', '255', $redirect);

        if (!filter_var($public_email, FILTER_VALIDATE_EMAIL)) {
            $public_email = '';
        }

        $data = [
            'user_id'            => $uid['user_id'],
            'user_name'          => $name,
            'user_color'         => \Request::getPostString('color', '#339900'),
            'user_about'         => $about,
            'user_website'       => \Request::getPostString('website', ''),
            'user_location'      => \Request::getPostString('location', ''),
            'user_public_email'  => $public_email,
            'user_skype'         => \Request::getPostString('skype', ''),
            'user_twitter'       => \Request::getPostString('twitter', ''),
            'user_telegram'      => \Request::getPostString('telegram', ''),
            'user_vk'            => \Request::getPostString('vk', ''),
        ];

        UserModel::editProfile($data);

        Base::addMsg(lang('Changes saved'), 'success');
        redirect($redirect);
    }

    // Форма загрузки аватарки
    function avatarForm()
    {
        $uid    = Base::getUid();
        $login  = \Request::get('login');

        // Ошибочный Slug в Url
        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/setting/avatar');
        }

        $userInfo           = UserModel::getUser($uid['user_login'], 'slug');

        $data = [
            'h1'            => lang('Change avatar'),
            'sheet'         => 'setting-ava',
            'meta_title'    => lang('Change avatar')
        ];

        Request::getHead()->addStyles('/assets/css/image-uploader.css');
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        return view(PR_VIEW_DIR . '/user/setting-avatar', ['data' => $data, 'uid' => $uid, 'user' => $userInfo]);
    }

    // Форма изменение пароля
    function securityForm()
    {
        $uid  = Base::getUid();
        $login  = \Request::get('login');

        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/setting/security');
        }

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
    function avatarEdit()
    {
        $uid        = Base::getUid();
        $redirect   = '/u/' . $uid['user_login'] . '/setting/avatar';

        // Запишем img
        $img        = $_FILES['images'];
        $check_img  = $_FILES['images']['name'][0];
        if ($check_img) {
            $new_img = UploadImage::img($img, $uid['user_id'], 'user');
            $_SESSION['account']['user_avatar'] = $new_img;
        }

        // Баннер
        $cover          = $_FILES['cover'];
        $check_cover    = $_FILES['cover']['name'][0];
        if ($check_cover) {
            UploadImage::cover($cover, $uid['user_id'], 'user');
        }

        Base::addMsg(lang('Change saved'), 'success');
        redirect($redirect);
    }

    // Изменение пароля
    function securityEdit()
    {
        $uid  = Base::getUid();
        $password    = \Request::getPost('password');
        $password2   = \Request::getPost('password2');
        $password3   = \Request::getPost('password3');

        $redirect = '/u/' . $uid['user_login'] . '/setting/security';
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

    // Удаление обложки
    function userCoverRemove()
    {
        $uid        = Base::getUid();

        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/setting/avatar');
        }

        $user = UserModel::getUser($uid['user_login'], 'slug');

        // Удалять может только автор и админ
        if ($user['user_id'] != $uid['user_id'] && $uid['user_trust_level'] != 5) {
            redirect('/');
        }

        $path_img = HLEB_PUBLIC_DIR . '/uploads/users/cover/';

        // Удалим, кроме дефолтной
        if ($user['user_cover_art'] != 'cover_art.jpeg') {
            unlink($path_img . $user['user_cover_art']);
        }

        UserModel::userCoverRemove($user['id']);
        Base::addMsg(lang('Cover removed'), 'success');

        // Если удаляет администрация
        if ($uid['user_trust_level'] == 5) {
            redirect('/admin/user/' . $user['user_id'] . '/edit');
        }

        redirect($redirect);
    }
}
