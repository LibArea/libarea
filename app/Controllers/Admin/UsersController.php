<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{UserModel, SettingModel, BadgeModel};
use App\Models\Admin\{BanUserModel, AgentModel};
use Base, Validation, Translate;

class UsersController extends MainController
{
    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $uid    = Base::getUid();

        $limit = 50;
        $pagesCount = UserModel::getUsersAllCount($sheet);
        $user_all   = UserModel::getUsersAll($page, $limit, $uid['user_id'], $sheet);

        $result = array();
        foreach ($user_all as $ind => $row) {
            $row['duplicat_ip_reg'] = AgentModel::duplicatesRegistrationCount($row['user_reg_ip']);
            $row['isBan']           = UserModel::isBan($row['user_id']);
            $row['last_visit_logs'] = AgentModel::lastVisitLogs($row['user_id']);
            $row['created_at']      = lang_date($row['user_created_at']);
            $row['user_updated_at'] = lang_date($row['user_updated_at']);
            $result[$ind]           = $row;
        }

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return view(
            '/admin/user/users',
            [
                'meta'  => meta($m = [], Translate::get('users')),
                'uid'   => $uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'alluser'       => $result,
                    'sheet'         => $sheet == 'all' ? 'users' : 'users-ban',
                ]
            ]
        );
    }

    // Повторы IP
    public function logsIp($option)
    {
        $user_ip    = Request::get('ip');
        if ($option == 'logs') {
            $user_all   = AgentModel::getUserLogsId($user_ip);
        } else {
            $user_all   = AgentModel::getUserRegsId($user_ip);
        }

        $results = array();
        foreach ($user_all as $ind => $row) {
            $row['duplicat_ip_reg'] = AgentModel::duplicatesRegistrationCount($row['user_id']);
            $row['isBan']       = BanUserModel::isBan($row['user_id']);
            $results[$ind]      = $row;
        }

        return view(
            '/admin/user/logip',
            [
                'meta'  => meta($m = [], Translate::get('search')),
                'uid'   => Base::getUid(),
                'data'  => [
                    'results'   => $results,
                    'option'    => $option,
                    'sheet'     => 'users',
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
    public function userEditPage()
    {
        $user_id    = Request::getInt('id');
        if (!$user = UserModel::getUser($user_id, 'id')) redirect(getUrlByName('admin'));

        $user['isBan']              = BanUserModel::isBan($user_id);
        $user['duplicat_ip_reg']    = AgentModel::duplicatesRegistrationCount($user_id);
        $user['last_visit_logs']    = AgentModel::lastVisitLogs($user_id);
        $user['badges']             = BadgeModel::getBadgeUserAll($user_id);

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return view(
            '/admin/user/edit',
            [
                'meta'  => meta($m = [], Translate::get('edit user')),
                'uid'   => Base::getUid(),
                'data'  => [
                    'sheet'     => 'edit-user',
                    'count'     => UserModel::contentCount($user_id),
                    'user'      => $user,
                ]
            ]
        );
    }

    // Редактировать участника
    public function userEdit()
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
        Validation::Limits($user_name, Translate::get('name'), '3', '11', $redirect);

        $data = [
            'user_id'            => $user_id,
            'user_login'         => $login,
            'user_email'         => Request::getPost('email'),
            'user_whisper'       => $user_whisper ?? '',
            'user_name'          => $user_name ?? '',
            'user_activated'     => Request::getPostInt('activated'),
            'user_limiting_mode' => Request::getPostInt('limiting_mode'),
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
