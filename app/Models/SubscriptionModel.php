<?php

namespace App\Models;

use DB;
use UserData;

class SubscriptionModel extends \Hleb\Scheme\App\Models\MainModel
{
    // $type: post | facet
    public static function getFocus($content_id, $type)
    {
        $sql = "SELECT signed_" . $type . "_id, signed_user_id FROM " . $type . "s_signed 
                    WHERE signed_" . $type . "_id = :content_id AND signed_user_id = :user_id";

        return DB::run($sql, ['content_id' => $content_id, 'user_id' => UserData::getUserId()])->fetch();
    }

    public static function focus($content_id, $type)
    {
        $result  = self::getFocus($content_id, $type);

        if (is_array($result)) {

            self::removalFocus($content_id, $type);

            return self::updateFocus($content_id, $type);
        }

        self::insertFocus($content_id, $type);

        $sql = "UPDATE " . $type . "s SET " . $type . "_focus_count = (" . $type . "_focus_count + 1) WHERE " . $type . "_id = :content_id";

        return DB::run($sql, ['content_id' => $content_id]);
    }

    public static function removalFocus($content_id, $type)
    {
        $sql = "DELETE FROM " . $type . "s_signed WHERE signed_" . $type . "_id = :content_id AND signed_user_id = :user_id";

        DB::run($sql, ['content_id' => $content_id, 'user_id' => UserData::getUserId()]);
    }

    public static function updateFocus($content_id, $type)
    {
        $sql = "UPDATE " . $type . "s SET " . $type . "_focus_count = (" . $type . "_focus_count - 1) WHERE " . $type . "_id = :content_id";

        DB::run($sql, ['content_id' => $content_id]);
    }

    public static function insertFocus($content_id, $type)
    {
        $sql = "INSERT INTO " . $type . "s_signed(signed_" . $type . "_id, signed_user_id) VALUES(:content_id, :user_id)";

        DB::run($sql, ['content_id' => $content_id, 'user_id' => UserData::getUserId()]);
    }
}
