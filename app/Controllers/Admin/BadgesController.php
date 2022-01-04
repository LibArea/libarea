<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\User\{UserModel, BadgeModel};
use Validation, Translate;

class BadgesController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    // Все награды
    public function index($sheet, $type)
    {
        return agRender(
            '/admin/badge/badges',
            [
                'meta'  => meta($m = [], Translate::get('badges')),
                'uid'   => $this->uid,
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                    'badges'    => BadgeModel::getBadgesAll(),
                ]
            ]
        );
    }

    // Форма добавления награды
    public function addPage($sheet, $type)
    {
        return agRender(
            '/admin/badge/add',
            [
                'meta'  => meta($m = [], Translate::get('add badge')),
                'uid'   => $this->uid,
                'data'  => [
                    'type'  => $type,
                    'sheet' => $sheet,
                ]
            ]
        );
    }

    // Форма изменения награды
    public function editPage($sheet, $type)
    {
        $badge_id   = Request::getInt('id');
        $badge      = BadgeModel::getBadgeId($badge_id);

        if (!$badge['badge_id']) {
            redirect('/admin/badges');
        }

        return agRender(
            '/admin/badge/edit',
            [
                'meta'  => meta($m = [], Translate::get('edit badge')),
                'uid'   => $this->uid,
                'data'  => [
                    'badge' => $badge,
                    'sheet' => $sheet,
                    'type'  => $type,
                ]
            ]
        );
    }

    // Добавляем награду
    public function create()
    {
        $badge_title         = Request::getPost('badge_title');
        $badge_description   = Request::getPost('badge_description');
        $badge_icon          = $_POST['badge_icon']; // для Markdown

        $redirect = getUrlByName('admin.badges');
        Validation::Limits($badge_title, Translate::get('title'), '4', '25', $redirect);
        Validation::Limits($badge_description, Translate::get('description'), '12', '250', $redirect);
        Validation::Limits($badge_icon, Translate::get('icon'), '12', '250', $redirect);

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
    public function addUserPage($sheet, $type)
    {
        $user_id    = Request::getInt('id');
        if ($user_id > 0) {
            $user   = UserModel::getUser($user_id, 'id');
        } else {
            $user   = null;
        }

        return agRender(
            '/admin/badge/user-add',
            [
                'meta'  => meta($m = [], Translate::get('reward the user')),
                'uid'   => $this->uid,
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                    'user'      => $user,
                    'badges'    => BadgeModel::getBadgesAll(),
                ]
            ]
        );
    }

    // Награждение
    public function addUser()
    {
        $user_id    = Request::getPostInt('user_id');
        $badge_id   = Request::getPostInt('badge_id');

        BadgeModel::badgeUserAdd($user_id, $badge_id);

        addMsg(Translate::get('reward added'), 'success');

        redirect('/admin/users/' . $user_id . '/edit');
    }

    // Измененяем награду
    public function edit()
    {
        $badge_id   = Request::getInt('id');
        $badge      = BadgeModel::getBadgeId($badge_id);

        $redirect = getUrlByName('admin.badges');
        if (!$badge['badge_id']) {
            redirect($redirect);
        }

        $badge_title         = Request::getPost('badge_title');
        $badge_description   = Request::getPost('badge_description');
        $badge_icon          = $_POST['badge_icon']; // для Markdown

        Validation::Limits($badge_title, Translate::get('title'), '4', '25', $redirect);
        Validation::Limits($badge_description, Translate::get('description'), '12', '250', $redirect);
        Validation::Limits($badge_icon, Translate::get('icon'), '12', '250', $redirect);

        $data = [
            'badge_id'          => $badge_id,
            'badge_title'       => $badge_title,
            'badge_description' => $badge_description,
            'badge_icon'        => $badge_icon,
        ];

        BadgeModel::edit($data);
        redirect($redirect);
    }

    public function remove()
    {
        $id     = Request::getPostInt('id');
        $uid    = Request::getPostInt('uid');

        BadgeModel::remove($id, $uid);

        addMsg(Translate::get('the command is executed'), 'success');

        redirect('/admin/users/' . $uid . '/edit');
    }
}
