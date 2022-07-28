<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{UserModel, BadgeModel};
use Validation, Meta;

class Badges extends Controller
{
    protected $type = 'badges';

    // All awards
    // Все награды
    public function index()
    {
        return view(
            '/view/default/badge/badges',
            [
                'meta'  => Meta::get(__('admin.badges')),
                'data'  => [
                    'type'      => $this->type,
                    'badges'    => BadgeModel::getAll(),
                ]
            ]
        );
    }

    // Form for adding an award
    // Форма добавления награды
    public function add()
    {
        return view(
            '/view/default/badge/add',
            [
                'meta'  => Meta::get(__('admin.badges')),
                'data'  => [
                    'type'  => $this->type,
                ]
            ]
        );
    }

    // Reward change form 
    // Форма изменения награды
    public function edit()
    {
        $badge_id   = Request::getInt('id');
        $badge      = BadgeModel::getId($badge_id);
        self::error404($badge);

        return view(
            '/view/default/badge/edit',
            [
                'meta'  => Meta::get(__('admin.edit')),
                'data'  => [
                    'badge' => $badge,
                    'type'  => $this->type,
                ]
            ]
        );
    }

    // Adding a reward 
    // Добавляем награду
    public function create()
    {
        $title         = Request::getPost('badge_title');
        $description   = Request::getPost('badge_description');
        $icon          = $_POST['badge_icon']; // для Markdown

        $redirect = url('admin.badges');
        Validation::Length($title, '4', '25', 'msg.title', $redirect);
        Validation::Length($description, '12', '250', 'msg.description', $redirect);
        Validation::Length($icon, '12', '250', 'msg.icon', $redirect);

        BadgeModel::add(
            [
                'badge_title'       => $title,
                'badge_description' => $description,
                'badge_icon'        => $icon,
                'badge_tl'          => 0,
                'badge_score'       => 0,
            ]
        );

        redirect($redirect);
    }

    // Participant award form
    // Форма награждения участника
    public function addUser()
    {
        $user_id    = Request::getInt('id');
        $user       = UserModel::getUser($user_id, 'id');

        return view(
            '/view/default/badge/user-add',
            [
                'meta'  => Meta::get(__('admin.badges')),
                'data'  => [
                    'type'      => $this->type,
                    'user'      => $user ?? null,
                    'badges'    => BadgeModel::getAll(),
                ]
            ]
        );
    }

    public function rewarding()
    {
        $uid = Request::getPostInt('user_id');
        $badge_id = Request::getPostInt('badge_id');

        BadgeModel::badgeUserAdd(
            [
                'user_id'   => $uid,
                'badge_id'  => $badge_id
            ]
        );

        is_return(__('msg.successfully'), 'success', url('admin.user.edit', ['id' => $uid]));
    }

    public function change()
    {
        $badge_id   = Request::getInt('id');
        $badge      = BadgeModel::getId($badge_id);

        $redirect = url('admin.badges');
        if (!$badge['badge_id']) {
            redirect($redirect);
        }

        $title         = Request::getPost('badge_title');
        $description   = Request::getPost('badge_description');
        $icon          = $_POST['badge_icon']; // для Markdown

        Validation::Length($title, 4, 25, 'title', $redirect);
        Validation::Length($description, 12, 250, 'description', $redirect);
        Validation::Length($icon, 6, 250, 'icon', $redirect);

        BadgeModel::edit(
            [
                'badge_id'          => $badge_id,
                'badge_title'       => $title,
                'badge_description' => $description,
                'badge_icon'        => $icon,
            ]
        );

        is_return(__('msg.change_saved'), 'success', $redirect);
    }

    public function remove()
    {
        $uid = Request::getPostInt('uid');
        BadgeModel::remove(
            [
                'bu_id'         => Request::getPostInt('id'),
                'bu_user_id'    => $uid,
            ]
        );

        is_return(__('msg.command_executed'), 'success', '/admin/users/' . $uid . '/edit');
    }
}
