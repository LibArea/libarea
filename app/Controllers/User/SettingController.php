<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Hleb\Static\Request;
use Hleb\Base\Controller;

use App\Models\User\{SettingModel, UserModel, PreferencesModel};
use App\Models\{IgnoredModel, ActionModel};
use App\Models\Auth\AuthModel;
use UploadImage, Meta, Img, Html, SendEmail, Msg;

use App\Validate\RulesUserSetting;

class SettingController extends Controller
{
    protected $limit = 25;

    public function profile(): void
    {
        $this->edit();
    }

    public function avatar(): void
    {
        $this->avatarEdit();
    }

    public function security(): void
    {
        $this->securityEdit();
    }

    public function preferences(): void
    {
        $this->preferencesEdit();
    }

    public function notification(): void
    {
        $this->notificationEdit();
    }

    public function coverUserRemove(): void
    {
        $this->coverRemove();
    }

    /**
     * Profile setup form
     * Форма настройки профиля
     *
     * @return void
     */
    public function index()
    {
        $new = SettingModel::getNewEmail();
        $email = $new['email'] ?? null;

        if ($code = Request::get('newemail')->value()) {
            if (SettingModel::available($code)) {
                SettingModel::editEmail($email);

                Msg::redirect(__('msg.change_saved'), 'success', url('setting'));
            }
        }

        render(
            '/user/setting/setting',
            [
                'meta'  => Meta::get(__('app.setting')),
                'data'  => [
                    'user'  => UserModel::get($this->container->user()->login(), 'slug'),
                    'new_email' => $email,
                ]
            ]
        );
    }

    function edit()
    {
        $user_id = $this->container->user()->id();
        RulesUserSetting::rulesSetting($data = Request::allPost());

        $user = UserModel::get($user_id, 'id');

        SettingModel::edit(
            [
                'id'                   => $user_id,
                'email'                => $user['email'],
                'login'                => $user['login'],
                'name'                 => $data['name'],
                'activated'            => $user['activated'],
                'limiting_mode'        => $user['limiting_mode'],
                'scroll'               => Request::post('scroll')->value() == 'on' ? 1 : 0,
                'nsfw'                 => Request::post('nsfw')->value() == 'on' ? 1 : 0,
                'post_design'           => Request::post('post_design')->value() == 'on' ? 1 : 0,
                'trust_level'          => $user['trust_level'],
                'updated_at'           => date('Y-m-d H:i:s'),
                'color'                => Request::post('color')->asString('#339900'),
                'about'                => $_POST['about'], // for Markdown
                'template'             => $data['template'] ?? 'default',
                'lang'                 => $data['lang'] ?? 'ru',
                'whisper'              => $user['whisper'] ?? '',
                'website'              => Request::post('website')->asString(''),
                'location'             => Request::post('location')->asString(''),
                'public_email'         => $data['public_email'] ?? null,
                'github'               => Request::post('github')->asString(''),
                'skype'                => Request::post('skype')->asString(''),
                'telegram'             => Request::post('telegram')->asString(''),
                'vk'                   => Request::post('vk')->asString(''),
            ]
        );

        Msg::redirect(__('msg.change_saved'), 'success', url('setting'));
    }

    /**
     * Avatar and cover upload form
     * Форма загрузки аватарки и обложики
     *
     * @return void
     */
    function avatarForm()
    {
        render(
            '/user/setting/avatar',
            [
                'meta'  => Meta::get(__('app.avatar')),
                'data'  => [
                    'user'  => UserModel::get($this->container->user()->login(), 'slug'),
                ]
            ]
        );
    }

    function avatarEdit()
    {
        UploadImage::set($_FILES, $this->container->user()->id(), 'user');
    }

    /**
     * Change password form
     * Форма изменение пароля
     *
     * @return void
     */
    function securityForm()
    {
        render(
            '/user/setting/security',
            [
                'meta'  => Meta::get(__('app.security')),
                'data'  => []
            ]
        );
    }

    function securityEdit()
    {
        $data = Request::allPost();

        RulesUserSetting::rulesSecurity($data, $this->container->user()->email());

        $newpass = password_hash($data['password2'], PASSWORD_BCRYPT);

        SettingModel::editPassword(['id' => $this->container->user()->id(), 'password' => $newpass]);

        Msg::redirect(__('msg.successfully'), 'success');
    }

    /**
     * Cover Removal
     * Удаление обложки
     *
     * @return void
     */
    function coverRemove()
    {
        $user = UserModel::get($this->container->user()->login(), 'slug');

        // Only the author and the admin can delete
        // Удалять может только автор и админ
        if ($user['id'] != $this->container->user()->id() && $this->container->user()->admin()) {
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

        Msg::redirect(__('msg.change_saved'), 'success', '/setting/avatar');
    }

    /**
     * Member preference setting form
     * Форма настройки предпочтений участника
     *
     * @return void
     */
    function notificationForm()
    {
        render(
            '/user/setting/notifications',
            [
                'meta'  => Meta::get(__('app.notifications')),
                'data'  => [
                    'notif' => SettingModel::getNotifications($this->container->user()->id()),
                ]
            ]
        );
    }

    function ignoredForm()
    {
        render(
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
        render(
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
                'setting_user_id'           => $this->container->user()->id(),
                'setting_email_pm'          => Request::post('setting_email_pm')->asInt(),
                'setting_email_appealed'    => Request::post('setting_email_appealed')->asInt(),
                'setting_email_post'        => 0,
                'setting_email_answer'      => 0,
            ]
        );

        Msg::redirect(__('msg.change_saved'), 'success', '/setting/notifications');
    }

    function newEmail()
    {
        $email = Request::post('email')->value();

        if (RulesUserSetting::rulesNewEmail($email) === false) {
            return json_encode('error');
        }

        if (is_array(AuthModel::checkRepetitions($email, 'email'))) {
            return json_encode('repeat');
        }

        $code = Html::randomString('crypto', 20);

        SettingModel::setNewEmail($email, $code);

        SendEmail::mailText($this->container->user()->id(), 'new.email', ['link' => '/setting?newemail=' . $code, 'new_email' => $email]);

        return json_encode('success');
    }

    public function deleteActivation()
    {
        SettingModel::deletionUser($this->container->user()->id());

        ActionModel::addLogs(
            [
                'id_content'    => $this->container->user()->id(),
                'action_type'   => 'profile',
                'action_name'   => 'deleted',
                'url_content'   => url('profile', ['login' => $this->container->user()->login()]),
            ]
        );

        $this->container->auth()->logout();
    }

    function preferencesForm()
    {
        render(
            '/user/setting/preferences',
            [
                'meta'  => Meta::get(__('app.preferences')),
                'data'  => [
                    'signed'        => PreferencesModel::get(Html::pageNumber()),
                    'pagesCount'    => PreferencesModel::getCount(),
                    'blocks'        => PreferencesModel::getBlocks(),
                    'pNum'          => Html::pageNumber(),
                    'facet_arr'     => []
                ]
            ]
        );
    }

    function preferencesEdit()
    {
        if (!is_array($data = Request::post('id')->value())) {

            PreferencesModel::removal();
            Msg::redirect(__('msg.change_saved'), 'success', '/setting/preferences');
        }

        PreferencesModel::edit($data);

        Msg::redirect(__('msg.change_saved'), 'success', '/setting/preferences');
    }
}
