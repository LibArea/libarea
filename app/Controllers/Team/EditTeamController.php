<?php

namespace App\Controllers\Team;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\TeamModel;
use Meta, Validation;

class EditTeamController extends Controller
{
    protected $limit = 5;

    // Command edit form
    // Форма редактирование команды
    public function index()
    {
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        $id = Request::getInt('id');

        $team = TeamModel::get($id);
        if ($team['team_user_id'] != $this->user['id']) {
            return;
        }

        return $this->render(
            '/team/edit',
            'base',
            [
                'meta'  => Meta::get(__('team.edit')),
                'user'  => $this->user,
                'data'  => [
                    'type'  => 'edit',
                    'team'  => $team,
                    'users' => TeamModel::getUsersTeam($team['team_id']),
                ]
            ]
        );
    }

    // Team change
    // Изменение команды
    public function change()
    {
        $team = TeamModel::get(Request::getPostInt('id'));

        if ($team['team_user_id'] != $this->user['id']) {
            return true;
        }

        $name = Request::getPost('name');
        $content = Request::getPost('content');

        $redirect = url('teams');

        Validation::length($name, 6, 250, 'title', $redirect);
        Validation::length($content, 6, 5000, 'content', $redirect);

        TeamModel::edit(
            [
                'team_id'       => $team['id'],
                'team_name'     => $name,
                'team_content'  => $content,
                'team_type'     => 'post',
                'team_modified' => date("Y-m-d H:i:s"),
            ]
        );

        $users    = Request::getPost() ?? [];
        self::editUser($users, $team['team_id']);

        Validation::comingBack(__('team.change'), 'success', url('teams'));
    }

    // Add fastes (blogs, topics) to the post 
    public static function editUser($users, $content_id)
    {
        $arr = $users['user_id'] ?? [];
        $arr_user = json_decode($arr, true);

        return TeamModel::editUsersRelation($arr_user, $content_id);
    }
}
