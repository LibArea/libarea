<?php

declare(strict_types=1);

namespace Modules\Admin\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class ConsoleModel extends Model
{
	// Let's recalculate the number of posts in the Topics
	// Пересчитаем количество постов в Темах
    public static function recalculateTopic()
    {
        $sql = "UPDATE facets SET facet_count = (SELECT count(relation_post_id) FROM facets_posts_relation 
                       LEFT JOIN posts ON relation_post_id = post_id WHERE relation_facet_id = facet_id AND post_is_deleted = 0)";

        return DB::run($sql);
    }

	// Let's recalculate the number of сщььутеы in the Posts
	// Пересчитаем количество комментариев в Постах
    public static function recalculateCountCommentPost()
    {
        $sql = "UPDATE posts SET post_comments_count = (SELECT count(comment_post_id) FROM comments WHERE comment_post_id = post_id AND comment_is_deleted = 0)";

        return DB::run($sql);
    }

    public static function allUp(int $uid)
    {
        $sql = "SELECT 
                    (SELECT SUM(post_votes) FROM posts WHERE post_user_id = $uid AND post_is_deleted = 0) 
                            AS count_posts,
                    (SELECT SUM(comment_votes) FROM comments WHERE comment_user_id = $uid AND comment_is_deleted = 0) 
                            AS count_comments";

        $user = DB::run($sql)->fetch();

        return $user['count_posts'] + $user['count_comments'];
    }

    public static function allUsers()
    {
        return DB::run("SELECT id FROM users")->fetchAll();
    }

    public static function setAllUp(int $uid, int $count)
    {
        $sql = "UPDATE users SET up_count  = :count WHERE id = :uid";

        return DB::run($sql, ['uid' => $uid, 'count' => $count]);
    }

    // Users Trust Level
    public static function getTrustLevel(int $tl)
    {
        $sql = "SELECT id, trust_level, up_count FROM users WHERE trust_level = :tl";

        return DB::run($sql, ['tl' => $tl])->fetchAll();
    }

    public static function setTrustLevel(int $uid, int $tl)
    {
        $sql = "UPDATE users SET trust_level = :tl WHERE id = :uid";

        return DB::run($sql, ['uid' => $uid, 'tl' => $tl]);
    }
}
