<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\User\{UserModel, BadgeModel};
use Validation, Translate;

class Badges
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // All awards
    // Все награды
    public function index($sheet, $type)
    {
        return view(
            '/view/default/badge/badges',
            [
                'meta'  => meta($m = [], Translate::get('badges')),
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                    'badges'    => BadgeModel::getAll(),
                ]
            ]
        );
    }

    // Form for adding an award
    // Форма добавления награды
    public function addPage($sheet, $type)
    {
        return view(
            '/view/default/badge/add',
            [
                'meta'  => meta($m = [], sprintf(Translate::get('add.option'), Translate::get('badges'))),
                'data'  => [
                    'type'  => $type,
                    'sheet' => $sheet,
                ]
            ]
        );
    }

    // Reward change form 
    // Форма изменения награды
    public function editPage($sheet, $type)
    {
        $badge_id   = Request::getInt('id');
        $badge      = BadgeModel::getId($badge_id);
        pageError404($badge);

        return view(
            '/view/default/badge/edit',
            [
                'meta'  => meta($m = [], Translate::get('edit')),
                'data'  => [
                    'badge' => $badge,
                    'sheet' => $sheet,
                    'type'  => $type,
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

        $redirect = getUrlByName('admin.badges');
        Validation::Length($title, Translate::get('title'), '4', '25', $redirect);
        Validation::Length($description, Translate::get('description'), '12', '250', $redirect);
        Validation::Length($icon, Translate::get('icon'), '12', '250', $redirect);

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
    public function addUserPage($sheet, $type)
    {
        $user_id    = Request::getInt('id');
        $user       = UserModel::getUser($user_id, 'id');

        return view(
            '/view/default/badge/user-add',
            [
                'meta'  => meta($m = [], Translate::get('reward the user')),
                'data'  => [
                    'type'      => $type,
                    'sheet'     => $sheet,
                    'user'      => $user ?? null,
                    'badges'    => BadgeModel::getAll(),
                ]
            ]
        );
    }

    public function addUser()
    {
        $uid = Request::getPostInt('user_id');
        BadgeModel::badgeUserAdd(
            [
                'user_id'   => $uid,
                'badge_id'  => Request::getPostInt('badge_id')
            ]
        );

        addMsg(Translate::get('successfully'), 'success');

        redirect('/admin/users/' . $uid . '/edit');
    }

    public function edit()
    {
        $badge_id   = Request::getInt('id');
        $badge      = BadgeModel::getId($badge_id);

        $redirect = getUrlByName('admin.badges');
        if (!$badge['badge_id']) {
            redirect($redirect);
        }

        $title         = Request::getPost('badge_title');
        $description   = Request::getPost('badge_description');
        $icon          = $_POST['badge_icon']; // для Markdown

        Validation::Length($title, Translate::get('title'), '4', '25', $redirect);
        Validation::Length($description, Translate::get('description'), '12', '250', $redirect);
        Validation::Length($icon, Translate::get('icon'), '12', '250', $redirect);

        BadgeModel::edit(
            [
                'badge_id'          => $badge_id,
                'badge_title'       => $title,
                'badge_description' => $description,
                'badge_icon'        => $icon,
            ]
        );

        redirect($redirect);
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

        addMsg(Translate::get('the command is executed'), 'success');

        redirect('/admin/users/' . $uid . '/edit');
    }
}
