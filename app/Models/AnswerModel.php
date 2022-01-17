<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class AnswerModel extends MainModel
{
    // Add an answer
    // Добавим ответ
    public static function add($params)
    {
        $sql = "INSERT INTO answers(answer_post_id, 
                    answer_content, 
                    answer_published, 
                    answer_ip, 
                    answer_user_id) 
                       VALUES(:answer_post_id, 
                           :answer_content, 
                           :answer_published, 
                           :answer_ip, 
                           :answer_user_id)";

        DB::run($sql, $params);

        $sql_last_id =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch(PDO::FETCH_ASSOC);

        return $sql_last_id['last_id'];
    }

    // Editing the answer
    // Редактируем ответ
    public static function edit($params)
    {
        $sql_two = "UPDATE answers SET answer_content = :answer_content, 
                        answer_modified = :answer_modified 
                            WHERE answer_id = :answer_id";

        return DB::run($sql_two, $params);
    }

    // Все ответы
    public static function getAnswersAll($page, $limit, $user, $sheet)
    {
        if ($sheet == 'user') {
            $sort = 'WHERE answer_is_deleted = 0 AND post_tl = 0 AND post_is_deleted = 0';
            if ($user['trust_level']) {
                $sort = 'WHERE answer_is_deleted = 0 AND post_is_deleted = 0 AND post_tl <= ' . $user['trust_level'] . '';
            }
        } else {
            $sort = "WHERE answer_is_deleted = 0 AND post_is_deleted = 0";
            if ($sheet == 'answers.ban') {
                $sort = "WHERE answer_is_deleted = 1 OR post_is_deleted = 1";
            }
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_feature,
                    post_is_deleted,
                    answer_id,
                    answer_content,
                    answer_date,
                    answer_user_id,
                    answer_ip,
                    answer_post_id,
                    answer_votes,
                    answer_is_deleted,
                    votes_answer_item_id, 
                    votes_answer_user_id,
                    id, 
                    login, 
                    avatar
                        FROM answers
                        INNER JOIN users ON id = answer_user_id
                        INNER JOIN posts ON answer_post_id = post_id 
                        LEFT JOIN votes_answer ON votes_answer_item_id = answer_id
                            AND votes_answer_user_id = :uid
                        $sort
                        ORDER BY answer_id DESC LIMIT $start, $limit ";

        return DB::run($sql, ['uid' => $user['id']])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество ответов
    public static function getAnswersAllCount($sheet)
    {
        $sort = "WHERE answer_is_deleted = 0";
        if ($sheet == 'answers.ban') {
            $sort = "WHERE answer_is_deleted = 1";
        }

        $sql = "SELECT 
                    answer_id, 
                    answer_is_deleted 
                        FROM answers $sort";

        return DB::run($sql)->rowCount();
    }

    // Получаем ответы в посте
    public static function getAnswersPost($post_id, $uid, $type)
    {
        $sort = "";
        if ($type == 1) {
            $sort = 'ORDER BY answer_votes DESC ';
        }

        // TODO: Сгруппировать комментарии по ответу (избавимся N+1)
        // LEFT JOIN comments ON comment_answer_id = answer_id
        // comment_answer_id,
        // comment_user_id,
        // comment_date,
        // comment_ip,
        // comment_content,
        $sql = "SELECT 
                    answer_id,
                    answer_user_id,
                    answer_post_id,
                    answer_date,
                    answer_content,
                    answer_modified,
                    answer_published,
                    answer_ip,
                    answer_votes,
                    answer_after,
                    answer_is_deleted,
                    votes_answer_item_id, 
                    votes_answer_user_id,
                    favorite_tid,
                    favorite_user_id,
                    favorite_type,
                    id, 
                    login,
                    avatar
                        FROM answers
                        LEFT JOIN users ON id = answer_user_id
                        LEFT JOIN votes_answer ON votes_answer_item_id = answer_id
                            AND votes_answer_user_id = $uid
                        LEFT JOIN favorites ON favorite_tid = answer_id
                            AND favorite_user_id  = $uid
                            AND favorite_type = 2
                            WHERE answer_post_id = $post_id
                            $sort ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Страница ответов участника
    public static function userAnswers($page, $limit, $uid, $uid_vote)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    answer_id,
                    answer_user_id,
                    answer_post_id,
                    answer_date,
                    answer_content,
                    answer_modified,
                    answer_ip,
                    answer_votes,
                    answer_after,
                    answer_is_deleted,
                    votes_answer_item_id, 
                    votes_answer_user_id,
                    post_id,
                    post_title,
                    post_slug,
                    post_is_deleted,
                    id, 
                    login, 
                    avatar
                        FROM answers
                        LEFT JOIN users ON id = answer_user_id
                        LEFT JOIN posts ON answer_post_id = post_id
                        LEFT JOIN votes_answer ON votes_answer_item_id = answer_id
                            AND votes_answer_user_id = :uid_vote
                        WHERE answer_user_id = :uid
                        AND answer_is_deleted = 0 AND post_is_deleted = 0 AND post_tl = 0 AND post_tl = 0
                        ORDER BY answer_id DESC LIMIT $start, $limit ";

        return DB::run($sql, ['uid' => $uid, 'uid_vote' => $uid_vote])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество ответов участника
    public static function userAnswersCount($uid)
    {
        $sql = "SELECT 
                    answer_id
                        FROM answers
                        LEFT JOIN posts ON answer_post_id = post_id
                            WHERE answer_user_id = :uid AND answer_is_deleted = 0 
                                AND post_is_deleted = 0 AND post_tl = 0 AND post_tl = 0";

        return DB::run($sql, ['uid' => $uid])->rowCount();
    }

    // Информацию по id ответа
    public static function getAnswerId($answer_id)
    {
        $sql = "SELECT 
                    answer_id,
                    answer_post_id,
                    answer_user_id,
                    answer_date,
                    answer_modified,
                    answer_published,
                    answer_ip,
                    answer_order,
                    answer_after,
                    answer_votes,
                    answer_content,
                    answer_lo,
                    answer_is_deleted
                        FROM answers 
                            WHERE answer_id = :answer_id";

        return  DB::run($sql, ['answer_id' => $answer_id])->fetch(PDO::FETCH_ASSOC);
    }
}
