<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Models\User\{SettingModel, BadgeModel};
use Modules\Admin\App\Models\{BanUserModel, UserModel};
use Validation, UserData, Meta, Html, Tpl;

class Users
{
    protected $limit = 50;

    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet, $type)
    {
        $pageNumber = Tpl::pageNumber();
        $pagesCount = UserModel::getUsersCount($sheet);
        $user_all   = UserModel::getUsers($pageNumber, $this->limit, $sheet);

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
                'meta'  => Meta::get(__('users')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'alluser'       => $result,
                    'sheet'         => $sheet,
                    'type'          => $type,
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
                'meta'  => Meta::get(__('search')),
                'data'  => [
                    'results'   => $results,
                    'option'    => $option,
                    'type'      => 'users',
                    'sheet'     => ''
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
    public function userEditPage($sheet, $type)
    {
        $user_id    = Request::getInt('id');
        if (!$user = UserModel::getUser($user_id, 'id')) redirect(getUrlByName('admin'));

        $user['isBan']              = BanUserModel::isBan($user_id);
        $user['duplicat_ip_reg']    = UserModel::duplicatesRegistrationCount($user_id);
        $user['last_visit_logs']    = UserModel::lastVisitLogs($user_id);
        $user['badges']             = BadgeModel::getBadgeUserAll($user_id);

        return view(
            '/view/default/user/edit',
            [
                'meta'  => Meta::get(__('edit')),
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                    'count'     => UserModel::contentCount($user_id),
                    'user'      => $user,
                ]
            ]
        );
    }

    // Редактировать участника
    public function edit()
    {
        $login          = Request::getPost('login');
        $user_id        = Request::getInt('id');
        $user_whisper   = Request::getPost('whisper');
        $user_name      = Request::getPost('name');
        $trust_level    = Request::getPostInt('trust_level');

        if (!$user = UserModel::getUser($user_id, 'id')) {
            redirect(getUrlByName('admin.users'));
        }

        $redirect = getUrlByName('admin.user.edit', ['id' => $user_id]);
        Validation::Length($login, __('login'), '3', '11', $redirect);

        SettingModel::edit(
            [
                'id'            => $user_id,
                'login'         => $login,
                'email'         => Request::getPost('email'),
                'whisper'       => $user_whisper ?? null,
                'name'          => $user_name ?? null,
                'activated'     => Request::getPostInt('activated'),
                'limiting_mode' => Request::getPostInt('limiting_mode'),
                'template'      => $user['template'] ?? 'default',
                'lang'          => $user['lang'] ?? 'ru',
                'scroll'        => $user['lang'] ?? 0,
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

        redirect($redirect);
    }
}
