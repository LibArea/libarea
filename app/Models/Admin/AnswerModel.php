<?php

namespace App\Models\Admin;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class AnswerModel extends MainModel
{
    // Все ответы
    public static function getAnswersAll($page, $limit, $sheet)
    {
        $sort = "WHERE answer_is_deleted = 0";
        if ($sheet == 'ban') {
            $sort = "WHERE answer_is_deleted = 1";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_type,
                    answer_id,
                    answer_content,
                    answer_date,
                    answer_user_id,
                    answer_post_id,
                    answer_ip,
                    answer_votes,
                    answer_is_deleted,
                    user_id, 
                    user_login, 
                    user_avatar
                        FROM answers
                        INNER JOIN users ON user_id = answer_user_id
                        INNER JOIN posts ON answer_post_id = post_id
                        $sort
                        ORDER BY answer_id DESC LIMIT $start, $limit ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество ответов
    public static function getAnswersAllCount($sheet)
    {
        $sort = "WHERE answer_is_deleted = 0";
        if ($sheet == 'ban') {
            $sort = "WHERE answer_is_deleted = 1";
        }

        $sql = "SELECT answer_id, answer_is_deleted FROM answers $sort";

        return DB::run($sql)->rowCount();
    }
}
