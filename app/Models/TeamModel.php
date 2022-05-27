<?php

namespace App\Models;

use DB;

class TeamModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Information by id
    // Информация по id
    public static function get($id)
    {
        $sql = "SELECT 
                    team_id,
                    team_name,
                    team_content,
                    team_user_id,
                    team_date,
                    team_modified,
                    team_is_deleted,
                    id as uid,
                    login,
                    avatar
                        FROM teams 
                        LEFT JOIN users u ON team_user_id = id
                            WHERE team_id = :id";

        return  DB::run($sql, ['id' => $id])->fetch();
    }

    // Get the full version of teams with participants in them
    // Получим полную версию команд с участниками в них
     public static function all($uid, $limit)
     {
         $sql = "SELECT 
                    t.team_id as id,
                    t.team_name,
                    t.team_content,
                    t.team_user_id,
                    t.team_date,
                    t.team_modified,
                    t.team_is_deleted,
                    u.id as uid,
                    u.login,
                    u.avatar,
                    rel.*
                     FROM teams t
                        LEFT JOIN users u ON t.team_user_id = u.id
                         LEFT JOIN (
                            SELECT 
                               r.team_id,
                                GROUP_CONCAT(id, '@', login, '@', avatar SEPARATOR '@') AS users_list
                                FROM users
                                LEFT JOIN teams_users_relation r
                                    on r.team_user_id = id
                                        GROUP BY r.team_id
                            ) AS rel
                                ON rel.team_id= t.team_id
                                        WHERE u.id = :uid ORDER BY t.team_is_deleted LIMIT :limit";
                            
        return DB::run($sql, ['uid' => $uid, 'limit' => $limit])->fetchAll(); 
    }

    // Number of folders
    // Количество команд
    public static function allCount($uid)
    {
        return  DB::run("SELECT team_id FROM teams WHERE team_user_id = :uid", ['uid' => $uid])->rowCount();
    }

    // Creation of a team
    // Добавление команды
    public static function create($params)
    {
        $sql    = "INSERT INTO teams (team_name, team_content, team_user_id, team_type) 
                        VALUES (:team_name, :team_content, :team_user_id, :team_type)";

        return DB::run($sql, $params);
    }

    // Editing a team
    // Изменим команду
    public static function edit($params)
    {
        $sql_two = "UPDATE teams 
                        SET team_name       = :team_name, 
                            team_content    = :team_content, 
                            team_type       = :team_type, 
                            team_modified   = :team_modified 
                                WHERE team_id   = :team_id";

        return DB::run($sql_two, $params);
    }

    // Add, change users in the team
    // Добавим, изменим пользователей в команде
    public static function editUsersRelation($rows, $team_id)
    {
        self::deleteUsersRelation($team_id);

        foreach ($rows as $row) {
            $user_id   = $row['id'];
            $sql        = "INSERT INTO teams_users_relation (team_id, team_user_id) 
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
                        LEFT JOIN users u ON t.team_user_id = u.id
                            WHERE t.team_id = :team_id";

        return DB::run($sql, ['team_id' => $team_id])->fetchAll();
    }
}
