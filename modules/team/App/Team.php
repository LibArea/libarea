<?php

namespace Modules\Team\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Team\App\Models\TeamModel;
use UserData, Meta, Html, Validation;

class Team
{
    protected $limit = 5;

    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // All commands created by the user
    // Все команды созданные пользователем
    public function index()
    {
        Request::getResources()->addBottomScript('/assets/js/team.js');

        $teams = TeamModel::all($this->user['id'], $this->limit);

        return view(
            '/view/default/user',
            [
                'meta'  => Meta::get(__('team.home')),
                'user'  => $this->user,
                'data'  => [
                    'type'  => 'teams',
                    'teams' => $teams,
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

        return view(
            '/view/default/view',
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

    // Deleting or restoring a team
    // Удаление и восстановление команды
    public function action()
    {
        $id     = Request::getPostInt('id');
        $team   = TeamModel::get($id);
        if ($team['team_user_id'] != $this->user['id']) {
            return;
        }

        TeamModel::action($id, $team['team_is_deleted']);
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
