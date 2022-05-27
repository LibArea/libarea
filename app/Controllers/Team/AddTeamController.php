<?php

namespace App\Controllers\Team;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\TeamModel;
use Meta, Validation;

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
        //$count = TeamModel::allCount($this->user['id']);
        //if ($count > $this->limit) {
        //  return;
        // }

        $name = Request::getPost('name');
        $content = Request::getPost('content');

        Validation::Length($name, 'msg.title', '6', '250', url('team.add'));
        Validation::Length($content, 'msg.content', '6', '5000', url('team.add'));

        TeamModel::create(
            [
                'team_name'     => $name,
                'team_content'  => $content,
                'team_user_id'  => $this->user['id'],
                'team_type'     => 'post',
            ]
        );
        Validation::ComeBack('team.created', 'success', url('teams'));
    }
}
