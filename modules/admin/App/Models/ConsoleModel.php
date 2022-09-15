<?php

namespace Modules\Admin\App\Models;

use DB;

class ConsoleModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function recalculateTopic()
    {
        $sql = "UPDATE facets SET facet_count = (SELECT count(relation_post_id) FROM facets_posts_relation where relation_facet_id = facet_id )";

        return DB::run($sql);
    }

    public static function allUp($uid)
    {
        $sql = "SELECT 
                    (SELECT SUM(post_votes) FROM posts WHERE post_user_id = $uid) 
                            AS count_posts,
                    (SELECT SUM(answer_votes) FROM answers WHERE answer_user_id = $uid) 
                            AS count_answers,
                    (SELECT SUM(comment_votes) FROM comments WHERE comment_user_id = $uid) 
                            AS count_comments";

        $user = DB::run($sql)->fetch();
        // Вернем сумму, но этот запрос необходим будет далее именно по отдельным типам 
        return $user['count_posts'] + $user['count_answers'] + $user['count_comments'];
    }

    public static function allUsers()
    {
        return DB::run("SELECT id FROM users")->fetchAll();
    }

    public static function setAllUp($uid, $count)
    {
        $sql = "UPDATE users SET up_count  = :count WHERE id = :uid";

        return DB::run($sql, ['uid' => $uid, 'count' => $count]);
    }

    // Users Trust Level
    public static function getTrustLevel($tl)
    {
        $sql = "SELECT id, trust_level, up_count FROM users WHERE trust_level = :tl";

        return DB::run($sql, ['tl' => $tl])->fetchAll();
    }

    public static function setTrustLevel($uid, $tl)
    {
        $sql = "UPDATE users SET trust_level = :tl WHERE id = :uid";

        return DB::run($sql, ['uid' => $uid, 'tl' => $tl]);
    }
}
