<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{SettingModel, UserModel};
use UploadImage, Validation, Tpl, Meta, UserData;

class SettingController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

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
            default:
                return $this->settingForm();
                break;
        }
    }

    // Profile setup form
    // Форма настройки профиля
    function settingForm()
    {
        $user   = UserModel::getUser($this->user['login'], 'slug');

        return Tpl::LaRender(
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
        $name           = Request::getPost('name');
        $about          = Request::getPost('about');
        $public_email   = Request::getPost('public_email');
        $template       = Request::getPost('template');
        $lang           = Request::getPost('lang');

        $redirect   = url('setting');
        Validation::Length($name, 'name', '0', '11', $redirect);
        Validation::Length($about, 'about', '0', '255', $redirect);

        if ($public_email) {
            Validation::Email($public_email, $redirect);
        }

        $user   = UserModel::getUser($this->user['id'], 'id');

        SettingModel::edit(
            [
                'id'                   => $this->user['id'],
                'email'                => $user['email'],
                'login'                => $user['login'],
                'name'                 => $name,
                'activated'            => $user['activated'],
                'limiting_mode'        => $user['limiting_mode'],
                'scroll'               => Request::getPostInt('scroll'),
                'trust_level'          => $user['trust_level'],
                'updated_at'           => date('Y-m-d H:i:s'),
                'color'                => Request::getPostString('color', '#339900'),
                'about'                => $about,
                'template'             => $template ?? 'default',
                'lang'                 => $lang ?? 'ru',
                'whisper'              => $user['whisper'] ?? '',
                'website'              => Request::getPostString('website', null),
                'location'             => Request::getPostString('location', null),
                'public_email'         => $public_email ?? null,
                'skype'                => Request::getPostString('skype', null),
                'telegram'             => Request::getPostString('telegram', null),
                'vk'                   => Request::getPostString('vk', null),
            ]
        );

        Validation::ComeBack('msg.change_saved', 'success', $redirect);
    }

    // Avatar and cover upload form
    // Форма загрузки аватарки и обложики
    function avatarForm()
    {
        Request::getResources()->addBottomScript('/assets/js/uploads.js');

        return Tpl::LaRender(
            '/user/setting/avatar',
            [
                'meta'  => Meta::get(__('app.edit')),
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
        return Tpl::LaRender(
            '/user/setting/security',
            [
                'meta'  => Meta::get(__('app.edit_option', ['name' => __('app.password')])),
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
            Validation::ComeBack('msg.pass_match_err', 'success', $redirect);
        }

        if (substr_count($password2, ' ') > 0) {
            Validation::ComeBack('msg.password_spaces', 'error', $redirect);
        }

        Validation::Length($password2, 'password', 8, 32, $redirect);

        $userInfo   = UserModel::userInfo($this->user['email']);
        if (!password_verify($password, $userInfo['password'])) {
            Validation::ComeBack('msg.old_error', 'error', $redirect);
        }

        $newpass = password_hash($password2, PASSWORD_BCRYPT);

        SettingModel::editPassword(['id' => $this->user['id'], 'password' => $newpass]);

        Validation::ComeBack('msg.password_changed', 'error', $redirect);
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
            redirect('/admin/users/' . $user['id'] . '/edit');
        }

        redirect('/setting/avatar');
    }

    // Member preference setting form
    // Форма настройки предпочтений участника
    function notificationForm()
    {
        return Tpl::LaRender(
            '/user/setting/notifications',
            [
                'meta'  => Meta::get(__('app.notifications')),
                'data'  => [
                    'sheet'     => 'notifications',
                    'type'      => 'user',
                    'setting'   => SettingModel::getNotifications($this->user['id']),
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

        Validation::ComeBack('msg.password_changed', 'success', '/setting/notifications');
    }
}
