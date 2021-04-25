<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class SearchModel extends \MainModel
{
    public static function getSearch($query)
    {
        $sql = "SELECT post_id, post_slug, post_title, post_content  FROM posts
                WHERE post_content LIKE :qa1 OR post_title LIKE :qa2 ORDER BY post_id LIMIT 10";
        $result_q = DB::run($sql,['qa1' => "%".$query."%", 'qa2' => "%".$query."%"]);
        
        return $result_q->fetchall(PDO::FETCH_ASSOC);
    }

    // Получение постов по url
    public static function getDomain($url, $uid)
    {
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                ->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                ->leftJoin(['votes_post'])->on(['votes_post_item_id'], '=', ['post_id'])
                ->and(['votes_post_user_id'], '=', $uid)
                ->where(['post_is_delete'], '=', 0)
                ->and(['post_url_domain'], '=', $url)
                ->orderBy(['post_id'])->desc();

        return $query->getSelect();
    }

}