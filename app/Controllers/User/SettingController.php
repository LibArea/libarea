<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{SettingModel, UserModel};
use UploadImage, Meta, UserData, Img;

use App\Validate\RulesUserSetting;

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
        return $this->render(
            '/user/setting/setting',
            'base',
            [
                'meta'  => Meta::get(__('app.setting')),
                'data'  => [
                    'user' => UserModel::getUser($this->user['login'], 'slug'),
                ]
            ]
        );
    }

    function edit()
    {
        RulesUserSetting::rulesSetting($data = Request::getPost());

        $user = UserModel::getUser($this->user['id'], 'id');

        SettingModel::edit(
            [
                'id'                   => $this->user['id'],
                'email'                => $user['email'],
                'login'                => $user['login'],
                'name'                 => $data['name'],
                'activated'            => $user['activated'],
                'limiting_mode'        => $user['limiting_mode'],
                'scroll'               => Request::getPost('scroll') == 'on' ? 1 : 0,
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
                'github'               => Request::getPostString('github', null),
                'skype'                => Request::getPostString('skype', null),
                'telegram'             => Request::getPostString('telegram', null),
                'vk'                   => Request::getPostString('vk', null),
            ]
        );

        is_return(__('msg.change_saved'), 'success', url('setting'));
    }

    // Avatar and cover upload form
    // Форма загрузки аватарки и обложики
    function avatarForm()
    {
        Request::getResources()->addBottomScript('/assets/js/uploads.js');

        return $this->render(
            '/user/setting/avatar',
            'base',
            [
                'meta'  => Meta::get(__('app.avatar')),
                'data'  => [
                    'user'  => UserModel::getUser($this->user['login'], 'slug'),
                ]
            ]
        );
    }

    function avatarEdit()
    {
        UploadImage::set($_FILES, $this->user['id'], 'user');

        is_return(__('msg.change_saved'), 'success', '/setting/avatar');
    }

    // Change password form
    // Форма изменение пароля
    function securityForm()
    {
        return $this->render(
            '/user/setting/security',
            'base',
            [
                'meta'  => Meta::get(__('app.security')),
                'data'  => []
            ]
        );
    }

    function securityEdit()
    {
        $data = Request::getPost();

        RulesUserSetting::rulesSecurity($data, $this->user['email']);

        $newpass = password_hash($data['password2'], PASSWORD_BCRYPT);

        SettingModel::editPassword(['id' => $this->user['id'], 'password' => $newpass]);

        is_return(__('msg.successfully'), 'success');
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
            unlink(HLEB_PUBLIC_DIR . Img::PATH['users_cover'] . $user['cover_art']);
            unlink(HLEB_PUBLIC_DIR . Img::PATH['users_cover_small'] . $user['cover_art']);
        }

        SettingModel::coverRemove(
            [
                'id' => $user['id'],
                'updated_at' => date('Y-m-d H:i:s'),
                'cover_art' => 'cover_art.jpeg'
            ]
        );

        is_return(__('msg.change_saved'), 'success', '/setting/avatar');
    }

    // Member preference setting form
    // Форма настройки предпочтений участника
    function notificationForm()
    {
        return $this->render(
            '/user/setting/notifications',
            'base',
            [
                'meta'  => Meta::get(__('app.notifications')),
                'data'  => [
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

        is_return(__('msg.change_saved'), 'success', '/setting/notifications');
    }
}
