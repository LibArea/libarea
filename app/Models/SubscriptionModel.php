<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class SubscriptionModel extends MainModel
{

    // Подписан ли участник
    public static function getFocus($content_id, $user_id, $type)
    {
        // $type = post / space / topic
        $sql = "SELECT signed_" . $type . "_id, signed_user_id FROM " . $type . "s_signed 
                    WHERE signed_" . $type . "_id = :content_id AND signed_user_id = :user_id";

        return DB::run($sql, ['content_id' => $content_id, 'user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Подписка / отписка
    public static function focus($content_id, $user_id, $type)
    {
        $result  = self::getFocus($content_id, $user_id, $type);

        if (is_array($result)) {

            $sql = "DELETE FROM " . $type . "s_signed WHERE signed_" . $type . "_id =  :content_id AND signed_user_id = :user_id";
            DB::run($sql, ['content_id' => $content_id, 'user_id' => $user_id]);

            $sql_two = "UPDATE " . $type . "s SET " . $type . "_focus_count = (" . $type . "_focus_count - 1) WHERE " . $type . "_id = :content_id";

            return DB::run($sql_two, ['content_id' => $content_id]);
        }

        $params = [
            'content_id' => $content_id,
            'user_id'    => $user_id,
        ];

        $sql = "INSERT INTO " . $type . "s_signed(signed_" . $type . "_id, signed_user_id) 
                       VALUES(:content_id, :user_id)";

        DB::run($sql, $params);

        $sql_two = "UPDATE " . $type . "s SET " . $type . "_focus_count = (" . $type . "_focus_count + 1) WHERE " . $type . "_id = :content_id";

        return DB::run($sql_two, ['content_id' => $content_id]);
    }
}
