<?php

namespace App\Models;

use Hleb\Constructor\Handlers\Request;
use App\Models\PostModel;
use UserData;
use DB;

class CommentModel extends \Hleb\Scheme\App\Models\MainModel
{
	public static $limit = 15;
	
    // Add an comment
    // Добавим ответ
    public static function add($post_id, $comment_id, $content, $trigger, $mobile)
    {
        $params = [
            'comment_post_id'    => $post_id,
			'comment_parent_id'  => $comment_id,
            'comment_content'    => $content,
            'comment_published'  => ($trigger === false) ? 0 : 1,
            'comment_ip'         => Request::getRemoteAddress(),
            'comment_user_id'    => UserData::getUserId(),
			'comment_is_mobile'  => ($mobile === false) ? 0 : 1,
        ];

        $sql = "INSERT INTO comments(comment_post_id, 
					comment_parent_id,
                    comment_content, 
                    comment_published, 
                    comment_ip, 
                    comment_user_id,
					comment_is_mobile) 
                       VALUES(:comment_post_id, 
						   :comment_parent_id,
                           :comment_content, 
                           :comment_published, 
                           :comment_ip, 
                           :comment_user_id,
						   :comment_is_mobile)";

        DB::run($sql, $params);

        $sql_last_id =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch();

        // Recalculating the number of responses for the post + 1
        // Пересчитываем количество ответов для поста + 1
        PostModel::updateCount($post_id, 'comments');

