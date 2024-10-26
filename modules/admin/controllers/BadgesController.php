<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Constructor\Data\View;
use Hleb\Static\Request;
use Hleb\Base\Module;
use Modules\Admin\Models\{BadgeModel, UserModel};
use Modules\Admin\Validate\RulesBadge;
use Meta, Msg;

class BadgesController extends Module
{
    protected $type = 'badges';

    /**
     * All awards
     * Все награды
     */
    public function index(): View
    {
        return view(
            '/badge/badges',
            [
                'meta'  => Meta::get(__('admin.badges')),
                'data'  => [
                    'type'      => $this->type,
                    'badges'    => BadgeModel::getAll(),
                ]
            ]
        );
    }

    /**
     * Form for adding an award
     * Форма добавления награды
     */
    public function add(): View
    {
        return view(
            '/badge/add',
            [
                'meta'  => Meta::get(__('admin.badges')),
                'data'  => [
                    'type'  => $this->type,
                ]
            ]
        );
    }

    /**
     * Reward change form 
     * Форма изменения награды
     */
    public function editBadge(): View
    {
        $badge_id   = Request::param('id')->asPositiveInt();
        $badge      = BadgeModel::getId($badge_id);
        notEmptyOrView404($badge);

        return view(
            '/badge/edit',
            [
                'meta'  => Meta::get(__('admin.edit')),
                'data'  => [
                    'badge' => $badge,
                    'type'  => $this->type,
                ]
            ]
        );
    }

    /**
     * Adding a reward
     * Добавляем награду
     *
     * @return void
     */
    public function create()
    {
        $data   = Request::allPost();
        $icon   = $_POST['badge_icon']; // для Markdown

        RulesBadge::rules($data, $icon);

        BadgeModel::add(
            [
                'badge_title'       => $data['badge_title'],
                'badge_description' => $data['badge_description'],
                'badge_icon'        => $icon,
                'badge_tl'          => 0,
                'badge_score'       => 0,
            ]
        );

        redirect(url('admin.badges'));
    }

    /**
     * Participant award form
     * Форма награждения участника
     */
    public function addBadgeUser(): View
    {
        $user_id    = Request::param('id')->asInt();
        $user       = UserModel::getUser($user_id, 'id');

        return view(
            '/badge/user-add',
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
        $uid = Request::post('user_id')->asInt();
        $badge_id = Request::post('badge_id')->asInt();

        BadgeModel::badgeUserAdd(
            [
                'user_id'   => $uid,
                'badge_id'  => $badge_id
            ]
        );

        Msg::redirect(__('msg.successfully'), 'success', url('admin.user.edit.form', ['id' => $uid]));
    }

    public function edit()
    {
        $badge_id   = Request::param('id')->asPositiveInt();
        $badge      = BadgeModel::getId($badge_id);
        $data       = Request::allPost();

        $redirect   = url('admin.badges');

        if (!$badge['badge_id']) {
            redirect($redirect);
        }

        $icon   = $_POST['badge_icon']; // для Markdown

        RulesBadge::rules($data, $icon);

        BadgeModel::edit(
            [
                'badge_id'          => $badge_id,
                'badge_title'       => $data['badge_title'],
                'badge_description' => $data['badge_description'],
                'badge_icon'        => $icon,
            ]
        );

        Msg::redirect(__('msg.change_saved'), 'success', $redirect);
    }

    public function remove()
    {
        $uid = Request::post('uid')->asInt();
        BadgeModel::remove(
            [
                'bu_id'         => Request::post('id')->asInt(),
                'bu_user_id'    => $uid,
            ]
        );

        Msg::redirect(__('msg.command_executed'), 'success', '/admin/users/' . $uid . '/edit');
    }
}
