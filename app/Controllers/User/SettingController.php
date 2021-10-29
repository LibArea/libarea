<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{SettingModel, UserModel};
use Base, UploadImage, Validation, Translate;

class SettingController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Форма настройки профиля
    function settingForm()
    {
        // Данные участника
        $login  = Request::get('login');
        $user   = UserModel::getUser($this->uid['user_login'], 'slug');

        // Ошибочный Slug в Url
        if ($login != $user['user_login']) {
            redirect(getUrlByName('setting', ['login' => $user['user_login']]));
        }

        // Если пользователь забанен
        Base::accountBan($user);

        return view(
            '/user/setting/setting',
            [
                'meta'  => meta($m = [], Translate::get('setting')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => 'settings',
                    'user'          => $user,
                ]
            ]
        );
    }

    // Изменение профиля
    function edit()
    {
        $name               = Request::getPost('name');
        $about              = Request::getPost('about');
        $public_email       = Request::getPost('public_email');
        $user_template      = Request::getPost('user_template');
        $user_lang          = Request::getPost('user_lang');

        $redirect   = getUrlByName('setting', ['login' => $this->uid['user_login']]);
        Validation::Limits($name, Translate::get('name'), '3', '11', $redirect);
        Validation::Limits($about, Translate::get('about me'), '0', '255', $redirect);

        if ($public_email) {
            Validation::checkEmail($public_email, $redirect);
        }

        $_SESSION['account']['user_template']   = $user_template ?? 'default';
        $_SESSION['account']['user_lang']       = $user_lang ?? 'ru';

        $user   = UserModel::getUser($this->uid['user_id'], 'id');

        $data = [
            'user_id'                   => $this->uid['user_id'],
            'user_email'                => $user['user_email'],
            'user_login'                => $user['user_login'],
            'user_name'                 => $name,
            'user_activated'            => $user['user_activated'],
            'user_limiting_mode'        => $user['user_limiting_mode'],
            'user_trust_level'          => $user['user_trust_level'],
            'user_updated_at'           => date('Y-m-d H:i:s'),
            'user_color'                => Request::getPostString('color', '#339900'),
            'user_about'                => $about,
            'user_template'             => $user_template ?? 'default',
            'user_lang'                 => $user_lang,
            'user_whisper'              => $user['user_whisper'],
            'user_website'              => Request::getPostString('website', ''),
            'user_location'             => Request::getPostString('location', ''),
            'user_public_email'         => $public_email,
            'user_skype'                => Request::getPostString('skype', ''),
            'user_twitter'              => Request::getPostString('twitter', ''),
            'user_telegram'             => Request::getPostString('telegram', ''),
            'user_vk'                   => Request::getPostString('vk', ''),
        ];

        SettingModel::editProfile($data);

        addMsg(Translate::get('changes saved'), 'success');
        redirect($redirect);
    }

    // Форма загрузки аватарки
    function avatarForm()
    {
        $login  = Request::get('login');

        // Ошибочный Slug в Url
        if ($login != $this->uid['user_login']) {
            redirect(getUrlByName('setting.avatar', ['login' => $this->uid['user_login']]));
        }

        Request::getHead()->addStyles('/assets/css/image-uploader.css');
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        return view(
            '/user/setting/avatar',
            [
                'meta'  => meta($m = [], Translate::get('change avatar')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet' => 'avatar',
                    'user'  => UserModel::getUser($this->uid['user_login'], 'slug'),
                ]
            ]
        );
    }

    // Изменение аватарки
    function avatarEdit()
    {
        $redirect   = getUrlByName('setting.avatar', ['login' => $this->uid['user_login']]);

        // Запишем img
        $img        = $_FILES['images'];
        $check_img  = $_FILES['images']['name'][0];
        if ($check_img) {
            $new_img = UploadImage::img($img, $this->uid['user_id'], 'user');
            $_SESSION['account']['user_avatar'] = $new_img;
        }

        // Баннер
        $cover          = $_FILES['cover'];
        $check_cover    = $_FILES['cover']['name'][0];
        if ($check_cover) {
            UploadImage::cover($cover, $this->uid['user_id'], 'user');
        }

        addMsg(Translate::get('change saved'), 'success');
        redirect($redirect);
    }

    // Форма изменение пароля
    function securityForm()
    {
        $login  = Request::get('login');

        if ($login != $this->uid['user_login']) {
            redirect(getUrlByName('setting.security', ['login' => $this->uid['user_login']]));
        }

        return view(
            '/user/setting/security',
            [
                'meta'  => meta($m = [], Translate::get('change password')),
                'uid'   => $this->uid,
                'data'  => [
                    'password'      => '',
                    'password2'     => '',
                    'password3'     => '',
                    'sheet'         => 'security',
                ]
            ]
        );
    }

    // Изменение пароля
    function securityEdit()
    {
        $password    = Request::getPost('password');
        $password2   = Request::getPost('password2');
        $password3   = Request::getPost('password3');

        $redirect = getUrlByName('setting.security', ['login' => $this->uid['user_login']]);
        if ($password2 != $password3) {
            addMsg(Translate::get('pass-match-err'), 'error');
            redirect($redirect);
        }

        if (substr_count($password2, ' ') > 0) {
            addMsg(Translate::get('pass-gap-err'), 'error');
            redirect($redirect);
        }

        Validation::Limits($password2, Translate::get('password'), 8, 32, $redirect);

        // Данные участника
        $account    = Request::getSession('account');
        $userInfo   = UserModel::userInfo($account['user_email']);

        if (!password_verify($password, $userInfo['user_password'])) {
            addMsg(Translate::get('old-password-err'), 'error');
            redirect($redirect);
        }

        $newpass = password_hash($password2, PASSWORD_BCRYPT);
        SettingModel::editPassword($account['user_id'], $newpass);

        addMsg(Translate::get('password changed'), 'success');
        redirect($redirect);
    }

    // Удаление обложки
    function coverRemove()
    {
        $login      = Request::get('login');
        $redirect   = getUrlByName('setting.avatar', ['login' => $this->uid['user_login']]);

        if ($login != $this->uid['user_login']) {
            redirect($redirect);
        }

        $user = UserModel::getUser($this->uid['user_login'], 'slug');

        // Удалять может только автор и админ
        if ($user['user_id'] != $this->uid['user_id'] && $this->uid['user_trust_level'] != 5) {
            redirect('/');
        }

        // Удалим, кроме дефолтной
        if ($user['user_cover_art'] != 'cover_art.jpeg') {
            unlink(HLEB_PUBLIC_DIR . AG_PATH_USERS_COVER . $user['user_cover_art']);
            unlink(HLEB_PUBLIC_DIR . AG_PATH_USERS_SMALL_COVER . $user['user_cover_art']);
        }

        $date = date('Y-m-d H:i:s');
        SettingModel::coverRemove($user['user_id'], $date);
        addMsg(Translate::get('cover removed'), 'success');

        // Если удаляет администрация
        if ($this->uid['user_trust_level'] == 5) {
            redirect('/admin/users/' . $user['user_id'] . '/edit');
        }

        redirect($redirect);
    }

    // Форма настройки предпочтений участника
    function notificationsForm()
    {
        // Данные участника
        $login  = Request::get('login');
        $user   = UserModel::getUser($this->uid['user_login'], 'slug');

        // Ошибочный Slug в Url
        if ($login != $user['user_login']) {
            redirect(getUrlByName('setting.notifications', ['login' => $user['user_login']]));
        }

        // Если пользователь забанен
        $user = UserModel::getUser($this->uid['user_id'], 'id');
        Base::accountBan($user);

        return view(
            '/user/setting/notifications',
            [
                'meta'  => meta($m = [], Translate::get('notifications')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'     => 'notifications',
                    'setting'   => SettingModel::getNotifications($user['user_id']),
                ]
            ]
        );
    }

    function notificationsEdit()
    {
        $data = [
            'setting_user_id'           => $this->uid['user_id'],
            'setting_email_pm'          => Request::getPostInt('setting_email_pm'),
            'setting_email_appealed'    => Request::getPostInt('setting_email_appealed'),
        ];

        SettingModel::setNotifications($data, $this->uid['user_id']);
        addMsg(Translate::get('change saved'), 'success');

        redirect(getUrlByName('setting.notifications', ['login' => $this->uid['user_login']]));
    }
}
