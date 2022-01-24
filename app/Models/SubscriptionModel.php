<?php

namespace App\Models;

use DB;

class SubscriptionModel extends \Hleb\Scheme\App\Models\MainModel
{
    // $type: post | facet
    public static function getFocus($content_id, $user_id, $type)
    {
        if ($type == 'post') {
            $sql = "SELECT signed_post_id, signed_user_id FROM posts_signed 
                    WHERE signed_post_id = :content_id AND signed_user_id = :user_id";
        } else {
            $sql = "SELECT signed_facet_id, signed_user_id FROM facets_signed 
                    WHERE signed_facet_id = :content_id AND signed_user_id = :user_id";
        }

        return DB::run($sql, ['content_id' => $content_id, 'user_id' => $user_id])->fetch();
    }

    public static function focus($content_id, $user_id, $type)
    {
        $result  = self::getFocus($content_id, $user_id, $type);

        if (is_array($result)) {

            self::removalFocus($content_id, $user_id, $type);

            return self::updateFocus($content_id, $type);
        }

        self::insertFocus($content_id, $user_id, $type);

        if ($type == 'post') {
            $sql = "UPDATE posts SET post_focus_count = (post_focus_count + 1) WHERE post_id = :content_id";
        } else {
            $sql = "UPDATE facets SET facet_focus_count = (facet_focus_count + 1) WHERE facet_id = :content_id";
        }

        return DB::run($sql, ['content_id' => $content_id]);
    }

    public static function removalFocus($content_id, $user_id, $type)
    {
        if ($type == 'post') {
            $sql = "DELETE FROM posts_signed WHERE signed_post_id = :content_id AND signed_user_id = :user_id";
        } else {
            $sql = "DELETE FROM facets_signed WHERE signed_facet_id = :content_id AND signed_user_id = :user_id";
        }

        DB::run($sql, ['content_id' => $content_id, 'user_id' => $user_id]);
    }

    public static function updateFocus($content_id, $type)
    {
        if ($type == 'post') {
            $sql = "UPDATE posts SET post_focus_count = (post_focus_count - 1) WHERE post_id = :content_id";
        } else {
            $sql = "UPDATE facets SET facet_focus_count = (facet_focus_count - 1) WHERE facet_id = :content_id";
        }

        DB::run($sql, ['content_id' => $content_id]);
    }

    public static function insertFocus($content_id, $user_id, $type)
    {
        if ($type == 'post') {
            $sql = "INSERT INTO posts_signed(signed_post_id, signed_user_id) VALUES(:content_id, :user_id)";
        } else {
            $sql = "INSERT INTO facets_signed(signed_facet_id, signed_user_id) VALUES(:content_id, :user_id)";
        }

        DB::run($sql, ['content_id' => $content_id, 'user_id' => $user_id]);
    }
}
