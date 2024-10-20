<?php

declare(strict_types=1);

namespace App\Controllers\Facet;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\FacetPresence;
use App\Models\FacetModel;
use Meta, Msg;

class TeamFacetController extends Controller
{
    public function index(): void
    {
        $type   = $this->accessType(Request::param('type')->asString());
        $facet  = FacetPresence::index(Request::param('id')->asPositiveInt(), 'id', $type);

        $this->access($facet['facet_user_id']);

        $users_team = FacetModel::getUsersTeam($facet['facet_id']);

        render(
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

    /**
     * Team change
     * Изменение команды
     *
     * @return void
     */
    public function edit(): void
    {
        $type = $this->accessType(Request::param('type')->asString());
        $facet = FacetPresence::index(Request::param('id')->asPositiveInt(), 'id', $type);

        $this->access($facet['facet_user_id']);

        $users = Request::allPost() ?? [];

        self::editUser($users, $facet['facet_id']);

        Msg::redirect(__('msg.change_saved'), 'success', url('team.form.edit', ['id' => $facet['facet_id'], 'type' => $type]));
    }

    /**
     * UWe will re-register the participants
     * Перезапишем участников 
     *
     * @param array $users
     * @param int $content_id
     * @return true
     */
    public static function editUser(array $users, int $content_id)
    {
        $arr = $users['user_id'] ?? [];
        $arr_user = json_decode($arr, true);

        return FacetModel::editUsersTeam($arr_user, $content_id);
    }

    /**
     * Only author and admin get accesss
     * Доступ получает только автор и админ
     *
     * @param int $facet_user_id
     * @return void
     */
    public function access(int $facet_user_id)
    {
        if ($facet_user_id != $this->container->user()->id() && !$this->container->user()->admin()) {
            redirect('/');
        }

        return true;
    }

    /**
     * Allowed facet types 
     * Разрешенные типы фасета
     *
     * @param string $type
     * @return string
     */
    public function accessType(string $type): string
    {
        if (!in_array($type = Request::param('type')->asString(), config('facets', 'permitted'))) {
            notEmptyOrView404([]);
        }

        return $type;
    }
}
