<?php

namespace App\Models\Admin;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class СonsoleModel extends MainModel
{
    public static function recalculateTopic()
    {
        $sql = "UPDATE topics SET topic_count = (SELECT count(relation_post_id) FROM topics_post_relation where relation_topic_id = topic_id )";

        return DB::run($sql);
    }
    
    public static function allUp($user_id)
    {
        $sql = "SELECT 
                    (SELECT SUM(post_votes) FROM posts WHERE post_user_id = :user_id) 
                            AS count_posts,
                    (SELECT SUM(answer_votes) FROM answers WHERE answer_user_id = :user_id) 
                            AS count_answers,
                    (SELECT SUM(comment_votes) FROM comments WHERE comment_user_id = :user_id) 
                            AS count_comments";
                            
       $user = DB::run($sql, ['user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
       // Вернем сумму, но этот запрос необходим будет далее именно по отдельным типам 
       return $user['count_posts'] + $user['count_answers'] + $user['count_comments'];  
    } 
    
    public static function allUsers()
    {
        return DB::run("SELECT user_id FROM users")->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function setAllUp($user_id, $count)
    {
        $sql = "UPDATE users SET user_up_count  = :count WHERE user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id, 'count' => $count]);
    }
    
}