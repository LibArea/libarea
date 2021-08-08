<?php

namespace App\Models;

use DB;
use PDO;

class CommentModel extends \MainModel
{
    // Добавляем комментарий
    public static function addComment($data)
    {
        $params = [
            'comment_post_id'       => $data['comment_post_id'],
            'comment_answer_id'     => $data['comment_answer_id'],
            'comment_comment_id'    => $data['comment_comment_id'],
            'comment_content'       => $data['comment_content'],
            'comment_published'     => $data['comment_published'],
            'comment_ip'            => $data['comment_ip'],
            'comment_user_id'       => $data['comment_user_id'],
        ];

        $sql = "INSERT INTO comments(comment_post_id, 
                                        comment_answer_id, 
                                        comment_comment_id, 
                                        comment_content, 
                                        comment_published, 
                                        comment_ip, 
                                        comment_user_id) 
        
                                VALUES(:comment_post_id, 
                                        :comment_answer_id, 
                                        :comment_comment_id, 
                                        :comment_content, 
                                        :comment_published, 
                                        :comment_ip, 
                                        :comment_user_id)";

        DB::run($sql, $params);

        $sql_last_id    =   DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch(PDO::FETCH_ASSOC);
        $last_id        =   $sql_last_id['last_id'];

        // Отмечаем комментарий, что за ним есть ответ
        self::setThereComment($last_id, $data['comment_comment_id']);

        $sql     = "SELECT * FROM answers WHERE answer_id = :comment_answer_id";
        $answer  = DB::run($sql, ['comment_answer_id' => $data['comment_answer_id']])->fetch(PDO::FETCH_ASSOC);

        if ($answer['answer_after'] == 0) {

            self::setThereAnswer($last_id, $data['comment_answer_id']);
        }

        return $last_id;
    }

    // Отметим комментарий, что за ним есть ответ
    public static function setThereComment($last_id, $comment_id)
    {
        $sql = "UPDATE comments SET comment_after = :last_id WHERE comment_id = :comment_id";

        return DB::run($sql, ['last_id' => $last_id, 'comment_id' => $comment_id]);
    }


    // Отмечаем ответ, что за ним есть комментарии
    public static function setThereAnswer($last_id, $answer_id)
    {
        $sql = "UPDATE answers SET answer_after = :last_id WHERE answer_id = :answer_id";

        return DB::run($sql, ['last_id' => $last_id, 'answer_id' => $answer_id]);
    }

    // Все комментарии
    public static function getCommentsAll($page, $limit, $uid)
    {
        if (!$uid['trust_level']) {
            $tl = 'AND post_tl = 0';
        } else {
            $tl = 'AND post_tl <= ' . $uid['trust_level'] . '';
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT
                    post_id,
                    post_title,
                    post_slug,
                    post_tl,
                    comment_id,
                    comment_date,
                    comment_content,
                    comment_post_id,
                    comment_user_id,
                    comment_votes,
                    comment_is_deleted,
                    id, 
                    login, 
                    avatar
                        FROM comments 
                        JOIN users ON id = comment_user_id
                        JOIN posts ON comment_post_id = post_id AND comment_is_deleted = 0 " . $tl . "
                        ORDER BY comment_id DESC LIMIT $start, $limit ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество комментариев
    public static function getCommentAllCount()
    {
        $sql = "SELECT comment_id, comment_is_deleted FROM comments WHERE comment_is_deleted = 0";

        return DB::run($sql)->rowCount();
    }

    // Получаем комментарии к ответу
    public static function getComments($answer_id, $user_id)
    {
        $sql = "SELECT 
                    comment_id,
                    comment_user_id,                
                    comment_answer_id,
                    comment_comment_id,
                    comment_content,
                    comment_date,
                    comment_votes,
                    comment_ip,
                    comment_after,
                    comment_is_deleted,
                    votes_comment_item_id, 
                    votes_comment_user_id,
                    id, 
                    login, 
                    avatar
                        FROM comments 
                        LEFT JOIN users  ON id = comment_user_id
                        LEFT JOIN votes_comment  ON votes_comment_item_id = comment_id 
                        AND votes_comment_user_id = $user_id
                        WHERE comment_answer_id = " . $answer_id;

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Страница комментариев участника
    public static function userComments($slug)
    {
        $sql = "SELECT 
                    comment_id,
                    comment_user_id,                
                    comment_answer_id,
                    comment_comment_id,
                    comment_content,
                    comment_date,
                    comment_votes,
                    comment_ip,
                    comment_after,
                    comment_is_deleted,
                    post_id, 
                    post_slug,
                    post_title,
                    id, 
                    login, 
                    avatar
                        FROM comments 
                        LEFT JOIN users  ON id = comment_user_id
                        LEFT JOIN posts  ON comment_post_id = post_id 
                        WHERE login = :slug AND comment_is_deleted = 0 AND post_tl = 0
                        ORDER BY comment_id DESC LIMIT 100";

        return DB::run($sql, ['slug' => $slug])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Получаем комментарий по id комментария
    public static function getCommentsId($comment_id)
    {
        $sql = "SELECT 
                    comment_id,
                    comment_content,
                    comment_user_id,
                    comment_date,
                    comment_is_deleted
                        FROM comments WHERE comment_id = :comment_id";

        return DB::run($sql, ['comment_id' => $comment_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Редактируем комментарий
    public static function CommentEdit($comment_id, $comment)
    {
        $sql = "UPDATE comments SET 
                    comment_content     = :comment,
                    comment_modified    = :data
                         WHERE comment_id = :comment_id";

        return DB::run($sql, ['comment_id' => $comment_id, 'comment' => $comment, 'data' => date("Y-m-d H:i:s")]);
    }

    // Частота размещения комментариев участника 
    public static function getCommentSpeed($uid)
    {
        $sql = "SELECT 
                    comment_id, 
                    comment_user_id, 
                    comment_date
                    FROM comments 
                        WHERE comment_user_id = " . $uid . "
                        AND comment_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
