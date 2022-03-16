<?php

namespace App\Models;

use DB;

class CommentModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Adding a comment
    // Добавляем комментарий
    public static function add($params)
    {
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

        $sql_last_id    =   DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch();
        $last_id        =   $sql_last_id['last_id'];

        // Отмечаем комментарий, что за ним есть ответ
        self::setThereComment($last_id, $params['comment_comment_id']);

        $sql     = "SELECT * FROM answers WHERE answer_id = :comment_answer_id";
        $answer  = DB::run($sql, ['comment_answer_id' => $params['comment_answer_id']])->fetch();

        if ($answer['answer_after'] == 0) {

            self::setThereAnswer($last_id, $params['comment_answer_id']);
        }

        return $last_id;
    }

    // Editing a comment
    // Редактируем комментарий
    public static function edit($params)
    {
        $sql = "UPDATE comments SET 
                    comment_content     = :comment_content,
                    comment_modified    = :comment_modified
                         WHERE comment_id = :comment_id";

        return DB::run($sql, $params);
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
    public static function getCommentsAll($page, $limit, $user, $sheet)
    {
        $sort = self::sorts($user, $sheet);
        $tl = $user['trust_level'];
        $start  = ($page - 1) * $limit;

        $sql = "SELECT
                    post_id,
                    post_title,
                    post_slug,
                    post_tl,
                    post_feature,
                    post_user_id,
                    post_closed,
                    post_is_deleted,
                    comment_id,
                    comment_ip,
                    comment_date,
                    comment_content,
                    comment_post_id,
                    comment_user_id,
                    comment_comment_id,
                    comment_published,
                    comment_votes,
                    comment_after,
                    comment_is_deleted,
                    votes_comment_item_id, 
                    votes_comment_user_id,
                    id, 
                    login, 
                    avatar
                        FROM comments 
                            JOIN users ON id = comment_user_id
                            JOIN posts ON comment_post_id = post_id AND post_tl <= $tl
                            LEFT JOIN votes_comment ON votes_comment_item_id = comment_id
                                AND votes_comment_user_id = :uid
                                $sort
                                    ORDER BY comment_id DESC LIMIT $start, $limit ";

        return DB::run($sql, ['uid' => $user['id']])->fetchAll();
    }

    // Количество комментариев 
    public static function getCommentsAllCount($user, $sheet)
    {
        $tl = $user['trust_level'];
        $sort = self::sorts($user, $sheet);
        $sql = "SELECT 
                    comment_id, 
                    comment_is_deleted 
                        FROM comments 
                            JOIN posts ON comment_post_id = post_id AND post_tl <= $tl
                                $sort";

        return DB::run($sql)->rowCount();
    }

    public static function sorts($user, $sheet)
    {
        switch ($sheet) {
            case 'comments.all':
                $sort     = "WHERE comment_is_deleted = 0";
                break;
            case 'comments.deleted':
                $sort     = "WHERE comment_is_deleted = 1";
                break;
        }

        return $sort;
    }

    // Получаем комментарии к ответу
    public static function getComments($answer_id, $uid)
    {
        $sql = "SELECT 
                    comment_id,
                    comment_user_id,                
                    comment_answer_id,
                    comment_comment_id,
                    comment_content,
                    comment_date,
                    comment_votes,
                    comment_published,
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
                        AND votes_comment_user_id = :uid
                            WHERE comment_answer_id = " . $answer_id;

        return DB::run($sql, ['uid' => $uid])->fetchAll();
    }

    // Страница комментариев участника
    public static function userComments($page, $limit, $uid, $id)
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
                    id, 
                    login, 
                    avatar
                        FROM comments 
                        LEFT JOIN users  ON id = comment_user_id
                        LEFT JOIN posts  ON comment_post_id = post_id 
                        LEFT JOIN votes_comment  ON votes_comment_item_id = comment_id
                        AND votes_comment_user_id = :id
                            WHERE comment_user_id = :uid AND comment_is_deleted = 0 
                                AND post_is_deleted = 0 AND post_tl = 0
                                    ORDER BY comment_id DESC LIMIT $start, $limit";

        return DB::run($sql, ['uid' => $uid, 'id' => $id])->fetchAll();
    }

    // Количество комментариев участника
    public static function userCommentsCount($uid)
    {
        $sql = "SELECT 
                    comment_id
                        FROM comments 
                        LEFT JOIN posts  ON comment_post_id = post_id 
                            WHERE comment_user_id = :uid AND comment_is_deleted = 0 
                                AND post_is_deleted = 0 AND post_tl = 0";

        return DB::run($sql, ['uid' => $uid])->rowCount();
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

        return DB::run($sql, ['comment_id' => $comment_id])->fetch();
    }
}
