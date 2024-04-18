<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Base\Module;
use Hleb\Static\Request;

use App\Models\User\SettingModel;
use Modules\Admin\Models\{UserModel, BanUserModel, BadgeModel};
use App\Validate\Validator;

use Meta, Html, Msg;

class UsersController extends Module
{
    protected $type = 'users';

    protected $limit = 20;

	public function all()
    {
        return $this->userIndex('all');
    }

	public function ban()
    {
        return $this->userIndex('ban');
    }

    public function userIndex($option)
    {
        $pagesCount = UserModel::getUsersCount($option);
        $user_all   = UserModel::getUsers(Html::pageNumber(), $this->limit, $option);

        $result = [];
        foreach ($user_all as $ind => $row) {
            $row['duplicat_ip_reg'] = UserModel::duplicatesRegistrationCount($row['reg_ip']);
            $row['last_visit_logs'] = UserModel::lastVisitLogs($row['id']);
            $row['created_at']      = langDate($row['created_at']);
            $row['updated_at']      = langDate($row['updated_at']);
            $result[$ind]           = $row;
        }

        return view(
            '/user/users',
            [
                'meta'  => Meta::get(__('admin.users')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => Html::pageNumber(),
                    'alluser'       => $result,
                    'type'          => $this->type,
                    'sheet'         => $option,
                    'users_count'   => $pagesCount,
                ]
            ]
        );
    }

    // Повторы (Repeats)
    public function logip()
    {
        return $this->callSearchIp('logip');
    }

    public function regip()
    {
        return $this->callSearchIp('regip');
    }

    public function deviceid()
    {
        return $this->callSearchIp('deviceid');
    }

    private function callSearchIp($option)
    {
        $item = Request::param('item')->asString();

        if ($option == 'logip') {
            $user_all    = UserModel::getUserLogsId($item);
        } else if ($option == 'deviceid') {
            $user_all    = UserModel::getUserSearchDeviceID($item);
        } else {
            $user_all   = UserModel::getUserSearchRegIp($item);
        }

        $results = [];
        foreach ($user_all as $ind => $row) {
            $row['duplicat_ip_reg'] = UserModel::duplicatesRegistrationCount($row['id']);
            $results[$ind]      = $row;
        }

        return view(
            '/user/search',
            [
                'meta'  => Meta::get(__('admin.search')),
                'data'  => [
                    'results'   => $results,
                    'option'    => $option,
                    'type'      => $this->type,
                ]
            ]
        );
    }

    /**
     * Ban a participant
     * Бан участнику
     *
     * @return void
     */
    public function banUser()
    {
        $user_id = Request::post('id')->asInt();

        BanUserModel::setBanUser($user_id);

        return true;
    }

    /**
     * The edit member page
     * Страница редактирование участника
     *
     * @return void
     */
    public function editForm()
    {
        $user_id    = Request::param('id')->asInt();
        if (!$user = UserModel::getUser($user_id, 'id')) redirect(url('admin'));

        $user['isBan']              = BanUserModel::isBan($user_id);
        $user['duplicat_ip_reg']    = UserModel::duplicatesRegistrationCount($user_id);
        $user['last_visit_logs']    = UserModel::lastVisitLogs($user_id);
        $user['badges']             = BadgeModel::getBadgeUserAll($user_id);

        return view(
            'user/edit',
            [
                'meta'  => Meta::get(__('admin.edit')),
                'data'  => [
                    'type'      => $this->type,
                    'user'      => $user,
                ]
            ]
        );
    }

    public function edit()
    {
        $data = Request::allPost();

        if (!$user = UserModel::getUser($data['user_id'], 'id')) {
            return false;
        }

        $redirect = url('admin.user.edit.form', ['id' => $user['id']]);

        Validator::length($data['login'], 3, 15, 'login', $redirect);

        if ($data['email']) {
            Validator::email(Request::post('email')->value(), $redirect);
        }

        SettingModel::edit(
            [
                'id'            => $data['user_id'],
                'login'         => $data['login'],
                'email'         => $data['email'],
                'whisper'       => $data['whisper'],
                'name'          => $data['name'],
                'activated'     => Request::post('activated')->value() == 'on' ? 1 : 0,
                'limiting_mode' => Request::post('limiting_mode')->value() == 'on' ? 1 : 0,
                'template'      => $user['template'] ?? 'default',
                'lang'          => $user['lang'] ?? 'ru',
                'scroll'        => Request::post('scroll')->value() == 'on' ? 1 : 0,
                'nsfw'          => Request::post('nsfw')->value() == 'on' ? 1 : 0,
                'post_design'   => Request::post('post_design')->value() == 'on' ? 1 : 0,
                'trust_level'   => $data['trust_level'] ?? 1,
                'updated_at'    => date('Y-m-d H:i:s'),
                'color'         => Request::post('color')->asString('#339900'),
                'about'         => Request::post('about')->asString(''),
                'website'       => Request::post('website')->asString(''),
                'location'      => Request::post('location')->asString(''),
                'public_email'  => Request::post('public_email')->asString(''),
                'github'        => Request::post('github')->asString(''),
                'skype'         => Request::post('skype')->asString(''),
                'telegram'      => Request::post('telegram')->asString(''),
                'vk'            => Request::post('vk')->asString(''),
                'vk'            => Request::post('vk')->asString(''),
            ]
        );

        Msg::redirect(__('msg.change_saved'), 'success', url('admin.user.edit.form', ['id' => $data['user_id']]));
    }

    public function history()
    {
        $user_id    = Request::param('id')->asInt();
        if (!$user = UserModel::getUser($user_id, 'id')) redirect(url('admin'));

        return view(
            '/user/history',
            [
                'meta'  => Meta::get(__('admin.history')),
                'data'  => [
                    'type'      => $this->type,
                    'results'    => userModel::userHistory($user_id),
                ]
            ]
        );
    }
}
