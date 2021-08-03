<?php

namespace Modules\Admin\Models;

use DB;
use PDO;

class BadgeModel extends \MainModel
{
    // Все награды
    public static function getBadgesAll()
    {
        $sql = "SELECT 
                    badge_id,
                    badge_icon,
                    badge_tl,
                    badge_score,
                    badge_title,
                    badge_description
                        FROM badges";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Получим информацию по награде
    public static function getBadgeId($badge_id)
    {
        $sql = "SELECT 
                    badge_id,
                    badge_icon,
                    badge_tl,
                    badge_score,
                    badge_title,
                    badge_description
                        FROM badges 
                        WHERE badge_id = :badge_id";

        return DB::run($sql, ['badge_id' => $badge_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Редактирование награды
    public static function edit($data)
    {
        $params = [
            'badge_title'       => $data['badge_title'],
            'badge_description' => $data['badge_description'],
            'badge_icon'        => $data['badge_icon'],
            'badge_id'          => $data['badge_id'],
        ];

        $sql = "UPDATE badges 
                    SET badge_title = :badge_title,  
                    badge_description = :badge_description, 
                    badge_icon = :badge_icon 
                        WHERE badge_id = :badge_id";

        return  DB::run($sql, $params);
    }

    // Добавить награды
    public static function add($data)
    {
        $params = [
            'badge_tl'          => $data['badge_tl'],
            'badge_score'       => $data['badge_score'],
            'badge_title'       => $data['badge_title'],
            'badge_description' => $data['badge_description'],
            'badge_icon'        => $data['badge_icon'],
        ];

        $sql = "INSERT INTO badges(badge_tl, 
                        badge_score, 
                        badge_title, 
                        badge_description, 
                        badge_icon) 
                            VALUES(:badge_tl, 
                                :badge_score, 
                                :badge_title, 
                                :badge_description, 
                                :badge_icon)";

        return DB::run($sql, $params);
    }

    // Наградить участника
    public static function badgeUserAdd($user_id, $badge_id)
    {
        $params = [
            'user_id'   => $user_id,
            'badge_id'  => $badge_id,
        ];

        $sql = "INSERT INTO badges_user(bu_user_id, bu_badge_id) 
                    VALUES(:user_id, :badge_id)";

        return DB::run($sql, $params);
    }

    // Все награды участника
    public static function getBadgeUserAll($user_id)
    {

        $sql = "SELECT 
                    bu_badge_id,
                    bu_user_id,
                    badge_id,
                    badge_tl,
                    badge_score,
                    badge_title,
                    badge_icon,
                    badge_description
                        FROM badges_user
                        LEFT JOIN badges ON badge_id = bu_badge_id
                        WHERE bu_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }
}
