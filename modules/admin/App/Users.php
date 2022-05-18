<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{SettingModel, BadgeModel};
use Modules\Admin\App\Models\{BanUserModel, UserModel};
use Validation, Meta, Html;

class Users extends Controller
{
    protected $type = 'users';

    protected $limit = 50;

    public function index($sheet)
    {
        $pagesCount = UserModel::getUsersCount($sheet);
        $user_all   = UserModel::getUsers($this->pageNumber, $this->limit, $sheet);

        $result = [];
        foreach ($user_all as $ind => $row) {
            $row['duplicat_ip_reg'] = UserModel::duplicatesRegistrationCount($row['reg_ip']);
            $row['last_visit_logs'] = UserModel::lastVisitLogs($row['id']);
            $row['created_at']      = Html::langDate($row['created_at']);
            $row['updated_at']      = Html::langDate($row['updated_at']);
            $result[$ind]           = $row;
        }

        return view(
            '/view/default/user/users',
            [
                'meta'  => Meta::get(__('admin.users')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'alluser'       => $result,
                    'type'          => $this->type,
                    'users_count'   => $pagesCount,
                ]
            ]
        );
    }

    // Повторы IP
    public function logsIp($option)
    {
        $user_ip    = Request::get('ip');
        if ($option == 'users.logip') {
            $user_all   = UserModel::getUserLogsId($user_ip);
        } else {
            $user_all   = UserModel::getUserRegsId($user_ip);
        }

        $results = [];
        foreach ($user_all as $ind => $row) {
            $row['duplicat_ip_reg'] = UserModel::duplicatesRegistrationCount($row['id']);
            $results[$ind]      = $row;
        }

        return view(
            '/view/default/user/logip',
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

    // Бан участнику
    public function banUser()
    {
        $user_id = Request::getPostInt('id');

        BanUserModel::setBanUser($user_id);

        return true;
    }

    // Страница редактирование участника
    public function edit()
    {
        $user_id    = Request::getInt('id');
        if (!$user = UserModel::getUser($user_id, 'id')) redirect(url('admin'));

        $user['isBan']              = BanUserModel::isBan($user_id);
        $user['duplicat_ip_reg']    = UserModel::duplicatesRegistrationCount($user_id);
        $user['last_visit_logs']    = UserModel::lastVisitLogs($user_id);
        $user['badges']             = BadgeModel::getBadgeUserAll($user_id);

        return view(
            '/view/default/user/edit',
            [
                'meta'  => Meta::get(__('admin.edit')),
                'data'  => [
                    'type'      => $this->type,
                    'count'     => UserModel::contentCount($user_id),
                    'user'      => $user,
                ]
            ]
        );
    }

    // Редактировать участника
    public function change()
    {
        $login          = Request::getPost('login');
        $user_id        = Request::getInt('id');
        $email          = Request::getPost('email');
        $user_whisper   = Request::getPost('whisper');
        $user_name      = Request::getPost('name');
        $trust_level    = Request::getPostInt('trust_level');

        if (!$user = UserModel::getUser($user_id, 'id')) {
            return true;
        }
        
        if (!Validation::length($login, 3, 11)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.login') . '»'])]);
        }

        if ($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return json_encode(['error' => 'error', 'text' => __('msg.email_correctness')]);
            }
        }

        SettingModel::edit(
            [
                'id'            => $user_id,
                'login'         => $login,
                'email'         => $email,
                'whisper'       => $user_whisper ?? '',
                'name'          => $user_name ?? '',
                'activated'     => Request::getPostInt('activated'),
                'limiting_mode' => Request::getPostInt('limiting_mode'),
                'template'      => $user['template'] ?? 'default',
                'lang'          => $user['lang'] ?? 'ru',
                'scroll'        => $user['scroll'] ?? 0,
                'trust_level'   => Request::getPostInt('trust_level'),
                'updated_at'    => date('Y-m-d H:i:s'),
                'color'         => Request::getPostString('color', '#339900'),
                'about'         => Request::getPost('about', null),
                'website'       => Request::getPost('website', null),
                'location'      => Request::getPost('location', null),
                'public_email'  => Request::getPost('public_email', null),
                'skype'         => Request::getPost('skype', null),
                'telegram'      => Request::getPost('telegram', null),
                'vk'            => Request::getPost('vk', null),
            ]
        );

        return true;
    }
}
