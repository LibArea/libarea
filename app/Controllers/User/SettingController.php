<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{SettingModel, UserModel};
use UploadImage, Validation, Meta, UserData;

class SettingController extends Controller
{
    function index()
    {
        switch (Request::get('type')) {
            case 'avatar':
                return $this->avatarForm();
                break;
            case 'security':
                return $this->securityForm();
                break;
            case 'notifications':
                return $this->notificationForm();
                break;
            case 'cover_remove':
                return $this->coverRemove();
                break;
            default:
                return $this->settingForm();
                break;
        }
    }


    function change()
    {
        switch (Request::get('type')) {
            case 'avatar':
                return $this->avatarEdit();
                break;
            case 'security':
                return $this->securityEdit();
                break;
            case 'notification':
                return $this->notificationEdit();
                break;
            default:
                return $this->edit();
                break;
        }
    }

    // Profile setup form
    // Форма настройки профиля
    function settingForm()
    {
        $user   = UserModel::getUser($this->user['login'], 'slug');

        return $this->render(
            '/user/setting/setting',
            [
                'meta'  => Meta::get(__('app.setting')),
                'data'  => [
                    'sheet'         => 'settings',
                    'type'          => 'user',
                    'user'          => $user,
                ]
            ]
        );
    }

    function edit()
    {
        $data = Request::getPost();

        if (!Validation::length($data['name'], 0, 11)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.name') . '»'])]);
        }

        if (!Validation::length($data['about'], 0, 255)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.about') . '»'])]);
        }

        if ($data['public_email']) {
            if (!filter_var($data['public_email'], FILTER_VALIDATE_EMAIL)) {
                return json_encode(['error' => 'error', 'text' => __('msg.email_correctness')]);
            }
        }

        $user   = UserModel::getUser($this->user['id'], 'id');

        SettingModel::edit(
            [
                'id'                   => $this->user['id'],
                'email'                => $user['email'],
                'login'                => $user['login'],
                'name'                 => $data['name'],
                'activated'            => $user['activated'],
                'limiting_mode'        => $user['limiting_mode'],
                'scroll'               => Request::getPostInt('scroll'),
                'trust_level'          => $user['trust_level'],
                'updated_at'           => date('Y-m-d H:i:s'),
                'color'                => Request::getPostString('color', '#339900'),
                'about'                => $data['about'],
                'template'             => $data['template'] ?? 'default',
                'lang'                 => $data['lang'] ?? 'ru',
                'whisper'              => $user['whisper'] ?? '',
                'website'              => Request::getPostString('website', null),
                'location'             => Request::getPostString('location', null),
                'public_email'         => $data['public_email'] ?? null,
                'skype'                => Request::getPostString('skype', null),
                'telegram'             => Request::getPostString('telegram', null),
                'vk'                   => Request::getPostString('vk', null),
            ]
        );

        return true;
    }

    // Avatar and cover upload form
    // Форма загрузки аватарки и обложики
    function avatarForm()
    {
        Request::getResources()->addBottomScript('/assets/js/uploads.js');

        return $this->render(
            '/user/setting/avatar',
            [
                'meta'  => Meta::get(__('app.avatar')),
                'data'  => [
                    'sheet' => 'avatar',
                    'type'  => 'user',
                    'user'  => UserModel::getUser($this->user['login'], 'slug'),
                ]
            ]
        );
    }

    function avatarEdit()
    {
        $img        = $_FILES['images'];
        $check_img  = $_FILES['images']['name'];
        if ($check_img) {
            UploadImage::img($img, $this->user['id'], 'user');
        }

        $cover          = $_FILES['cover'];
        $check_cover    = $_FILES['cover']['name'];
        if ($check_cover) {
            UploadImage::cover($cover, $this->user['id'], 'user');
        }

        Validation::ComeBack('msg.change_saved', 'success', '/setting/avatar');
    }

    // Change password form
    // Форма изменение пароля
    function securityForm()
    {
        return $this->render(
            '/user/setting/security',
            [
                'meta'  => Meta::get(__('app.security')),
                'data'  => [
                    'password'      => '',
                    'password2'     => '',
                    'password3'     => '',
                    'sheet'         => 'security',
                    'type'          => 'user',
                ]
            ]
        );
    }

    function securityEdit()
    {
        $password    = Request::getPost('password');
        $password2   = Request::getPost('password2');
        $password3   = Request::getPost('password3');

        $redirect   = '/setting/security';
        if ($password2 != $password3) {
            return json_encode(['error' => 'error', 'text' => __('msg.pass_match_err')]);
        }

        if (substr_count($password2, ' ') > 0) {
            return json_encode(['error' => 'error', 'text' => __('msg.password_spaces')]);
        }

        if (!Validation::length($password2, 8, 32)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.password') . '»'])]);
        }

        $userInfo   = UserModel::userInfo($this->user['email']);
        if (!password_verify($password, $userInfo['password'])) {
            return json_encode(['error' => 'error', 'text' => __('msg.old_error')]);
        }

        $newpass = password_hash($password2, PASSWORD_BCRYPT);

        SettingModel::editPassword(['id' => $this->user['id'], 'password' => $newpass]);

        return true;
    }

    // Cover Removal
    // Удаление обложки
    function coverRemove()
    {
        $user = UserModel::getUser($this->user['login'], 'slug');

        // Удалять может только автор и админ
        if ($user['id'] != $this->user['id'] && UserData::checkAdmin()) {
            redirect('/');
        }

        // Удалим, кроме дефолтной
        if ($user['cover_art'] != 'cover_art.jpeg') {
            unlink(HLEB_PUBLIC_DIR . PATH_USERS_COVER . $user['cover_art']);
            unlink(HLEB_PUBLIC_DIR . PATH_USERS_SMALL_COVER . $user['cover_art']);
        }

        SettingModel::coverRemove(
            [
                'id' => $user['id'],
                'updated_at' => date('Y-m-d H:i:s'),
                'cover_art' => 'cover_art.jpeg'
            ]
        );

        // Если удаляет администрация
        if (UserData::checkAdmin()) {
            redirect('/mod/admin/users/' . $user['id'] . '/edit');
        }

        redirect('/setting/avatar');
    }

    // Member preference setting form
    // Форма настройки предпочтений участника
    function notificationForm()
    { //print_r(SettingModel::getNotifications($this->user['id']));
        return $this->render(
            '/user/setting/notifications',
            [
                'meta'  => Meta::get(__('app.notifications')),
                'data'  => [
                    'sheet' => 'notifications',
                    'type'  => 'user',
                    'notif' => SettingModel::getNotifications($this->user['id']),
                ]
            ]
        );
    }

    function notificationEdit()
    {
        SettingModel::setNotifications(
            [
                'setting_user_id'           => $this->user['id'],
                'setting_email_pm'          => Request::getPostInt('setting_email_pm'),
                'setting_email_appealed'    => Request::getPostInt('setting_email_appealed'),
                'setting_email_post'        => 0,
                'setting_email_answer'      => 0,
                'setting_email_comment'     => 0,
            ]
        );

        return true;
    }
}
