<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use App\Middleware\Before\UserData;
use DB;
use PDO;

class ActionModel extends MainModel
{
    public static function addAudit($audit_type, $audit_user_id, $audit_content_id, $url)
    {
        $params = [
            'audit_type'        => $audit_type,
            'audit_user_id'     => $audit_user_id,
            'audit_content_id'  => $audit_content_id,
        ];

        $sql = "INSERT INTO audits(audit_type, audit_user_id, audit_content_id, audit_read_flag) 
                    VALUES(:audit_type, :audit_user_id, :audit_content_id, 0)";

        DB::run($sql, $params);

        // Send notification type 15 (audit) to administrator (id 1) 
        // Отправим тип уведомления 15 (аудит) администратору (id 1)
        (new \App\Models\NotificationsModel())->send(
            [
                'sender_id'         => $audit_user_id,
                'recipient_id'      => 1,  // admin
                'action_type'       => 15, // audit 
                'connection_type'   => $audit_content_id,
                'content_url'       => $url,
            ]

        );

        return true;
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

    // Поиск контента для форм
    public static function getSearch($search, $type)
    {
        $uid = UserData::getUid();
        $field_id   = $type . '_id';
        if ($type == 'post') {
            $field_tl = 'post_tl';
            $field_name = 'post_title';
            $sql = "SELECT post_id, post_title, post_tl FROM posts WHERE post_title LIKE :post_title AND post_is_deleted = 0 AND post_tl = 0 ORDER BY post_id DESC LIMIT 100";
        } elseif ($type == 'user') {
            $field_tl = 'user_trust_level';
            $field_name = 'user_login';
            $sql = "SELECT user_id, user_login, user_trust_level, user_activated FROM users WHERE user_activated = 1 AND user_login LIKE :user_login";
        } elseif ($type == 'section') {
            $field_id = 'facet_id';
            $field_tl = 'facet_tl';
            $field_name = 'facet_title';
            $condition = 'AND facet_user_id = ' . $uid['user_id'];
            $sql = "SELECT facet_id, facet_title, facet_tl, facet_type FROM facets 
                    WHERE facet_title LIKE :facet_title AND facet_type = 'section' $condition ORDER BY facet_count DESC LIMIT 100";
        } else {
            $condition = '';
            if ($uid['user_trust_level'] != UserData::REGISTERED_ADMIN) {
                if ($type == 'blog') {
                    $condition = 'AND facet_user_id = ' . $uid['user_id'];
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

    // Get the logs
    // Получим логи  
    public static function getLogs($page, $limit)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    log_id,
                    log_user_id,
                    log_user_login,
                    log_id_content,
                    log_type_content,
                    log_action_name,
                    log_url_content,
                    log_date
                        FROM users_action_logs ORDER BY log_id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get gthe number of records 
    // Получим количество записей  
    public static function getLogsCount()
    {
        return DB::run("SELECT log_id FROM users_action_logs")->rowCount();
    }

    // Let's write the logs
    // Запишем логи   
    public static function addLogs($data)
    {
        $params = [
            'log_user_id'       => $data['user_id'],
            'log_user_login'    => $data['user_login'],
            'log_id_content'    => $data['log_id_content'],
            'log_type_content'  => $data['log_type_content'],
            'log_action_name'   => $data['log_action_name'],
            'log_url_content'   => $data['log_url_content'],
            'log_date'          => date("Y-m-d H:i:s"),
        ];

        $sql = "INSERT INTO users_action_logs(log_user_id, 
                        log_user_login, 
                        log_id_content, 
                        log_type_content, 
                        log_action_name, 
                        log_url_content,
                        log_date) 
                            VALUES(:log_user_id, 
                                :log_user_login, 
                                :log_id_content, 
                                :log_type_content, 
                                :log_action_name, 
                                :log_url_content,
                                :log_date)";

        return DB::run($sql, $params);
    }
}
