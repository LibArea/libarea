<?php

namespace Modules\Teams\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Teams\App\Models\TeamModel;
use UserData, Meta, Html, Validation;

class Edit
{
    protected $limit = 5;

    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Command edit form
    // Форма редактирование команды
    public function index()
    {
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        $id = Request::getInt('id');

        $team = TeamModel::get($id);
        if ($team['user_id'] != $this->user['id']) {
            return;
        }

        return view(
            '/view/default/edit',
            [
                'meta'  => Meta::get(__('team.edit')),
                'user'  => $this->user,
                'data'  => [
                    'type'  => 'edit',
                    'team'  => $team,
                    'users' => TeamModel::getUsersTeam($team['id']),
                ]
            ]
        );
    }

    // Team change
    // Изменение команды
    public function change()
    {
        $team = TeamModel::get(Request::getPostInt('id'));
        if ($team['user_id'] != $this->user['id']) {
            return true;
        }

        $name = Request::getPost('name');
        $content = Request::getPost('content');

        if (!Validation::length($name, 6, 250)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.title') . '»'])]);
        }

        if (!Validation::length($content, 6, 5000)) {
            return json_encode(['error' => 'error', 'text' => __('msg.string_length', ['name' => '«' . __('msg.content') . '»'])]);
        }

        TeamModel::edit(
            [
                'id'            => $team['id'],
                'name'          => $name,
                'content'       => $content,
                'action_type'   => 'post',
                'updated_at'    => date("Y-m-d H:i:s"),
            ]
        );

        $users    = Request::getPost() ?? [];
        self::editUser($users, $team['id']);

        Validation::ComeBack('team.change', 'success', url('teams'));
    }

    // Add fastes (blogs, topics) to the post 
    public static function editUser($users, $content_id)
    {
        $arr = $users['user_id'] ?? [];
        $arr_user = json_decode($arr, true);

        return TeamModel::editUsersRelation($arr_user, $content_id);
    }

}
