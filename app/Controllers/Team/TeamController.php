<?php

namespace App\Controllers\Team;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\TeamModel;
use Meta, Html;

class TeamController extends Controller
{
    protected $limit = 5;

    // All commands created by the user
    // Все команды созданные пользователем
    public function index()
    {
        return $this->render(
            '/team/user',
            'base',
            [
                'meta'  => Meta::get(__('team.home')),
                'user'  => $this->user,
                'data'  => [
                    'type'  => 'teams',
                    'teams' => TeamModel::all($this->user['id'], $this->limit),
                    'limit' => $this->limit,
                    'count' => TeamModel::allCount($this->user['id']),
                ]
            ]
        );
    }

    // Team View
    // Просмотр команды
    public function view()
    {
        $id = Request::getInt('id');
        $team = TeamModel::get($id);
        if ($team['user_id'] != $this->user['id']) {
            return;
        }

        return $this->render(
            '/team/view',
            'base',
            [
                'meta'  => Meta::get(__('team.home')),
                'user'  => $this->user,
                'data'  => [
                    'type'          => 'view',
                    'team'          => $team,
                    'team_users'    => TeamModel::getUsersTeam($team['id']),
                ]
            ]
        );
    }

    // Formation of team members
    public static function users($users)
    {
        if (!$users) {
            return '';
        }

        if (!is_array($users)) {
            $users = preg_split('/(@)/', $users);
        }

        $result = [];
        foreach (array_chunk($users, 3) as $ind => $row) {
            $result[] = '<a class="mr15 gray-600" href="' . url('profile', ['login' => $row[1]]) . '">
            ' . Html::image($row[2], $row[1], 'img-sm', 'avatar', 'small') . '
            ' . $row[1] . '</a>';
        }

        return implode($result);
    }
}
