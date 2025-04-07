<?php

namespace Modules\Search\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

use S2\Rose\Storage\Database\PdoStorage;

class SearchModel extends Model
{

    public static function PdoStorage()
    {
        $database = config('database', 'db.settings.list');

        // Array ( [0] => mysql:host=localhost [1] => port=3306 [2] => dbname=lwiki [3] => charset=utf8 [user] => root [pass] => [options] => Array ( ) )
        $db = $database['mysql.name'];

        $host = substr(strstr($db[0], '='), 1);
        $port = substr(strstr($db[1], '='), 1);
        $dbname = substr(strstr($db[2], '='), 1);

        $pdo = new \PDO('mysql:host=' . $host . ':' . $port  .  ';dbname=' . $dbname . ';charset=utf8mb4', $db['user'], $db['pass']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return new PdoStorage($pdo, 'search_index_');
    }

    public static function getContentsAll()
    {
        $sql = "SELECT post_id, post_title, post_content, post_slug, post_type  
						FROM posts 
							WHERE 
								post_is_deleted = 0 AND post_tl = 0 AND post_draft = 0 AND post_type != 'page'";

        return DB::run($sql)->fetchAll();
    }

    public static function getFacetsAll()
    {
        $sql = "SELECT facet_id, facet_slug, facet_title,	facet_info
                        FROM facets 
							WHERE 
								facet_type = 'topic' AND facet_is_deleted = 0";

        return DB::run($sql)->fetchAll();
    }

    public static function getCommentsSearch(int $page, int $limit, string $query)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT comment_id, comment_content, post_id, post_slug, post_type, post_title as title
                    FROM comments  
                    LEFT JOIN posts ON comment_post_id = post_id 
                        WHERE post_is_deleted = 0
                            AND comment_content LIKE :qa LIMIT :start, :limit";

        return DB::run($sql, ['qa' => "%" . $query . "%", 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function setSearchLogs(array $params)
    {
        $sql = "INSERT INTO search_logs(request, 
                            action_type, 
                            add_ip,
                            user_id, 
                            count_results) 
                               VALUES(:request, 
                                   :action_type, 
                                   :add_ip,
                                   :user_id, 
                                   :count_results)";

        DB::run($sql, $params);
    }

    public static function getSearchLogs(int $limit)
    {
        $sql = "SELECT 
                    request, 
                    action_type,
                    add_date,
                    add_ip,
                    user_id, 
                    count_results
                        FROM search_logs ORDER BY id DESC LIMIT :limit";

        return DB::run($sql, ['limit' => $limit])->fetchAll();
    }

    public static function getSearchUsers(int $page, int $limit, null|string $login = '', null|string $ip = '')
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT id, login, name, reg_ip, email, trust_level FROM users WHERE login LIKE :login AND reg_ip LIKE :ip LIMIT :start, :limit";

        return DB::run($sql, ['login' => "%" . $login . "%", 'ip' => $ip . "%", 'start' => $start, 'limit' => $limit])->fetchAll();
    }


    public static function getLastIDContent()
    {
        $sql = "SELECT  MAX(CAST(external_id AS SIGNED)) as id
					FROM search_index_toc";

        $lastId = DB::run($sql)->fetch();

        return $lastId['id'];
    }

    public static function newIndexContent($lastId)
    {
        $sql = "SELECT post_id, post_title, post_content, post_slug, post_type  
						FROM posts 
							WHERE 
								post_is_deleted = 0 AND post_tl = 0 AND post_draft = 0 AND post_type != 'page' AND post_id > :lastId";

        return DB::run($sql, ['lastId' => $lastId])->fetchAll();
    }

    // Для простого поиска по таблицам.
    public static function getSearch(int $page, int $limit, string $query, string $type = 'content')
    {
        if ($type === 'comment') {
            return self::getComments($page, $limit, $query);
        }

        return self::getPosts($page, $limit, $query);
    }

    public static function getPosts(int $page, int $limit, string $query)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT DISTINCT 
                post_id, 
                post_title as title, 
                post_slug, 
				post_type, 
                post_published, 
                post_user_id, 
                post_votes as votes, 
                post_content as content,
                post_hits_count as count, 
                rel.*,  
                id, login, avatar
                    FROM facets_posts_relation  
                    LEFT JOIN posts ON relation_post_id = post_id 
                    LEFT JOIN ( SELECT  
                            relation_post_id,  
                            GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list  
                            FROM facets  
                            LEFT JOIN facets_posts_relation on facet_id = relation_facet_id  
                                GROUP BY relation_post_id  
                    ) AS rel ON rel.relation_post_id = post_id  
                        LEFT JOIN users ON id = post_user_id 
                            WHERE post_is_deleted = 0 AND post_draft = 0 AND post_tl = 0 AND post_hidden = 0 AND post_type != 'page' AND post_type != 'note'
                                AND MATCH(post_title, post_content) AGAINST (:qa) LIMIT :start, :limit";

        return DB::run($sql, ['qa' => $query, 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getComments(int $page, int $limit, string $query)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT comment_id, comment_content, post_id, post_slug, post_type, post_title as title
                    FROM comments  
                    LEFT JOIN posts ON comment_post_id = post_id 
                        WHERE post_is_deleted = 0
                            AND comment_content LIKE :qa LIMIT :start, :limit";

        return DB::run($sql, ['qa' => "%" . $query . "%", 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getSearchCount(string $query, string $type = 'content')
    {
        if ($type == 'comment') {
            $sql = "SELECT comment_id FROM comments LEFT JOIN posts ON comment_post_id = post_id WHERE post_is_deleted = 0 AND comment_content LIKE :qa";

            return DB::run($sql, ['qa' => "%" . $query . "%"])->rowCount();
        }

        $sql = "SELECT post_id FROM posts WHERE post_is_deleted = 0 AND post_hidden = 0 AND post_draft = 0 AND post_tl = 0 AND post_type != 'page' AND post_type != 'note' AND MATCH(post_title, post_content) AGAINST (:qa)";

        return DB::run($sql, ['qa' => $query])->rowCount();
    }

    public static function getSearchTags(null|string $query, string $type, int $limit)
    {
        $sql = "SELECT 
                    facet_slug slug, 
                    facet_title title
                        FROM facets WHERE facet_type = :type AND (facet_title LIKE :qa1 OR facet_slug LIKE :qa2) LIMIT :limit";

        return DB::run($sql, ['type' => $type, 'qa1' => "%" . $query . "%", 'qa2' => "%" . $query . "%", 'limit' => $limit])->fetchAll();
    }
}
