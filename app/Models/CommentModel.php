<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Static\Request;
use Hleb\Base\Model;
use Hleb\Static\DB;
use App\Models\PostModel;

class CommentModel extends Model
{
    public static $limit = 15;

    /**
     * Add an comment
     * Добавим ответ
     *
     * @param integer $post_id
     * @param integer $parent_id
     * @param string $content
     * @param integer $trigger
     * @param integer $mobile
     * @return mixed
     */
    public static function add(int $post_id, int $parent_id, string $content, $trigger, $mobile)
    {
        $params = [
            'comment_post_id'    => $post_id,
            'comment_parent_id'  => $parent_id,
            'comment_content'    => $content,
            'comment_published'  => ($trigger === false) ? 0 : 1,
            'comment_ip'         => Request::getUri()->getIp(),
            'comment_user_id'    => self::container()->user()->id(),
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

    /**
     * Editing the comment
     * Редактируем ответ
     *
     * @param array $params
     */
    public static function edit(array $params)
    {
        $sql_two = "UPDATE comments SET comment_content = :comment_content, 
                        comment_modified = :comment_modified, comment_user_id = :comment_user_id
                            WHERE comment_id = :comment_id";

        return DB::run($sql_two, $params);
    }

    /**
     * All comments 
     * Все ответы
     *
     * @param integer $page
     * @param string $sheet
     */
    public static function getComments(int $page, string $sheet): false|array
    {
        $user_id = self::container()->user()->id();
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

    public static function getCommentsCount(string $sheet)
    {
        $sort = self::sorts($sheet);

        $sql = "SELECT comment_id FROM comments INNER JOIN posts ON comment_post_id = post_id $sort";

        return DB::run($sql)->rowCount();
    }

    public static function sorts(string $sheet)
    {
        $hidden = self::container()->user()->admin() ? '' : 'AND post_hidden = 0';

		return match ($sheet) {
			'all'		=> 'WHERE comment_is_deleted = 0 AND post_tl = 0 AND post_is_deleted = 0 ' . $hidden,
			'deleted'	=> 'WHERE comment_is_deleted = 1',
			default		=> 'WHERE comment_is_deleted = 0 AND post_is_deleted = 0',
		};
    }

    /**
     * Number of replies per post
     * Количество ответов на пост
     *
     * @param integer $post_id
     */
    public static function getNumberComment(int $post_id)
    {
        $sql = "SELECT comment_id FROM comments WHERE comment_post_id = :id AND comment_is_deleted = 0";

        return DB::run($sql, ['id' => $post_id])->rowCount();
    }

    /**
     * Add the comment to the end of the post
     * Добавим ответ в конец поста
     *
     * @param string $content
     * @param integer $post_id
     */
    public static function mergePost(string $content, int $post_id)
    {
        $sql = "UPDATE posts SET post_content = CONCAT(post_content, :content) WHERE post_id = :post_id";

        $content = "\n\n `+` " . $content;

        return DB::run($sql, ['post_id' => $post_id, 'content' => $content]);
    }

    /**
     * Add the answer to the end of the last comment (if there is a repeat comment)
     * Добавим ответ в конец последнего комментария (если есть повторное комментирования)
     *
     * @param string $content
     * @param integer $comment_id
     */
    public static function mergeComment(string $content, int $comment_id)
    {
        $sql = "UPDATE comments SET comment_content = CONCAT(comment_content, :content) WHERE comment_id = :comment_id";

        $content = "\n\n `+` " . $content;

        return DB::run($sql, ['comment_id' => $comment_id, 'content' => $content]);
    }

    /**
     * Is the last response to a specific comment a participant's comment
     * Является ли последний ответ на конкретный комментарий комментарием участника
     *
     * @param integer $comment_id
     * @return boolean
     */
    public static function isResponseUser(int $comment_id)
    {
        $sql = "SELECT comment_id FROM comments WHERE comment_parent_id = :id AND comment_user_id = :user_id";

        return DB::run($sql, ['id' => $comment_id, 'user_id' => self::container()->user()->id()])->fetch();
    }

    /**
     * Getting comments in a post
     * Получаем ответы в посте
     *
     * @param integer $post_id
     * @param integer $type
     * @param string $sorting
     * @return array
     */
    public static function getCommentsPost(int $post_id, int $type, string|null $sorting = 'new')
    {
        $user_id = self::container()->user()->id();

        if ($type == 1) {
            $sorting = 'top';
        }

		$sort = match ($sorting) {
			'top'		=> 'ORDER BY comment_lo DESC, comment_votes DESC',
			'old'		=> 'ORDER BY comment_id DESC',
			default		=> '',
		};

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

    /**
     * Let's check if the participant has a response (in the comments) to the post.
     * Проверим, есть ли ответ участника (в комментариях) на пост.
     *
     * @param integer $post_id
     * @return boolean
     */
    public static function isAnswerUser(int $post_id)
    {
        $sql = "SELECT comment_id FROM comments WHERE comment_parent_id = 0 AND comment_post_id = :post_id AND comment_user_id = :user_id";

        return DB::run($sql, ['post_id' => $post_id, 'user_id' => self::container()->user()->id()])->fetch();
    }

    /**
     * User responses
     * Ответы участника
     *
     * @param integer $page
     * @param integer $user_id
     * @param integer $uid_vote
     * @return array|false
     */
    public static function userComments(int $page, int $user_id, int $uid_vote)
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

    /**
     * Number of participant comments
     * Количество ответов участника
     *
     * @param integer $user_id
     * @return int
     */
    public static function userCommentsCount(int $user_id)
    {
        $sql = "SELECT 
                    comment_id
                        FROM comments
                        LEFT JOIN posts ON comment_post_id = post_id
                            WHERE comment_user_id = :user_id AND comment_is_deleted = 0 
                                AND post_is_deleted = 0 AND post_tl = 0 AND post_tl = 0";

        return DB::run($sql, ['user_id' => $user_id])->rowCount();
    }

    /**
     * Information on the id of the comment
     * Информацию по id ответа
     *
     * @param integer $comment_id
     * @return array|bool
     */
    public static function getCommentId(int $comment_id)
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

    /**
     * Choice of the best comment
     * Выбор лучшего ответа
     *
     * @param integer $post_id
     * @param integer $comment_id
     * @param integer $selected_best_comment
     * @return void
     */
    public static function setBest(int $post_id, int $comment_id, bool $selected_best_comment)
    {
        if ($selected_best_comment) {
            DB::run("UPDATE comments SET comment_lo = 0 WHERE comment_id = :id", ['id' => $selected_best_comment]);
        }

        self::setCommentBest($comment_id);

        self::commentPostBest($post_id, $comment_id);
    }

    /**
     * Let's write down the id of the participant who chose the best comment
     * Запишем id участника выбравший лучший ответ
     *
     * @param integer $comment_id
     */
    public static function setCommentBest(int $comment_id)
    {
        $sql = "UPDATE comments SET comment_lo = :user_id WHERE comment_id = :comment_id";

        return  DB::run($sql, ['comment_id' => $comment_id, 'user_id' => self::container()->user()->id()]);
    }

    /**
     * Rewriting the number of the selected best comment in the post
     * Переписываем номер выбранного лучший ответ в посте
     *
     * @param integer $post_id
     * @param integer $comment_id
     */
    public static function commentPostBest(int $post_id, int $comment_id)
    {
        $sql_two = "UPDATE posts SET post_lo = :comment_id WHERE post_id = :post_id";

        return DB::run($sql_two, ['post_id' => $post_id, 'comment_id' => $comment_id]);
    }

    /**
     * The last 5 responses on the main page
     * Последние 5 ответа на главной
     *
     * @param integer $limit
     * @return array
     */
    public static function latestComments(int $limit = 5): array
    {
        $trust_level = self::container()->user()->tl();
        $user_comment = "AND post_tl = 0";

        if ($user_id = self::container()->user()->id()) {
            $user_comment = "AND comment_user_id != $user_id AND post_tl <= $trust_level";
        }

        $hidden = self::container()->user()->admin() ? "" : "AND post_hidden = 0";

        $sql = "SELECT 
                    comment_id,
                    comment_post_id,
                    comment_content,
                    comment_date,
                    post_id,
					post_title,
                    post_slug,
                    post_hidden,
                    login,
                    avatar,
					created_at
                        FROM comments 
                        LEFT JOIN users ON id = comment_user_id
                        RIGHT JOIN posts ON post_id = comment_post_id
                            WHERE comment_is_deleted = 0 AND post_is_deleted = 0 $hidden 
                                $user_comment AND post_type = 'post'
                                    ORDER BY comment_id DESC LIMIT :limit";

        return DB::run($sql, ['limit' => $limit])->fetchAll();
    }
}
