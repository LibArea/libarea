<?php

namespace Modules\Teams\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Teams\App\Models\TeamModel;
use UserData, Meta, Html, Validation, Translate;

class Teams
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
                'meta'  => Meta::get(__('teams')),
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
                'meta'  => Meta::get(__('team')),
                'user'  => $this->user,
                'data'  => [
                    'type'          => 'view',
                    'team'          => $team,
                    'team_users'    => TeamModel::getUsersTeam($team['id']),
                ]
            ]
        );
    }

    // Team creation form
    // Форма создание команды
    public function add()
    {
        return view(
            '/view/default/add',
            [
                'meta'  => Meta::get(__('add') . ' ' . __('teams')),
                'user'  => $this->user,
                'data'  => [
                    'type'      => 'add',
                    'teams'     => [],
                ]
            ]
        );
    }

    // Command edit form
    // Форма редактирование команды
    public function edit()
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
                'meta'  => Meta::get(__('edit') . ' ' . __('teams')),
                'user'  => $this->user,
                'data'  => [
                    'type'  => 'edit',
                    'team'  => $team,
                    'users' => TeamModel::getUsersTeam($team['id']),
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

        Validation::Length($name, Translate::get('title'), '6', '250', getUrlByName('team.add'));
        Validation::Length($content, Translate::get('the post'), '6', '5000', getUrlByName('team.add'));

        TeamModel::create(
            [
                'name'      => $name,
                'content'   => $content,
                'user_id'   => $this->user['id'],
            ]
        );

        Html::addMsg('team.created', 'success');
        redirect(getUrlByName('teams'));
    }

    // Team change
    // Изменение команды
    public function change()
    {
        $id     = Request::getPostInt('id');
        $team   = TeamModel::get($id);
        if ($team['user_id'] != $this->user['id']) {
            return;
        }

        $name = Request::getPost('name');
        $content = Request::getPost('content');

        Validation::Length($name, Translate::get('title'), '6', '250', getUrlByName('team.add'));
        Validation::Length($content, Translate::get('the post'), '6', '5000', getUrlByName('team.add'));

        TeamModel::edit(
            [
                'id'            => $id,
                'name'          => $name,
                'content'       => $content,
                'updated_at'    => date("Y-m-d H:i:s"),
            ]
        );

        $users    = Request::getPost() ?? [];
        self::editUser($users, $id);

        Html::addMsg('team.change', 'success');
        redirect(getUrlByName('teams'));
    }


    // Add fastes (blogs, topics) to the post 
    public static function editUser($users, $content_id)
    {
        $arr = $users['user_id'] ?? [];
        $arr_user = json_decode($arr, true);

        return TeamModel::editUsersRelation($arr_user, $content_id);
    }

    // Deleting or restoring a team
    // Удаление и восстановление команды
    public function action()
    {
        $id = Request::getPostInt('id');

        $team = TeamModel::get($id);
        if ($team['user_id'] != $this->user['id']) {
            return;
        }

        TeamModel::action($id, $team['is_deleted']);
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
            $result[] = '<a class="mr15 gray-600" href="' . getUrlByName('profile', ['login' => $row[1]]) . '">
            ' . Html::image($row[2], $row[1], 'ava-sm', 'avatar', 'small') . '
            ' . $row[1] . '</a>';
        }

        return implode($result);
    }
    
    
}
