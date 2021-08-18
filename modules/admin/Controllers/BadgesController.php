<?php

namespace Modules\Admin\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\BadgeModel;
use App\Models\UserModel;
use Lori\Base;

class BadgesController extends MainController
{
    // Все награды
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $badges = BadgeModel::getBadgesAll();

        $data = [
            'meta_title'    => lang('Badges'),
            'sheet'         => $sheet == 'all' ? 'badges' : $sheet,
        ];

        return view('/templates/badge/badges', ['data' => $data, 'uid' => $uid, 'badges' => $badges]);
    }

    // Форма добавления награды
    public function addPage()
    {
        $uid  = Base::getUid();
        $data = [
            'meta_title'    => lang('Add badge'),
            'sheet'         => 'badges',
        ];

        return view('/templates/badge/add', ['data' => $data, 'uid' => $uid]);
    }


    // Форма изменения награды
    public function editPage()
    {
        $uid        = Base::getUid();
        $badge_id   = Request::getInt('id');
        $badge      = BadgeModel::getBadgeId($badge_id);

        if (!$badge['badge_id']) {
            redirect('/admin/badges');
        }

        $data = [
            'meta_title'    => lang('Edit badge'),
            'sheet'         => 'badges',
        ];

        return view('/templates/badge/edit', ['data' => $data, 'uid' => $uid, 'badge' => $badge]);
    }

    // Добавляем награду
    public function add()
    {
        $badge_title         = Request::getPost('badge_title');
        $badge_description   = Request::getPost('badge_description');
        $badge_icon          = $_POST['badge_icon']; // не фильтруем

        $redirect = '/admin/badges';
        Base::Limits($badge_title, lang('Title'), '4', '25', $redirect);
        Base::Limits($badge_description, lang('Description'), '12', '250', $redirect);
        Base::Limits($badge_icon, lang('Icon'), '12', '250', $redirect);

        $data = [
            'badge_title'       => $badge_title,
            'badge_description' => $badge_description,
            'badge_icon'        => $badge_icon,
            'badge_tl'          => 0,
            'badge_score'       => 0,
        ];

        BadgeModel::add($data);
        redirect($redirect);
    }

    // Форма награждения участинка
    public function addUserPage()
    {
        $uid        = Base::getUid();
        $user_id    = Request::getInt('id');

        if ($user_id > 0) {
            $user   = UserModel::getUser($user_id, 'id');
        } else {
            $user   = null;
        }

        $badges = BadgeModel::getBadgesAll();

        $data = [
            'meta_title'    => lang('Reward the user'),
            'sheet'         => 'admin',
        ];

        return view('/templates/badge/user-add', ['data' => $data, 'uid' => $uid, 'user' => $user, 'badges' => $badges]);
    }

    // Награждение
    public function addUser()
    {
        $user_id    = Request::getPostInt('user_id');
        $badge_id   = Request::getPostInt('badge_id');

        BadgeModel::badgeUserAdd($user_id, $badge_id);

        Base::addMsg(lang('Reward added'), 'success');

        redirect('/admin/users/' . $user_id . '/edit');
    }



    // Измененяем награду
    public function edit()
    {
        $badge_id   = Request::getInt('id');
        $badge      = BadgeModel::getBadgeId($badge_id);

        $redirect = '/admin/badges';
        if (!$badge['badge_id']) {
            redirect($redirect);
        }

        $badge_title         = Request::getPost('badge_title');
        $badge_description   = Request::getPost('badge_description');
        $badge_icon          = $_POST['badge_icon']; // не фильтруем

        Base::Limits($badge_title, lang('Title'), '4', '25', $redirect);
        Base::Limits($badge_description, lang('Description'), '12', '250', $redirect);
        Base::Limits($badge_icon, lang('Icon'), '12', '250', $redirect);

        $data = [
            'badge_id'          => $badge_id,
            'badge_title'       => $badge_title,
            'badge_description' => $badge_description,
            'badge_icon'        => $badge_icon,
        ];

        BadgeModel::edit($data);
        redirect($redirect);
    }
}
