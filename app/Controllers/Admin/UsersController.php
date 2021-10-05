<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\User\SettingModel;
use App\Models\{Admin\BanUserModel, Admin\BadgeModel, Admin\AgentModel, SpaceModel};
use Agouti\{Base, Validation};

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
        $meta = [
            'meta_title'    => lang('users'),
            'sheet'         => 'users',
        ];

        $data = [
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'alluser'       => $result,
            'sheet'         => $sheet == 'all' ? 'users' : 'users-ban',
        ];

        return view('/admin/user/users', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
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
            $row['duplicat_ip_reg']    = AgentModel::duplicatesRegistrationCount($row['user_id']);
            $row['isBan']       = BanUserModel::isBan($row['user_id']);
            $results[$ind]      = $row;
        }

        $meta = [
            'meta_title'    => lang('search'),
            'sheet'         => 'users',
        ];

        $data = [
            'results'       => $results,
            'option'        => $option,
            'sheet'         => 'users',
        ];

        return view('/admin/user/logip', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    // Бан участнику
    public function banUser()
    {
        $user_id    = Request::getPostInt('id');

        BanUserModel::setBanUser($user_id);

        return true;
    }

    // Страница редактиорование участника
    public function userEditPage()
    {
        $user_id    = Request::getInt('id');

        if (!$user = UserModel::getUser($user_id, 'id')) {
            redirect('/admin');
        }

        $user['isBan']              = BanUserModel::isBan($user_id);
        $user['duplicat_ip_reg']    = AgentModel::duplicatesRegistrationCount($user_id);
        $user['last_visit_logs']    = AgentModel::lastVisitLogs($user_id);
        $user['badges']             = BadgeModel::getBadgeUserAll($user_id);

        $counts = UserModel::contentCount($user_id);

        $meta = [
            'meta_title'        => lang('edit user'),
            'sheet'             => 'users',
        ];

        $data = [
            'sheet'             => 'edit-user',
            'posts_count'       => $counts['count_posts'],
            'answers_count'     => $counts['count_answers'],
            'comments_count'    => $counts['count_comments'],
            'spaces_user'       => SpaceModel::getUserCreatedSpaces($user_id),
            'user'              => $user,
        ];

        return view('/admin/user/edit', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    // Редактировать участника
    public function userEdit()
    {
        $user_id    = Request::getInt('id');

        $redirect = '/admin/users';
        if (!UserModel::getUser($user_id, 'id')) {
            redirect($redirect);
        }

        $email          = Request::getPost('email');
        $login          = Request::getPost('login');
        $name           = Request::getPost('name');
        $about          = Request::getPost('about');
        $whisper        = Request::getPost('whisper');
        $activated      = Request::getPostInt('activated');
        $limiting_mode  = Request::getPostInt('limiting_mode');
        $trust_level    = Request::getPostInt('trust_level');
        $website        = Request::getPost('website');
        $location       = Request::getPost('location');
        $public_email   = Request::getPost('public_email');
        $skype          = Request::getPost('skype');
        $twitter        = Request::getPost('twitter');
        $telegram       = Request::getPost('telegram');
        $vk             = Request::getPost('vk');

        Validation::Limits($login, lang('login'), '4', '11', $redirect);

        $data = [
            'user_id'            => $user_id,
            'user_email'         => $email,
            'user_login'         => $login,
            'user_whisper'       => $whisper ?? '',
            'user_name'          => $name ?? '',
            'user_activated'     => $activated,
            'user_limiting_mode' => $limiting_mode,
            'user_trust_level'   => $trust_level,
            'user_about'         => $about ?? '',
            'user_website'       => $website ?? '',
            'user_location'      => $location ?? '',
            'user_public_email'  => $public_email ?? '',
            'user_skype'         => $skype ?? '',
            'user_twitter'       => $twitter ?? '',
            'user_telegram'      => $telegram ?? '',
            'user_vk'            => $vk ?? '',
        ];

        SettingModel::editProfile($data);

        redirect($redirect);
    }
}
