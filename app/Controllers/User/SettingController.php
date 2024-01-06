<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{SettingModel, UserModel, PreferencesModel};
use App\Models\{IgnoredModel, AuthModel, ActionModel};
use UploadImage, Session, Meta, UserData, Img, Html, SendEmail;

use App\Validate\RulesUserSetting;

class SettingController extends Controller
{
    protected $limit = 25;

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
            case 'ignored':
                return $this->ignored();
                break;
            case 'preferences':
                return $this->preferences();
                break;
            case 'deletion':
                return $this->deletion();
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
            case 'preferences':
                return $this->preferencesEdit();
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
        Request::getResources()->addBottomScript('/assets/js/dialog/dialog.js');

        $new = SettingModel::getNewEmail();
        $email = $new['email'] ?? null;

        if ($code = Request::getGet('newemail')) {
            if (SettingModel::available($code)) {
                SettingModel::editEmail($email);

                is_return(__('msg.change_saved'), 'success', url('setting'));
            }
        }

        return $this->render(
            '/user/setting/setting',
            [
                'meta'  => Meta::get(__('app.setting')),
                'data'  => [
                    'user'  => UserModel::getUser($this->user['login'], 'slug'),
                    'new_email' => $email,
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
                'nsfw'                 => Request::getPost('nsfw') == 'on' ? 1 : 0,
                'post_design'           => Request::getPost('post_design') == 'on' ? 1 : 0,
                'trust_level'          => $user['trust_level'],
                'updated_at'           => date('Y-m-d H:i:s'),
                'color'                => Request::getPostString('color', '#339900'),
                'about'                => $_POST['about'], // for Markdown
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
            [
                'meta'  => Meta::get(__('app.notifications')),
                'data'  => [
                    'notif' => SettingModel::getNotifications($this->user['id']),
                ]
            ]
        );
    }

    function ignored()
    {
        return $this->render(
            '/user/setting/ignored',
            [
                'meta'  => Meta::get(__('app.ignored')),
                'data'  => [
                    'ignored' => IgnoredModel::getIgnoredUsers($this->limit),
                ]
            ]
        );
    }

    function deletion()
    {
        return $this->render(
            '/user/setting/deletion',
            [
                'meta'  => Meta::get(__('app.delete_profile')),
                'data'  => []
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
            ]
        );

        is_return(__('msg.change_saved'), 'success', '/setting/notifications');
    }

    function newEmail()
    {
        $email = Request::getPost('email');

        if (RulesUserSetting::rulesNewEmail($email) === false) {
            return json_encode('error');
        }

        if (is_array(AuthModel::checkRepetitions($email, 'email'))) {
            return json_encode('repeat');
        }

        $code = Html::randomString('crypto', 20);

        SettingModel::setNewEmail($email, $code);

        SendEmail::mailText($this->user['id'], 'new.email', ['link' => '/setting?newemail=' . $code, 'new_email' => $email]);

        return json_encode('success');
    }

    public function deleteActivation()
    {
        SettingModel::deletionUser($this->user['id']);

        ActionModel::addLogs(
            [
                'id_content'    => $this->user['id'],
                'action_type'   => 'profile',
                'action_name'   => 'deleted',
                'url_content'   => url('profile', ['login' => $this->user['login']]),
            ]
        );

        Session::logout();
    }

    function preferences()
    {
        return $this->render(
            '/user/setting/preferences',
            [
                'meta'  => Meta::get(__('app.preferences')),
                'data'  => [
                    'facets'         => PreferencesModel::get($this->pageNumber),
                    'pagesCount'    => PreferencesModel::getCount(),
                    'blocks'        => PreferencesModel::getBlocks(),
                    'pNum'             => $this->pageNumber,
                    'facet_arr'     => []
                ]
            ]
        );
    }

    function preferencesEdit()
    {
        if (!is_array($data = Request::getPost('id'))) {

            PreferencesModel::removal();
            is_return(__('msg.change_saved'), 'success', '/setting/preferences');
        }

        PreferencesModel::edit($data);

        is_return(__('msg.change_saved'), 'success', '/setting/preferences');
    }
}
