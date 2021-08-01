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
                id,
                login,
                avatar
                    FROM space  
                    LEFT JOIN users ON id = space_user_id
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

        $sql = "SELECT space_id, space_is_delete FROM space WHERE $signet";

        return DB::run($sql)->rowCount();
    }
}