        return $sql_last_id['last_id'];
    }

    // Editing the comment
    // Редактируем ответ
    public static function edit($params)
    {
        $sql_two = "UPDATE comments SET comment_content = :comment_content, 
                        comment_modified = :comment_modified, comment_user_id = :comment_user_id
                            WHERE comment_id = :comment_id";

        return DB::run($sql_two, $params);
    }

    // All comments    
    // Все ответы
    public static function getComments($page, $sheet)
    {
        $user_id = UserData::getUserId();
        $sort = self::sorts($sheet);
        $start  = ($page - 1) * self::$limit;
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_user_id,
                    post_closed,
                    post_feature,
                    post_is_deleted,
                    comment_id,
                    comment_content,
                    comment_date,
                    comment_user_id,
                    comment_ip,
                    comment_post_id,
                    comment_votes,
                    comment_is_deleted,
                    comment_published,
                    votes_comment_item_id, 
                    votes_comment_user_id,
                    fav.tid,
                    fav.user_id,
                    fav.action_type,
                    u.id, 
                    u.login, 
                    u.avatar
                        FROM comments
                        INNER JOIN users u ON u.id = comment_user_id
                        INNER JOIN posts ON comment_post_id = post_id 
                        LEFT JOIN votes_comment ON votes_comment_item_id = comment_id
                            AND votes_comment_user_id = $user_id
                        LEFT JOIN favorites fav ON fav.tid = comment_id
                            AND fav.user_id  = $user_id
                            AND fav.action_type = 'comment'    
                        $sort
                        ORDER BY comment_id DESC LIMIT :start, :limit ";

        return DB::run($sql, ['start' => $start, 'limit' => self::$limit])->fetchAll();
    }

    public static function getCommentsCount($sheet)
    {
        $sort = self::sorts($sheet);

        $sql = "SELECT comment_id FROM comments INNER JOIN posts ON comment_post_id = post_id $sort";

        return DB::run($sql)->rowCount();
    }

    public static function sorts($sheet)
    {
        $hidden = UserData::checkAdmin() ? "" : "AND post_hidden = 0";
        
        switch ($sheet) {
            case 'all':
                $sort     = "WHERE comment_is_deleted = 0 AND post_tl = 0 AND post_is_deleted = 0 $hidden";
                break;
            case 'deleted':
                $sort     = "WHERE comment_is_deleted = 1";
                break;
        }

        return $sort;
    }

    // Number of replies per post
    // Количество ответов на пост
    public static function getNumberComment($post_id)
    {
        $sql = "SELECT comment_id FROM comments WHERE comment_post_id = :id AND comment_is_deleted = 0";

        return DB::run($sql, ['id' => $post_id])->rowCount();
    }

    // Add the comment to the end of the post
    // Добавим ответ в конец поста
    public static function mergePost($post_id, $content)
    {
        $sql = "UPDATE posts SET post_content = CONCAT(post_content, :content) WHERE post_id = :post_id";

        $content = "\n\n `+` " . $content;

        return DB::run($sql, ['post_id' => $post_id, 'content' => $content]); 
    }

	// Is the last response to a specific comment a participant's comment
	// Является ли последний ответ на конкретный комментарий комментарием участника
	public static function isResponseUser($comment_id)
	{
        $sql = "SELECT comment_id FROM comments WHERE comment_parent_id = :id AND comment_user_id = :user_id";

        return DB::run($sql, ['id' => $comment_id, 'user_id' => UserData::getUserId()])->fetch();
	}

    // Getting comments in a post
    // Получаем ответы в посте
    public static function getCommentsPost($post_id, $type, $sorting = 'new')
    {
        $user_id = UserData::getUserId();

        if ($type == 1) {
            $sorting = 'top';
        }

        switch ($sorting) {
            case 'top':
                $sort = 'ORDER BY comment_lo DESC, comment_votes DESC';
                break;
            case 'old':
                $sort = 'ORDER BY comment_id DESC';
                break;
                // new    
            default:
                $sort = '';
                break;
        }

        $sql = "SELECT 
                    comment_id,
                    comment_user_id,
                    comment_post_id,
					comment_parent_id,
                    comment_date,
                    comment_content,
                    comment_modified,
                    comment_published,
                    comment_ip,
                    comment_votes,
                    comment_lo,
					comment_is_mobile,
                    comment_is_deleted,
                    votes_comment_item_id, 
                    votes_comment_user_id,
                    fav.tid,
                    fav.user_id,
                    fav.action_type,
                    u.id, 
                    u.login,
                    u.avatar,
                    u.created_at
                        FROM comments
                        LEFT JOIN users u ON u.id = comment_user_id
                        LEFT JOIN votes_comment ON votes_comment_item_id = comment_id
                            AND votes_comment_user_id = $user_id
                        LEFT JOIN favorites fav ON fav.tid = comment_id
                            AND fav.user_id  = $user_id
                            AND fav.action_type = 'comment'
								WHERE comment_post_id = $post_id $sort";

        return DB::run($sql)->fetchAll();
    }

    // User responses
    // Ответы участника
    public static function userComments($page, $user_id, $uid_vote)
    {
        $start  = ($page - 1) * self::$limit;
        $sql = "SELECT 
                    comment_id,
                    comment_user_id,
                    comment_post_id,
                    comment_date,
                    comment_content,
                    comment_modified,
                    comment_published,
                    comment_ip,
                    comment_votes,
                    comment_is_deleted,
                    votes_comment_item_id, 
                    votes_comment_user_id,
                    post_id,
                    post_title,
                    post_slug,
                    post_user_id,
                    post_closed,
                    post_is_deleted,
                    id, 
                    login, 
                    avatar
                        FROM comments
                        LEFT JOIN users ON id = comment_user_id
                        LEFT JOIN posts ON comment_post_id = post_id
                        LEFT JOIN votes_comment ON votes_comment_item_id = comment_id
                            AND votes_comment_user_id = :uid_vote
                        WHERE comment_user_id = :user_id AND post_hidden = 0
                            AND comment_is_deleted = 0 AND post_is_deleted = 0 AND post_tl = 0 AND post_tl = 0
                                ORDER BY comment_id DESC LIMIT :start, :limit ";

        return DB::run($sql, ['user_id' => $user_id, 'uid_vote' => $uid_vote, 'start' => $start, 'limit' => self::$limit])->fetchAll();
    }

    public static function userCommentsCount($user_id)
    {
        $sql = "SELECT 
                    comment_id
                        FROM comments
                        LEFT JOIN posts ON comment_post_id = post_id
                            WHERE comment_user_id = :user_id AND comment_is_deleted = 0 
                                AND post_is_deleted = 0 AND post_tl = 0 AND post_tl = 0";

        return DB::run($sql, ['user_id' => $user_id])->rowCount();
    }

    // Information on the id of the comment
    // Информацию по id ответа
    public static function getCommentId($comment_id)
    {
        $sql = "SELECT 
                    comment_id,
                    comment_post_id,
					comment_parent_id,
                    comment_user_id,
                    comment_date,
                    comment_modified,
                    comment_published,
                    comment_ip,
                    comment_votes,
                    comment_content,
                    comment_lo,
                    comment_is_deleted
                        FROM comments 
                            WHERE comment_id = :comment_id";

        return  DB::run($sql, ['comment_id' => $comment_id])->fetch();
    }

    /* 
     *  Best comment
     */

    // Choice of the best comment
    // Выбор лучшего ответа
    public static function setBest($post_id, $comment_id, $selected_best_comment)
    {
        if ($selected_best_comment) {
            DB::run("UPDATE comments SET comment_lo = 0 WHERE comment_id = :id", ['id' => $selected_best_comment]);
        }

        self::setCommentBest($comment_id);

        self::commentPostBest($post_id, $comment_id);
    }

    // Let's write down the id of the participant who chose the best comment
    // Запишем id участника выбравший лучший ответ
    public static function setCommentBest($comment_id)
    {
        $sql = "UPDATE comments SET comment_lo = :user_id WHERE comment_id = :comment_id";

        return  DB::run($sql, ['comment_id' => $comment_id, 'user_id' => UserData::getUserId()]);
    }

    // Rewriting the number of the selected best comment in the post
    // Переписываем номер выбранного лучший ответ в посте
    public static function commentPostBest($post_id, $comment_id)
    {
        $sql_two = "UPDATE posts SET post_lo = :comment_id WHERE post_id = :post_id";

        return DB::run($sql_two, ['post_id' => $post_id, 'comment_id' => $comment_id]);
    }
}
