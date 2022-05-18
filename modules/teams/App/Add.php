<?php

namespace Modules\Teams\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Teams\App\Models\TeamModel;
use UserData, Meta, Html, Validation;

class Add
{
    protected $limit = 5;

    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Team creation form
    // Форма создание команды
    public function index()
    {
        return view(
            '/view/default/add',
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
        if ($count > $this->limit) {
            return;
        }

        $name = Request::getPost('name');
        $content = Request::getPost('content');

        Validation::Length($name, 'msg.title', '6', '250', url('team.add'));
        Validation::Length($content, 'msg.content', '6', '5000', url('team.add'));

        TeamModel::create(
            [
                'name'      => $name,
                'content'   => $content,
                'user_id'   => $this->user['id'],
                'action_type' => 'post',
            ]
        );
        Validation::ComeBack('team.created', 'success', url('teams'));
    }
}
