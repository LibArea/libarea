<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{SettingModel, UserModel};
use UploadImage, Validation, Translate, Tpl, Meta, Html, UserData;

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

        return Tpl::agRender(
            '/user/setting/setting',
            [
                'meta'  => Meta::get($m = [], Translate::get('setting')),
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

        $redirect   = getUrlByName('setting');
        Validation::Length($name, Translate::get('name'), '0', '11', $redirect);
        Validation::Length($about, Translate::get('about me'), '0', '255', $redirect);

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
                'trust_level'          => $user['trust_level'],
                'updated_at'           => date('Y-m-d H:i:s'),
                'color'                => Request::getPostString('color', '#339900'),
                'about'                => $about,
                'template'             => $template ?? 'default',
                'lang'                 => $lang ?? 'ru',
                'whisper'              => $user['whisper'] ?? null,
                'website'              => Request::getPostString('website', null),
                'location'             => Request::getPostString('location', null),
                'public_email'         => $public_email ?? null,
                'skype'                => Request::getPostString('skype', null),
                'telegram'             => Request::getPostString('telegram', null),
                'vk'                   => Request::getPostString('vk', null),
            ]
        );


        Html::addMsg('change.saved', 'success');
        redirect($redirect);
    }

    // Avatar and cover upload form
    // Форма загрузки аватарки и обложики
    function avatarForm()
    {
        Request::getResources()->addBottomScript('/assets/js/uploads.js');

        return Tpl::agRender(
            '/user/setting/avatar',
            [
                'meta'  => Meta::get($m = [], Translate::get('edit')),
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

        Html::addMsg('change.saved', 'success');
        redirect('/setting/avatar');
    }

    // Change password form
    // Форма изменение пароля
    function securityForm()
    {
        return Tpl::agRender(
            '/user/setting/security',
            [
                'meta'  => Meta::get($m = [], sprintf(Translate::get('edit.option'), Translate::get('password'))),
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
            Html::addMsg('pass.match.err', 'error');
            redirect($redirect);
        }

        if (substr_count($password2, ' ') > 0) {
            Html::addMsg('password.spaces', 'error');
            redirect($redirect);
        }

        Validation::Length($password2, Translate::get('password'), 8, 32, $redirect);

        $userInfo   = UserModel::userInfo($this->user['email']);
        if (!password_verify($password, $userInfo['password'])) {
            Html::addMsg('old.password.err', 'error');
            redirect($redirect);
        }

        $newpass = password_hash($password2, PASSWORD_BCRYPT);

        SettingModel::editPassword(['id' => $this->user['id'], 'password' => $newpass]);

        Html::addMsg('password changed', 'success');

        redirect($redirect);
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
        return Tpl::agRender(
            '/user/setting/notifications',
            [
                'meta'  => Meta::get($m = [], Translate::get('notifications')),
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

        Html::addMsg('change.saved', 'success');
        redirect('/setting/notifications');
    }
}
