<?php

namespace App\Models;

use UserData;
use DB;

class ActionModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Get information on the content depending on the type
    // Получим информацию по контенту в зависимости от типа
    public static function getInfoTypeContent($type_id, $type)
    {
        $sql = "select * from " . $type . "s where " . $type . "_id = " . $type_id . "";

        return DB::run($sql)->fetch();
    }

    // Deleting / restoring content
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

    public static function setRecommend($post_id, $status)
    {
        if ($status == 1) {
            $sql = "UPDATE posts SET post_is_recommend = 0 where post_id = :post_id";
        } else {
            $sql = "UPDATE posts SET post_is_recommend = 1 where post_id = :post_id";
        }

        DB::run($sql, ['post_id' => $post_id]);
    }

    // Find content for forms
    // Поиск контента для форм
    public static function getSearch($search, $type)
    {
        $field_id   = $type . '_id';
        if ($type == 'post') {
            $field_name = 'post_title';
            $sql = "SELECT post_id, post_title FROM posts WHERE post_title LIKE :post_title AND post_is_deleted = 0 AND post_tl = 0 AND post_type = 'post' ORDER BY post_id DESC LIMIT 100";
        } elseif ($type == 'user') {
            $field_id = 'id';
            $field_name = 'login';
            $sql = "SELECT id, login, trust_level, activated FROM users WHERE activated = 1 AND login LIKE :login";
        } else {
            $condition = '';
            if (!UserData::checkAdmin()) {
                if ($type == 'blog') {
                    $condition = 'AND facet_user_id = ' . UserData::getUserId();
                }
            }

            $field_id = 'facet_id';
            $field_name = 'facet_title';
            $sql = "SELECT facet_id, facet_title, facet_type FROM facets 
                    WHERE facet_title LIKE :facet_title AND facet_type = '$type' $condition ORDER BY facet_count DESC LIMIT 200";
        }

        $result = DB::run($sql, [$field_name => "%" . $search . "%"]);
        $lists  = $result->fetchAll();

        $response = [];
        foreach ($lists as $list) {
            $response[] = array(
                "id"    => $list[$field_id],
                "value" => $list[$field_name],
            );
        }

        return json_encode($response);
    }

    // Freeze Mode
    // Режим заморозки
    public static function addLimitingMode($user_id)
    {
        $sql = "UPDATE users SET limiting_mode = 1 where id = :user_id";

        return DB::run($sql, ['user_id' => $user_id]);
    }

    // All member content (contribution)
    // Весь контент участника (вклад)
    public static function allContentUserCount($user_id)
    {
        $sql = "SELECT
                (SELECT COUNT(*) FROM 
                    posts WHERE post_user_id = $user_id and post_is_deleted = 0) AS t1Count,
                (SELECT COUNT(*) FROM 
                    answers WHERE answer_user_id = $user_id and answer_is_deleted = 0) AS t2Count,
                (SELECT COUNT(*) FROM 
                    comments WHERE comment_user_id = $user_id and comment_is_deleted = 0) AS t3Count";

        $lists  = DB::run($sql)->fetch();

        return $lists['t1Count'] + $lists['t2Count'] + $lists['t3Count'];
    }

    // Member Content Posting Frequency 
    // Частота размещения контента участника в день 
    public static function getSpeedDay($user_id, $type)
    {
        $sql = "SELECT 
                    " . $type . "_id, 
                    " . $type . "_user_id, 
                    " . $type . "_date
                    FROM " . $type . "s 
                        WHERE " . $type . "_user_id = :user_id
                        AND " . $type . "_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['user_id' => $user_id])->rowCount();
    }

    public static function deleteLimitingMode($user_id)
    {
        $sql = "UPDATE users SET limiting_mode = 0 where id = :user_id";

        return DB::run($sql, ['user_id' => $user_id]);
    }

    // Let's write the logs
    // Запишем логи   
    public static function addLogs($data)
    {

        $params = [
            'user_id'       => UserData::getUserId(),
            'user_login'    => UserData::getUserLogin(),
            'id_content'    => $data['id_content'],
            'action_type'   => $data['action_type'],
            'action_name'   => $data['action_name'],
            'url_content'   => $data['url_content']
        ];

        $sql = "INSERT INTO users_action_logs(user_id, 
                        user_login, 
                        id_content, 
                        action_type, 
                        action_name, 
                        url_content) 
                            VALUES(:user_id, 
                                :user_login, 
                                :id_content, 
                                :action_type, 
                                :action_name, 
                                :url_content)";

        return DB::run($sql, $params);
    }
}
