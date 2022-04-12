<?php

namespace Modules\Teams\App\Models;

use DB;

class TeamModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Information by id
    // Информация по id
    public static function get($id)
    {
        $sql = "SELECT 
                    t.id,
                    t.name,
                    t.content,
                    t.user_id,
                    t.created_at,
                    t.updated_at,
                    t.is_deleted,
                    u.id as uid,
                    u.login,
                    u.avatar
                        FROM teams t
                        LEFT JOIN users u ON t.user_id = u.id
                            WHERE t.id = :id";

        return  DB::run($sql, ['id' => $id])->fetch();
    }

    // Get the full version of teams with participants in them
    // Получим полную версию команд с участниками в них
     public static function all($uid, $limit)
     {
         $sql = "SELECT 
                    t.id,
                    t.name,
                    t.content,
                    t.user_id,
                    t.created_at,
                    t.updated_at,
                    t.is_deleted,
                    u.id as uid,
                    u.login,
                    u.avatar,
                    rel.*
                     FROM teams t
                        LEFT JOIN users u ON t.user_id = u.id
                         LEFT JOIN (
                            SELECT 
                                team_id,
                                GROUP_CONCAT(id, '@', login, '@', avatar SEPARATOR '@') AS users_list
                                FROM users
                                LEFT JOIN teams_users_relation 
                                    on user_id = id
                                        GROUP BY team_id
                            ) AS rel
                                ON rel.team_id= t.id
                                        WHERE u.id = :uid LIMIT :limit";
                            
        return DB::run($sql, ['uid' => $uid, 'limit' => $limit])->fetchAll(); 
    }

    // Number of folders
    // Количество команд
    public static function allCount($uid)
    {
        return  DB::run("SELECT id FROM teams WHERE user_id = :uid", ['uid' => $uid])->rowCount();
    }

    // Creation of a team
    // Добавление команды
    public static function create($params)
    {
        $sql    = "INSERT INTO teams (name, content, user_id, action_type) VALUES (:name, :content, :user_id, :action_type)";

        return DB::run($sql, $params);
    }

    // Editing a team
    // Изменим команду
    public static function edit($params)
    {
        $sql_two = "UPDATE teams 
                        SET name = :name, 
                            content = :content, 
                            action_type = :action_type, 
                            updated_at = :updated_at 
                                WHERE id = :id";

        return DB::run($sql_two, $params);
    }

    // Deleting and restoring a team
    // Удаление и восстановление команды
    public static function action($id, $status)
    {
        $sql = "UPDATE teams SET is_deleted = 1 where id = :id";
        if ($status == 1) {
            $sql = "UPDATE teams SET is_deleted = 0 where id = :id";
        }

        DB::run($sql, ['id' => $id]);
    }

    // Add, change users in the team
    // Добавим, изменим пользователей в команде
    public static function editUsersRelation($rows, $team_id)
    {
        self::deleteUsersRelation($team_id);

        foreach ($rows as $row) {
            $user_id   = $row['id'];
            $sql        = "INSERT INTO teams_users_relation (team_id, user_id) 
                                VALUES (:team_id, :user_id)";

            DB::run($sql, ['team_id' => $team_id, 'user_id' => $user_id]);
        }

        return true;
    }

    public static function deleteUsersRelation($team_id)
    {
        $sql = "DELETE FROM teams_users_relation WHERE team_id = :team_id";

        return DB::run($sql, ['team_id' => $team_id]);
    }

    // Team Members
    // Участники в команде
    public static function getUsersTeam($team_id)
    {
        $sql = "SELECT 
                    u.id as value,
                    u.login,
                    u.avatar
                        FROM teams_users_relation t
                        LEFT JOIN users u ON t.user_id = u.id
                            WHERE t.team_id = :team_id";

        return DB::run($sql, ['team_id' => $team_id])->fetchAll();
    }
}
