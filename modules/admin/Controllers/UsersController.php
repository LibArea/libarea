<?php

namespace Modules\Admin\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\{UserModel, BadgeModel};
use App\Models\SpaceModel;
use Lori\Base;

class UsersController extends MainController
{
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 50;
        $pagesCount = UserModel::getUsersListForAdminCount($sheet);
        $user_all   = UserModel::getUsersListForAdmin($page, $limit, $sheet);

        $result = array();
        foreach ($user_all as $ind => $row) {
            $row['replayIp']    = UserModel::replayIp($row['user_reg_ip']);
            $row['isBan']       = UserModel::isBan($row['user_id']);
            $row['logs']        = UserModel::userLogId($row['user_id']);
            $row['created_at']  = lang_date($row['user_created_at']);
            $result[$ind]       = $row;
        }

        $data = [
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'users'         => $result,
            'meta_title'    => lang('Users'),
            'sheet'         => $sheet == 'all' ? 'users' : 'users-ban',
        ];

        return view('/templates/user/users', ['data' => $data, 'uid' => $uid, 'alluser' => $result]);
    }

    // Повторы IP
    public function logsIp()
    {
        $uid        = Base::getUid();
        $user_ip    = Request::get('ip');
        $user_all   = UserModel::getUserLogsId($user_ip);

        $results = array();
        foreach ($user_all as $ind => $row) {
            $row['replayIp']    = UserModel::replayIp($row['user_reg_ip']);
            $row['isBan']       = UserModel::isBan($row['user_id']);
            $results[$ind]      = $row;
        }

        $data = [
            'h1'            => lang('Search'),
            'meta_title'    => lang('Search'),
            'sheet'         => 'admin',
        ];

        return view('/templates/user/logip', ['data' => $data, 'uid' => $uid, 'alluser' => $results]);
    }

    // Бан участнику
    public function banUser()
    {
        $user_id    = Request::getPostInt('id');

        UserModel::setBanUser($user_id);

        return true;
    }

    // Страница редактиорование участника
    public function userEditPage()
    {
        $uid        = Base::getUid();
        $user_id    = Request::getInt('id');

        if (!$user = UserModel::getUser($user_id, 'id')) {
            redirect('/admin');
        }

        $user['isBan']      = UserModel::isBan($user_id);
        $user['replayIp']   = UserModel::replayIp($user_id);
        $user['logs']       = UserModel::userLogId($user_id);
        $user['badges']     = BadgeModel::getBadgeUserAll($user_id);

        $counts =   UserModel::contentCount($user_id);

        $data = [
            'meta_title'        => lang('Edit user'),
            'sheet'             => 'edit-user',
            'posts_count'       => $counts['count_posts'],
            'answers_count'     => $counts['count_answers'],
            'comments_count'    => $counts['count_comments'],
            'spaces_user'       => SpaceModel::getUserCreatedSpaces($user_id),
        ];

        return view('/templates/user/edit', ['data' => $data, 'uid' => $uid, 'user' => $user]);
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

        Base::Limits($login, lang('Login'), '4', '11', $redirect);

        $data = [
            'user_id'            => $user_id,
            'user_email'         => $email,
            'user_login'         => $login,
            'user_name'          => empty($name) ? '' : $name,
            'user_activated'     => $activated,
            'user_limiting_mode' => $limiting_mode,
            'user_trust_level'   => $trust_level,
            'user_about'         => empty($about) ? '' : $about,
            'user_website'       => empty($website) ? '' : $website,
            'user_location'      => empty($location) ? '' : $location,
            'user_public_email'  => empty($public_email) ? '' : $public_email,
            'user_skype'         => empty($skype) ? '' : $skype,
            'user_twitter'       => empty($twitter) ? '' : $twitter,
            'user_telegram'      => empty($telegram) ? '' : $telegram,
            'user_vk'            => empty($vk) ? '' : $vk,
        ];

        UserModel::setUserEdit($data);

        redirect($redirect);
    }
}
