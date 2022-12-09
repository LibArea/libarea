<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\FacetPresence;
use App\Models\FacetModel;
use Meta, UserData;

class TeamFacetController extends Controller
{
    public function index()
    {
        $type   = $this->accessType(Request::get('type'));
        $facet  = FacetPresence::index(Request::getInt('id'), 'id', $type);

        $this->access($facet['facet_user_id']);

        $users_team = FacetModel::getUsersTeam($facet['facet_id']);

        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        return $this->render(
            '/facets/team',
            [
                'meta'  => Meta::get(__('app.team') . ' | ' . $facet['facet_title']),
                'data'  => [
                    'facet'         => $facet,
                    'sheet'         => $facet['facet_type'] . 's',
                    'type'          => $type,
                    'users_team'    => $users_team,
                ]
            ]
        );
    }

    // Team change
    // Изменение команды
    public function change()
    {
        $type = $this->accessType(Request::get('type'));
        $facet = FacetPresence::index(Request::getInt('id'), 'id', $type);

        $this->access($facet['facet_user_id']);

        $users = Request::getPost() ?? [];
        self::editUser($users, $facet['facet_id']);

        is_return(__('msg.change_saved'), 'success', url('team.edit', ['id' => $facet['facet_id'], 'type' => $type]));
    }

    // We will re-register the participantsы
    // Перезапишем участников 
    public static function editUser($users, $content_id)
    {
        $arr = $users['user_id'] ?? [];
        $arr_user = json_decode($arr, true);

        return FacetModel::editUsersTeam($arr_user, $content_id);
    }

    // Only author and admin get accesss
    // Доступ получает только автор и админ
    public function access($facet_user_id)
    {
        if ($facet_user_id != $this->user['id'] && !UserData::checkAdmin()) {
            redirect('/');
        }

        return true;
    }

    // Allowed facet types    
    // Разрешенные типы фасета
    public function accessType($type)
    {
        if (!in_array($type = Request::get('type'), config('facets.permitted'))) {
            notEmptyOrView404([]);
        }

        return $type;
    }
}
