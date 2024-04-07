<?php

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class ActionModel extends Model
{
    // Get information on the content depending on the type
    // Получим информацию по контенту в зависимости от типа
    public static function getInfoTypeContent($type_id, $type)
    {
        $sql = "select * from " . $type . "s WHERE " . $type . "_id = " . $type_id . "";

        return DB::run($sql)->fetch();
    }

    // Deleting / restoring content
    // Удаление / восстановление контента
    public static function setDeletingAndRestoring($type, $type_id, $status)
    {
        $value = $status == 1 ? 0 : 1;

        $sql = "UPDATE " . $type . "s SET " . $type . "_is_deleted = $value WHERE " . $type . "_id = :type_id";

        DB::run($sql, ['type_id' => $type_id]);

        self::recalculate($type, $type_id, $status);
    }

    // Recalculate the number of replies and comments in a post
    // Пересчитываем количество ответов и комментариев в посту
    public static function recalculate($type, $type_id, $status)
    {
        if (!in_array($type, ['comment', 'answer'])) {
            return false;
        }

        $post  = DB::run("SELECT  " . $type . "_post_id  FROM " . $type . "s WHERE " . $type . "_id = :type_id", ['type_id' => $type_id])->fetch();

        $action = $status == 1 ? "+1" : "-1";

        $sql = "UPDATE posts SET post_" . $type . "s_count = (post_" . $type . "s_count " . $action . ") WHERE post_id = :type_id";

        DB::run($sql, ['type_id' => $post[$type . '_post_id']]);
    }

    public static function setRecommend($post_id, $status)
    {
        $value = $status == 1 ? 0 : 1;

        $sql = "UPDATE posts SET post_is_recommend = $value WHERE post_id = :post_id";

        DB::run($sql, ['post_id' => $post_id]);
    }

    // Freeze Mode
    // Режим заморозки
    public static function addLimitingMode($user_id)
    {
        $sql = "UPDATE users SET limiting_mode = 1 WHERE id = :user_id";

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
                    comments WHERE comment_user_id = $user_id and comment_is_deleted = 0) AS t2Count";

        $lists  = DB::run($sql)->fetch();

        return $lists['t1Count'] + $lists['t2Count'];
    }

    // Member Content Posting Frequency 
    // Частота размещения контента участника в день 
    public static function getSpeedDay($type)
    {
        $sql = "SELECT 
                    " . $type . "_id, 
                    " . $type . "_user_id, 
                    " . $type . "_date
                    FROM " . $type . "s 
                        WHERE " . $type . "_user_id = :user_id
                        AND " . $type . "_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['user_id' => self::container()->user()->id()])->rowCount();
    }

    // Let's write the logs
    // Запишем логи   
    public static function addLogs($data)
    {
        $params = [
            'user_id'       => self::container()->user()->id(),
            'user_login'    => self::container()->user()->login(),
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
