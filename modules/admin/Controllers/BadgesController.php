<?php

namespace Modules\Admin\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\BadgeModel;
use App\Models\UserModel;
use Agouti\{Base, Validation};

class BadgesController extends MainController
{
    // Все награды
    public function index($sheet)
    {
        $badges = BadgeModel::getBadgesAll();

        $meta = [
            'meta_title'    => lang('Badges'),
            'sheet'         => 'badges',
        ];
        
        $data = [
            'sheet'         => $sheet == 'all' ? 'badges' : $sheet,
            'badges'        => $badges,
        ];
        
        return view('/badge/badges', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    // Форма добавления награды
    public function addPage()
    {
        $meta = [
            'meta_title'    => lang('Add badge'),
            'sheet'         => 'badges',
        ];
        
        return view('/badge/add', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => []]);
    }


    // Форма изменения награды
    public function editPage()
    {
        $badge_id   = Request::getInt('id');
        $badge      = BadgeModel::getBadgeId($badge_id);

        if (!$badge['badge_id']) {
            redirect('/admin/badges');
        }

        $meta = [
            'meta_title'    => lang('Edit badge'),
            'sheet'         => 'badges',
        ];
        
        $data = [
            'badge'         => $badge
        ];
        
        return view('/badge/edit', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    // Добавляем награду
    public function add()
    {
        $badge_title         = Request::getPost('badge_title');
        $badge_description   = Request::getPost('badge_description');
        $badge_icon          = $_POST['badge_icon']; // для Markdown

        $redirect = '/admin/badges';
        Validation::Limits($badge_title, lang('Title'), '4', '25', $redirect);
        Validation::Limits($badge_description, lang('Description'), '12', '250', $redirect);
        Validation::Limits($badge_icon, lang('Icon'), '12', '250', $redirect);

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
        $user_id    = Request::getInt('id');
        if ($user_id > 0) {
            $user   = UserModel::getUser($user_id, 'id');
        } else {
            $user   = null;
        }

        $badges = BadgeModel::getBadgesAll();

        $meta = [
            'meta_title'    => lang('Reward the user'),
            'sheet'         => 'admin',
        ];
        
        $data = [
            'sheet'         => 'admin',
            'user'          => $user,
            'badges'        => $badges,
        ];
        
        return view('/badge/user-add', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    // Награждение
    public function addUser()
    {
        $user_id    = Request::getPostInt('user_id');
        $badge_id   = Request::getPostInt('badge_id');

        BadgeModel::badgeUserAdd($user_id, $badge_id);

        addMsg(lang('Reward added'), 'success');

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
        $badge_icon          = $_POST['badge_icon']; // для Markdown

        Validation::Limits($badge_title, lang('Title'), '4', '25', $redirect);
        Validation::Limits($badge_description, lang('Description'), '12', '250', $redirect);
        Validation::Limits($badge_icon, lang('Icon'), '12', '250', $redirect);

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
