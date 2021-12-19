<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{SettingModel, BadgeModel};
use App\Models\Admin\{BanUserModel, UserModel};
use Base, Validation, Translate;

class UsersController extends MainController
{
    protected $limit = 50;

    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = UserModel::getUsersCount($sheet);
        $user_all   = UserModel::getUsers($page, $this->limit, $sheet);

        $result = [];
        foreach ($user_all as $ind => $row) {
            $row['duplicat_ip_reg'] = UserModel::duplicatesRegistrationCount($row['user_reg_ip']);
            $row['last_visit_logs'] = UserModel::lastVisitLogs($row['user_id']);
            $row['created_at']      = lang_date($row['user_created_at']);
            $row['user_updated_at'] = lang_date($row['user_updated_at']);
            $result[$ind]           = $row;
        }

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return render(
            '/admin/user/users',
            [
                'meta'  => meta($m = [], Translate::get('users')),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'alluser'       => $result,
                    'sheet'         => $sheet,
                    'type'          => $type,
                ]
            ]
        );
    }

    // Повторы IP
    public function logsIp($option)
    {
        $user_ip    = Request::get('ip');
        if ($option == 'logs') {
            $user_all   = UserModel::getUserLogsId($user_ip);
        } else {
            $user_all   = UserModel::getUserRegsId($user_ip);
        }

        $results = [];
        foreach ($user_all as $ind => $row) {
            $row['duplicat_ip_reg'] = UserModel::duplicatesRegistrationCount($row['user_id']);
            $row['isBan']       = BanUserModel::isBan($row['user_id']);
            $results[$ind]      = $row;
        }

        return render(
            '/admin/user/logip',
            [
                'meta'  => meta($m = [], Translate::get('search')),
                'uid'   => $this->uid,
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

    // Страница редактиорование участника
    public function userEditPage($sheet, $type)
    {
        $user_id    = Request::getInt('id');
        if (!$user = UserModel::getUser($user_id, 'id')) redirect(getUrlByName('admin'));

        $user['isBan']              = BanUserModel::isBan($user_id);
        $user['duplicat_ip_reg']    = UserModel::duplicatesRegistrationCount($user_id);
        $user['last_visit_logs']    = UserModel::lastVisitLogs($user_id);
        $user['badges']             = BadgeModel::getBadgeUserAll($user_id);

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return render(
            '/admin/user/edit',
            [
                'meta'  => meta($m = [], Translate::get('edit user')),
                'uid'   => $this->uid,
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

        if (!$user = UserModel::getUser($user_id, 'id')) {
            redirect(getUrlByName('admin.users'));
        }

        $redirect = getUrlByName('admin.user.edit', ['id' => $user_id]);
        Validation::Limits($login, Translate::get('login'), '3', '11', $redirect);

        if ($this->uid['user_trust_level'] != 5) {
            Validation::Limits($user_name, Translate::get('name'), '3', '11', $redirect);
        }

        $data = [
            'user_id'            => $user_id,
            'user_login'         => $login,
            'user_email'         => Request::getPost('email'),
            'user_whisper'       => $user_whisper ?? '',
            'user_name'          => $user_name ?? '',
            'user_activated'     => Request::getPostInt('activated'),
            'user_limiting_mode' => Request::getPostInt('limiting_mode'),
            'user_template'      => $user['user_template'],
            'user_lang'          => $user['user_lang'],
            'user_trust_level'   => Request::getPostInt('trust_level'),
            'user_color'         => Request::getPostString('color', '#339900'),
            'user_about'         => Request::getPost('about', ''),
            'user_website'       => Request::getPost('website', ''),
            'user_location'      => Request::getPost('location', ''),
            'user_public_email'  => Request::getPost('public_email', ''),
            'user_skype'         => Request::getPost('skype', ''),
            'user_twitter'       => Request::getPost('twitter', ''),
            'user_telegram'      => Request::getPost('telegram', ''),
            'user_vk'            => Request::getPost('vk', ''),
        ];

        SettingModel::editProfile($data);

        redirect($redirect);
    }
}
