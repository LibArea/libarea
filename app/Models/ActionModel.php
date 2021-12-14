<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;
use Base;

class ActionModel extends MainModel
{
    public static function addAudit($audit_type, $audit_user_id, $audit_content_id)
    {
        $params = [
            'audit_type'        => $audit_type,
            'audit_user_id'     => $audit_user_id,
            'audit_content_id'  => $audit_content_id,
        ];

        $sql = "INSERT INTO audits(audit_type, audit_user_id, audit_content_id, audit_read_flag) 
                    VALUES(:audit_type, :audit_user_id, :audit_content_id, 0)";

        return DB::run($sql, $params);
    }

    // Получим информацию по контенту в зависимости от типа
    public static function getInfoTypeContent($type_id, $type)
    {
        $sql = "select * from " . $type . "s where " . $type . "_id = " . $type_id . "";

        return DB::run($sql)->fetch(PDO::FETCH_ASSOC);
    }

    // Удаление / восстановление контента
    public static function setDeletingAndRestoring($type, $type_id, $status)
    {
        if ($status == 1) {
            $sql = "UPDATE " . $type . "s SET " . $type . "_is_deleted = 0 where " . $type . "_id = :type_id";
        } else {
            $sql = "UPDATE " . $type . "s SET " . $type . "_is_deleted = 1 where " . $type . "_id = :type_id";
        }

        DB::run($sql, ['type_id' => $type_id]);
    }

    // Рекомендованно
    public static function setRecommend($post_id, $status)
    {
        if ($status == 1) {
            $sql = "UPDATE posts SET post_is_recommend = 0 where post_id = :post_id";
        } else {
            $sql = "UPDATE posts SET post_is_recommend = 1 where post_id = :post_id";
        }

        DB::run($sql, ['post_id' => $post_id]);
    }

    public static function getModerations()
    {
        $sql = "SELECT 
                    mod_id,
                    mod_post_id,
                    mod_moderates_user_id,
                    mod_created_at,
                    mod_action,
                    user_id,
                    user_login,
                    user_avatar,
                    post_id,
                    post_title,
                    post_slug,
                    post_type
                        FROM moderations
                        LEFT JOIN users ON user_id = mod_moderates_user_id
                        LEFT JOIN posts ON post_id = mod_post_id
                        ORDER BY mod_id DESC LIMIT 25";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function moderationsAdd($data)
    {
        $params = [
            'mod_moderates_user_id' => $data['user_id'],
            'mod_moderates_user_tl' => $data['user_tl'],
            'mod_created_at'        => $data['created_at'],
            'mod_post_id'           => $data['post_id'],
            'mod_content_id'        => $data['content_id'],
            'mod_action'            => $data['action'],
            'mod_reason'            => $data['reason'],
        ];

        $sql = "INSERT INTO moderations(mod_moderates_user_id, 
                        mod_moderates_user_tl, 
                        mod_created_at, 
                        mod_post_id, 
                        mod_content_id, 
                        mod_action, 
                        mod_reason) 
                            VALUES(:mod_moderates_user_id, 
                                :mod_moderates_user_tl, 
                                :mod_created_at, 
                                :mod_post_id, 
                                :mod_content_id, 
                                :mod_action, 
                                :mod_reason)";

        return DB::run($sql, $params);
    }

    // Поиск контента для форм
    public static function getSearch($search, $type)
    {
        $field_id   = $type . '_id';
        if ($type == 'post') {
            $field_tl = 'post_tl';
            $field_name = 'post_title';
            $sql = "SELECT post_id, post_title, post_is_deleted, post_tl FROM posts WHERE post_title LIKE :post_title AND post_is_deleted = 0 AND post_tl = 0 ORDER BY post_id DESC LIMIT 200";
        } elseif ($type == 'user') {
            $field_tl = 'user_trust_level';
            $field_name = 'user_login';
            $sql = "SELECT user_id, user_login, user_trust_level, user_activated FROM users WHERE user_activated = 1 AND user_login LIKE :user_login";
        } else {
            $uid    = Base::getUid();
            $id     = $uid['user_id'];

            $condition = '';
            if ($uid['user_trust_level'] != 5) {
                if ($type == 'blog') {
                    $condition = 'AND facet_user_id = ' . $id;
                }
            }

            $field_id = 'facet_id';
            $field_tl = 'facet_tl';
            $field_name = 'facet_title';
            $sql = "SELECT facet_id, facet_title, facet_tl, facet_type FROM facets 
                    WHERE facet_title LIKE :facet_title AND facet_type = '$type' $condition ORDER BY facet_count DESC LIMIT 200";
        }

        $result = DB::run($sql, [$field_name => "%" . $search . "%"]);
        $lists  = $result->fetchall(PDO::FETCH_ASSOC);

        $response = [];
        foreach ($lists as $list) {
            $response[] = array(
                "id"    => $list[$field_id],
                "value" => $list[$field_name],
                "tl"    => $list[$field_tl]
            );
        }

        return json_encode($response);
    }

    // Режим заморозки
    public static function addLimitingMode($user_id)
    {
        $sql = "UPDATE users SET user_limiting_mode = 1 where user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id]);
    }

    public static function deleteLimitingMode($user_id)
    {
        $sql = "UPDATE users SET user_limiting_mode = 0 where user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id]);
    }

    // Общий вклад
    public static function ceneralContributionCount($user_id)
    {
        $sql = "SELECT
                (SELECT COUNT(*) FROM 
                    posts WHERE post_user_id = :user_id and post_is_deleted = 0) AS t1Count,
                (SELECT COUNT(*) FROM 
                    answers WHERE answer_user_id = :user_id and answer_is_deleted = 0) AS t2Count,
                (SELECT COUNT(*) FROM 
                    comments WHERE comment_user_id = :user_id and comment_is_deleted = 0) AS t3Count";

        $result = DB::run($sql, ['user_id' => $user_id]);
        $lists  = $result->fetch(PDO::FETCH_ASSOC);

        return $lists['t1Count'] + $lists['t2Count'] + $lists['t3Count'];
    }
}
