<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{SettingModel, BadgeModel};
use Modules\Admin\App\Models\{BanUserModel, UserModel};
use App\Validate\Validator;
use Meta, Html;

class Users extends Controller
{
    protected $type = 'users';

    protected $limit = 20;

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
                    'sheet'         => $sheet,
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
                    'user'      => $user,
                ]
            ]
        );
    }

    // Редактировать участника
    public function change()
    {
        $data = Request::getPost();

        if (!$user = UserModel::getUser($data['user_id'], 'id')) {
            return false;
        }

        $redirect = url('admin.user.edit', ['id' => $user['id']]);

        Validator::length($data['login'], 3, 15, 'login', $redirect);

        if ($data['email']) {
            Validator::email(Request::getPost('email'), $redirect);
        }

        SettingModel::edit(
            [
                'id'            => $data['user_id'],
                'login'         => $data['login'],
                'email'         => $data['email'],
                'whisper'       => $data['whisper'],
                'name'          => $data['name'],
                'activated'     => Request::getPost('activated') == 'on' ? 1 : 0,
                'limiting_mode' => Request::getPost('limiting_mode') == 'on' ? 1 : 0,
                'template'      => $user['template'] ?? 'default',
                'lang'          => $user['lang'] ?? 'ru',
                'scroll'        => Request::getPost('scroll') == 'on' ? 1 : 0,
                'nsfw'          => Request::getPost('nsfw') == 'on' ? 1 : 0,
                'trust_level'   => $data['trust_level'] ?? 1,
                'updated_at'    => date('Y-m-d H:i:s'),
                'color'         => Request::getPostString('color', '#339900'),
                'about'         => Request::getPost('about', null),
                'website'       => Request::getPost('website', null),
                'location'      => Request::getPost('location', null),
                'public_email'  => Request::getPost('public_email', null),
                'github'        => Request::getPost('github', null),
                'skype'         => Request::getPost('skype', null),
                'telegram'      => Request::getPost('telegram', null),
                'vk'            => Request::getPost('vk', null),
            ]
        );

        is_return(__('msg.change_saved'), 'success', url('admin.user.edit', ['id' => $data['user_id']]));
    }
}
