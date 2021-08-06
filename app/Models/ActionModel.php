<?php

namespace App\Models;

use DB;
use PDO;

class ActionModel extends \MainModel
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

    public static function getModerations()
    {
        $sql = "SELECT 
                    mod_id,
                    mod_post_id,
                    mod_moderates_user_id,
                    mod_created_at,
                    mod_action,
                    id,
                    login,
                    avatar,
                    post_id,
                    post_title,
                    post_slug,
                    post_type
                        FROM moderations
                        LEFT JOIN users ON id = mod_moderates_user_id
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
        $field_id = $type . '_id';
        if ($type == 'post') {
            $field_name = 'post_title';
            $sql = "SELECT post_id, post_title, post_is_deleted, post_tl FROM posts WHERE post_title LIKE :post_title AND post_is_deleted = 0 AND post_tl = 0 ORDER BY post_id LIMIT 8";
        } elseif ($type == 'topic') {
            $field_name = 'topic_title';
            $sql = "SELECT topic_id, topic_title FROM topics 
                    WHERE topic_title LIKE :topic_title ORDER BY topic_id LIMIT 8";
        } elseif ($type == 'main') {
            $field_id = 'topic_id';
            $field_name = 'topic_title';
            $sql = "SELECT topic_id, topic_title FROM topics 
                    WHERE topic_is_parent !=0 AND topic_title LIKE :topic_title ORDER BY topic_id LIMIT 8";
        } else {
            $field_id = 'id';
            $field_name = 'login';
            $sql = "SELECT id, login FROM users WHERE login LIKE :login";
        }

        $result = DB::run($sql, [$field_name => "%" . $search . "%"]);
        $lists  = $result->fetchall(PDO::FETCH_ASSOC);

        $response = array();
        foreach ($lists as $list) {
            $response[] = array(
                "id" => $list[$field_id],
                "text" => $list[$field_name]
            );
        }

        return json_encode($response);
    }

    // Режим заморозки
    public static function addLimitingMode($user_id)
    {
        $sql = "UPDATE users SET limiting_mode = 1 where id = :user_id";

        return DB::run($sql, ['user_id' => $user_id]);
    }

    public static function deleteLimitingMode($user_id)
    {
        $sql = "UPDATE users SET limiting_mode = 0 where id = :user_id";

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
