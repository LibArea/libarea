<?php

namespace App\Controllers\Team;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\TeamModel;
use App\Validate\RulesTeam;
use Meta;

class AddTeamController extends Controller
{
    protected $limit = 5;

    // Team creation form
    // Форма создание команды
    public function index()
    {
        return $this->render(
            '/team/add',
            'base',
            [
                'meta'  => Meta::get(__('team.add')),
                'user'  => $this->user,
                'data'  => [
                    'type'      => 'add',
                    'teams'     => [],
                ]
            ]
        );
    }

    // Adding a team
    // Добавление команды
    public function create()
    {
        $count = TeamModel::allCount($this->user['id']);
        if ($count > $this->limit) return;

        $data = Request::getPost();
        
        RulesTeam::rules($data, url('team.add'));

        TeamModel::create(
            [
                'team_name'     => $data['name'],
                'team_content'  => $data['content'],
                'team_user_id'  => $this->user['id'],
                'team_type'     => 'post',
            ]
        );
        is_return(__('team.created'), 'success', url('teams'));
    }
}
