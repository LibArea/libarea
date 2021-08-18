<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class AnswerModel extends MainModel
{
    // Добавляем ответ
    public static function addAnswer($data)
    {
        $params = [
            'answer_post_id'    => $data['answer_post_id'],
            'answer_content'    => $data['answer_content'],
            'answer_published'  => $data['answer_published'],
            'answer_ip'         => $data['answer_ip'],
            'answer_user_id'    => $data['answer_user_id'],
        ];

        $sql = "INSERT INTO answers(answer_post_id, answer_content, answer_published, answer_ip, answer_user_id) 
                       VALUES(:answer_post_id, :answer_content, :answer_published, :answer_ip, :answer_user_id)";

        DB::run($sql, $params);

        $sql_last_id =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch(PDO::FETCH_ASSOC);

        return $sql_last_id['last_id'];
    }

    // Все ответы
    public static function getAnswersAll($page, $limit, $uid)
    {
        $tl = 'AND post_tl = 0';
        if ($uid['user_trust_level']) {
            $tl = 'AND post_tl <= ' . $uid['user_trust_level'] . '';
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    answer_id,
                    answer_content,
                    answer_date,
                    answer_user_id,
                    answer_post_id,
                    answer_votes,
                    answer_is_deleted,
                    user_id, 
                    user_login, 
                    user_avatar
                        FROM answers
                        INNER JOIN users ON user_id = answer_user_id
                        INNER JOIN posts ON answer_post_id = post_id AND answer_is_deleted = 0 " . $tl . "
                        ORDER BY answer_id DESC LIMIT $start, $limit ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество ответов
    public static function getAnswersAllCount()
    {
        $sql = "SELECT 
                    answer_id, 
                    answer_is_deleted 
                        FROM answers WHERE answer_is_deleted = 0";

        return DB::run($sql)->rowCount();
    }

    // Получаем лучший комментарий (LO)
    public static function getAnswerLo($post_id)
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
                            WHERE answer_post_id = :post_id AND answer_lo > 0";

        return  DB::run($sql, ['post_id' => $post_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Получаем ответы в посте
    public static function getAnswersPost($post_id, $user_id, $type)
    {
        $sort = "";
        if ($type == 1) {
            $sort = 'ORDER BY answer_votes DESC ';
        }

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
                    favorite_tid,
                    favorite_user_id,
                    favorite_type,
                    user_id, 
                    user_login, 
                    user_avatar
                        FROM answers
                        LEFT JOIN users ON user_id = answer_user_id
                        LEFT JOIN votes_answer ON votes_answer_item_id = answer_id
                            AND votes_answer_user_id = $user_id
                        LEFT JOIN favorites ON favorite_tid = answer_id
                            AND favorite_user_id  = $user_id
                            AND favorite_type = 2
                            WHERE answer_post_id = $post_id
                            $sort ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Страница ответов участника
    public static function userAnswers($slug)
    {
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
                    post_id,
                    post_title,
                    post_slug,
                    user_id, 
                    user_login, 
                    user_avatar
                        FROM answers
                        LEFT JOIN users ON user_id = answer_user_id
                        LEFT JOIN posts ON answer_post_id = post_id
                        WHERE user_login = :slug
                        AND answer_is_deleted = 0 AND post_tl = 0 AND post_tl = 0
                        ORDER BY answer_id DESC";

        return DB::run($sql, ['slug' => $slug])->fetchAll(PDO::FETCH_ASSOC);
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

    // Редактируем ответ
    public static function AnswerEdit($answer_id, $content)
    {
        $sql_two = "UPDATE answers SET answer_content = :content, answer_modified = :date WHERE answer_id = :answer_id";

        return DB::run($sql_two, ['answer_id' => $answer_id, 'content' => $content, 'date' => date("Y-m-d H:i:s")]);
    }

    // Частота размещения ответа участника 
    public static function getAnswerSpeed($user_id)
    {
        $sql = "SELECT 
                    answer_id, 
                    answer_user_id, 
                    answer_date
                        fROM answers 
                            WHERE answer_user_id = :user_id
                            AND answer_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['user_id' => $user_id])->rowCount();
    }
}
