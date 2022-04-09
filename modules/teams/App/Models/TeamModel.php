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
                    t.is_deleted,
                    u.id,
                    u.login,
                    u.avatar
                        FROM teams t
                        LEFT JOIN users u ON t.user_id = u.id
                            WHERE t.id = :id";

        return  DB::run($sql, ['id' => $id])->fetch();
    }

    // All participant teams
    // Все команды участника
    public static function all($uid)
    {
        $sql = "SELECT id, name, content, user_id, is_deleted FROM teams WHERE user_id = :uid";

        return  DB::run($sql, ['uid' => $uid])->fetchAll();
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
        $sql    = "INSERT INTO teams (name, content, user_id) VALUES (:name, :content, :user_id)";

        return DB::run($sql, $params);
    }

    // Editing a team
    // Изменим команду
    public static function edit($params)
    {
        $sql_two = "UPDATE teams SET name = :name, content = :content WHERE id = :id";

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
}
