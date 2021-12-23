<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class CommentModel extends MainModel
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
    public static function getCommentsAll($page, $limit, $uid, $sheet)
    {
        if ($sheet == 'user') {
            if (!$uid['user_trust_level']) {
                $tl = 'AND comment_is_deleted = 0 AND post_tl = 0 AND post_is_deleted = 0';
            } else {
                $tl = 'AND comment_is_deleted = 0 AND post_is_deleted = 0 AND post_tl <= ' . $uid['user_trust_level'] . '';
            }
            $sort = '';
        } else {
            $sort = "WHERE comment_is_deleted = 0 AND post_is_deleted = 0";
            if ($sheet == 'comments.ban') {
                $sort = "WHERE comment_is_deleted = 1 OR post_is_deleted = 1";
            }
            $tl = '';
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT
                    post_id,
                    post_title,
                    post_slug,
                    post_tl,
                    post_feature,
                    post_is_deleted,
                    comment_id,
                    comment_ip,
                    comment_date,
                    comment_content,
                    comment_post_id,
                    comment_user_id,
                    comment_votes,
                    comment_is_deleted,
                    votes_comment_item_id, 
                    votes_comment_user_id,
                    user_id, 
                    user_login, 
                    user_avatar
                        FROM comments 
                        JOIN users ON user_id = comment_user_id
                        JOIN posts ON comment_post_id = post_id " . $tl . "
                        LEFT JOIN votes_comment ON votes_comment_item_id = comment_id
                            AND votes_comment_user_id = :user_id
                        $sort
                        ORDER BY comment_id DESC LIMIT $start, $limit ";

        return DB::run($sql, ['user_id' => $uid['user_id']])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество комментариев 
    public static function getCommentsAllCount($sheet)
    {
        if ($sheet == 'user') {
            $sort = "WHERE comment_is_deleted = 0";
        } else {
            $sort = "WHERE comment_is_deleted = 0";
            if ($sheet == 'comments.ban') {
                $sort = "WHERE comment_is_deleted = 1";
            }
        }

        $sql = "SELECT comment_id, comment_is_deleted FROM comments $sort";

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
                    user_id, 
                    user_login, 
                    user_avatar
                        FROM comments 
                        LEFT JOIN users  ON user_id = comment_user_id
                        LEFT JOIN votes_comment  ON votes_comment_item_id = comment_id 
                        AND votes_comment_user_id = :user_id
                        WHERE comment_answer_id = " . $answer_id;

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Страница комментариев участника
    public static function userComments($page, $limit, $user_id, $uid_id)
    {
        $start  = ($page - 1) * $limit;
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
                    post_id, 
                    post_slug,
                    post_title,
                    post_is_deleted,
                    user_id, 
                    user_login, 
                    user_avatar
                        FROM comments 
                        LEFT JOIN users  ON user_id = comment_user_id
                        LEFT JOIN posts  ON comment_post_id = post_id 
                        LEFT JOIN votes_comment  ON votes_comment_item_id = comment_id
                        AND votes_comment_user_id = :uid_id
                            WHERE comment_user_id = :user_id AND comment_is_deleted = 0 
                                AND post_is_deleted = 0 AND post_tl = 0
                                    ORDER BY comment_id DESC LIMIT $start, $limit";

        return DB::run($sql, ['user_id' => $user_id, 'uid_id' => $uid_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество комментариев участника
    public static function userCommentsCount($user_id)
    {
        $sql = "SELECT 
                    comment_id
                        FROM comments 
                        LEFT JOIN posts  ON comment_post_id = post_id 
                            WHERE comment_user_id = :user_id AND comment_is_deleted = 0 
                                AND post_is_deleted = 0 AND post_tl = 0";

        return DB::run($sql, ['user_id' => $user_id])->rowCount();
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
}
