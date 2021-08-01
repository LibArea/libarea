<?php

namespace Modules\Admin\Models;

use DB;
use PDO;

class CommentModel extends \MainModel
{
    // Все комментарии
    public static function getCommentsAll($page, $limit, $sheet)
    {
        $sort = "WHERE comment_is_deleted = 0";
        if ($sheet == 'ban') {
            $sort = "WHERE comment_is_deleted = 1";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT
                    post_id,
                    post_title,
                    post_slug,
                    post_tl,
                    post_type,
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
                        JOIN posts ON comment_post_id = post_id
                        $sort
                        ORDER BY comment_id DESC LIMIT $start, $limit ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество комментариев
    public static function getCommentsAllCount($sheet)
    {

        $sort = "WHERE comment_is_deleted = 0";
        if ($sheet == 'ban') {
            $sort = "WHERE comment_is_deleted = 1";
        }

        $sql = "SELECT comment_id, comment_is_deleted FROM comments $sort";

        return DB::run($sql)->rowCount();
    }
}
