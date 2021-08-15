<?php

namespace Modules\Admin\Models;

use DB;
use PDO;

class SpaceModel extends \MainModel
{
    // Пространства открытые / забаненные
    public static function getSpaces($page, $limit, $sort)
    {
        $signet = "space_is_delete = 0";
        if ($sort == 'ban') {
            $signet = "space_is_delete = 1";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                space_id, 
                space_name, 
                space_description,
                space_slug, 
                space_img,
                space_date,
                space_type,
                space_user_id,
                space_is_delete,
                user_id,
                user_login,
                user_avatar
                    FROM spaces
                    LEFT JOIN users ON user_id = space_user_id
                    WHERE $signet
                    ORDER BY space_id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество
    public static function getSpacesCount($sort)
    {
        $signet = "space_is_delete = 0";
        if ($sort == 'ban') {
            $signet = "space_is_delete = 1";
        }

        $sql = "SELECT space_id, space_is_delete FROM spaces WHERE $signet";

        return DB::run($sql)->rowCount();
    }

    // Удалено пространство или нет
    public static function isTheSpaceDeleted($space_id)
    {
        $sql = "SELECT space_id, space_is_delete FROM spaces WHERE space_id = :space_id";

        $result = DB::run($sql, ['space_id' => $space_id])->fetch(PDO::FETCH_ASSOC);

        return $result['space_is_delete'];
    }

    // Удаление, восстановление пространства
    public static  function SpaceDelete($space_id)
    {
        if (self::isTheSpaceDeleted($space_id) == 1) {

            $sql = "UPDATE spaces
                            SET space_is_delete = 0
                                WHERE space_id = :space_id";
        } else {
            $sql = "UPDATE spaces
                            SET space_is_delete = 1
                                WHERE space_id = :space_id";
        }

        return DB::run($sql, ['space_id' => $space_id]);
    }
}
