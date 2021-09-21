<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{UserModel, NotificationsModel};
use Agouti\{Config, Base, UploadImage, Validation};

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
        $user = UserModel::getUser($this->uid['user_id'], 'id');
        Base::accountBan($user);

        $meta = [
            'sheet'         => 'setting',
            'meta_title'    => lang('Setting profile'),
        ];

        $data = [
            'sheet'         => 'setting',
            'user'          => $user,
        ];

        return view('/user/setting/setting', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
    }

    // Изменение профиля
    function edit()
    {
        $name           = Request::getPost('name');
        $about          = Request::getPost('about');
        $public_email   = Request::getPost('public_email');

        $redirect       = getUrlByName('setting', ['login' => $this->uid['user_login']]);

        Validation::Limits($name, lang('Name'), '3', '11', $redirect);
        Validation::Limits($about, lang('About me'), '0', '255', $redirect);
        
        if ($public_email) {
            Validation::checkEmail($public_email, $redirect);
        }
        
        $data = [
            'user_id'           => $this->uid['user_id'],
            'user_name'         => $name,
            'user_updated_at'   => date('Y-m-d H:i:s'),
            'user_color'        => Request::getPostString('color', '#339900'),
            'user_about'        => $about,
            'user_website'      => Request::getPostString('website', ''),
            'user_location'     => Request::getPostString('location', ''),
            'user_public_email' => $public_email,
            'user_skype'        => Request::getPostString('skype', ''),
            'user_twitter'      => Request::getPostString('twitter', ''),
            'user_telegram'     => Request::getPostString('telegram', ''),
            'user_vk'           => Request::getPostString('vk', ''),
        ];

        UserModel::editProfile($data);

        addMsg(lang('Changes saved'), 'success');
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

        $meta = [
            'sheet'         => 'avatar',
            'meta_title'    => lang('Change avatar'),
        ];

        $data = [
            'sheet' => 'avatar',
            'user'  => UserModel::getUser($this->uid['user_login'], 'slug'),
        ];

        return view('/user/setting/avatar', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
    }

    // Форма изменение пароля
    function securityForm()
    {
        $login  = Request::get('login');

        if ($login != $this->uid['user_login']) {
            redirect(getUrlByName('setting.security', ['login' => $this->uid['user_login']]));
        }

        $meta = [
            'sheet'         => 'security',
            'meta_title'    => lang('Change password') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        $data = [
            'password'      => '',
            'password2'     => '',
            'password3'     => '',
            'sheet'         => 'security',
        ];

        return view('/user/setting/security', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
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

        addMsg(lang('Change saved'), 'success');
        redirect($redirect);
    }

    // Изменение пароля
    function securityEdit()
    {
        $password    = Request::getPost('password');
        $password2   = Request::getPost('password2');
        $password3   = Request::getPost('password3');
 
        $redirect = getUrlByName('setting.security', ['login' => $this->uid['user_login']]);
        if ($password2 != $password3) {
            addMsg(lang('pass-match-err'), 'error');
            redirect($redirect);
        }

        if (substr_count($password2, ' ') > 0) {
            addMsg(lang('pass-gap-err'), 'error');
            redirect($redirect);
        }

        Validation::Limits($password2, lang('Password'), 8, 32, $redirect);

        // Данные участника
        $account    = Request::getSession('account');
        $userInfo   = UserModel::userInfo($account['user_email']);

        if (!password_verify($password, $userInfo['user_password'])) {
            addMsg(lang('old-password-err'), 'error');
            redirect($redirect);
        }

        $newpass = password_hash($password2, PASSWORD_BCRYPT);
        UserModel::editPassword($account['user_id'], $newpass);

        addMsg(lang('Password changed'), 'success');
        redirect($redirect);
    }

    // Удаление обложки
    function userCoverRemove()
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
        UserModel::userCoverRemove($user['user_id'], $date);
        addMsg(lang('Cover removed'), 'success');

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

        $meta = [
            'sheet'         => 'notifications',
            'meta_title'    => lang('Notifications'),
        ];

        $data = [
            'sheet'     => 'notifications',
            'setting'   => NotificationsModel::getUserSetting($user['user_id']),
        ];

        return view('/user/setting/notifications', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
    }

    function notificationsEdit()
    {
        $data = [
            'setting_user_id'           => $this->uid['user_id'],
            'setting_email_pm'          => Request::getPostInt('setting_email_pm'),
            'setting_email_appealed'    => Request::getPostInt('setting_email_appealed'),
        ];

        NotificationsModel::setUserSetting($data, $this->uid['user_id']);
        addMsg(lang('Change saved'), 'success');

        redirect(getUrlByName('setting.notifications', ['login' => $this->uid['user_login']]));
    }
}
