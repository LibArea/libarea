<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class WebModel extends MainModel
{
    // Все сайты
    public static function getItemsAll($page, $limit, $user_id)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT
                    item_id, 
                    item_title_url,
                    item_content_url,
                    item_published,
                    item_user_id,
                    item_url,
                    item_url_domain,
                    item_votes,
                    item_count,
                    item_title_soft,
                    item_github_url,
                    item_is_deleted,
                    rel.*,
                    votes_item_user_id, 
                    votes_item_item_id
                        FROM items
                        LEFT JOIN
                        (
                            SELECT 
                                relation_item_id,
                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets  
                                LEFT JOIN facets_items_relation 
                                    on facet_id = relation_facet_id
                                    WHERE facet_is_web = 1
                                        GROUP BY relation_item_id
                        ) AS rel
                            ON rel.relation_item_id = item_id 

                        LEFT JOIN votes_item ON votes_item_item_id = item_id AND  votes_item_user_id = $user_id
                        WHERE item_is_deleted = 0
                        ORDER BY item_id DESC LIMIT $start, $limit ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getItemsAllCount()
    {
        $sql = "SELECT item_id, item_is_deleted FROM items WHERE item_is_deleted = 0";

        return DB::run($sql)->rowCount();
    }

    // 5 популярных доменов
    public static function getItemsTop($domain)
    {
        $sql = "SELECT
                    item_id,
                    item_title_url,
                    item_content_url,
                    item_published,
                    item_user_id,
                    item_url,
                    item_url_domain,
                    item_votes,
                    item_count,
                    item_is_deleted
                        FROM items 
                        WHERE item_url_domain != :domain AND item_published = 1 AND item_is_deleted = 0
                        ORDER BY item_count DESC LIMIT 10";

        return DB::run($sql, ['domain' => $domain])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Получаем домены по условиям
    // https://systemrequest.net/index.php/123/
    public static function feedItem($page, $limit, $facets, $uid, $topic_id, $type)
    {
        $result = [];
        foreach ($facets as $ind => $row) {
            $result['9999'] = $topic_id;
            $result[$ind] = $row['value'];
        }

        $sort = "ORDER BY item_votes DESC";
        if ($type == 'new') $sort = "ORDER BY item_date DESC";

        $string = "relation_facet_id IN($topic_id)";
        if ($result) $string = "relation_facet_id IN(" . implode(',', $result ?? []) . ")";

        $start  = ($page - 1) * $limit;

        $sql = "SELECT DISTINCT
                    item_id,
                    item_title_url,
                    item_content_url,
                    item_published,
                    item_user_id,
                    item_url,
                    item_url_domain,
                    item_votes,
                    item_date,
                    item_count,
                    item_title_soft,
                    item_github_url,
                    item_is_deleted,
                    rel.*,
                    votes_item_item_id, votes_item_user_id,
                    user_id, user_login, user_avatar
  
                        FROM facets_items_relation 
                        LEFT JOIN items ON relation_item_id = item_id
                        LEFT JOIN (
                            SELECT 
                                relation_item_id,
                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets
                                LEFT JOIN facets_items_relation 
                                    on facet_id = relation_facet_id
                                    WHERE facet_is_web = 1
                                    GROUP BY relation_item_id
                        ) AS rel
                             ON rel.relation_item_id = item_id
                            LEFT JOIN users ON user_id = item_user_id
                            LEFT JOIN votes_item 
                                ON votes_item_item_id = item_id AND votes_item_user_id = :user_id

                                WHERE $string $sort LIMIT $start, $limit";

        return DB::run($sql, ['user_id' => $uid['user_id']])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function feedItemCount($facets, $topic_id)
    {
        $result = [];
        foreach ($facets as $ind => $row) {
            $result['9999'] = $topic_id;
            $result[$ind] = $row['value'];
        }

        $string = "relation_facet_id IN($topic_id)";
        if ($result) $string = "relation_facet_id IN(" . implode(',', $result ?? []) . ")";

        $sql = "SELECT DISTINCT
                    item_id,
                    item_published,
                    item_url,
                    item_is_deleted,
                    rel.*
                        FROM facets_items_relation 
                        LEFT JOIN items ON relation_item_id = item_id
            
                        LEFT JOIN (
                            SELECT 
                                relation_item_id,
                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets
                                LEFT JOIN facets_items_relation 
                                    on facet_id = relation_facet_id
                                    WHERE facet_is_web = 1
                                    GROUP BY relation_item_id
                        ) AS rel
                             ON rel.relation_item_id = item_id

                                WHERE $string";

        return DB::run($sql)->rowCount();
    }

    // Проверим наличие домена
    public static function getItemOne($domain, $user_id)
    {
        $sql = "SELECT
                    item_id,
                    item_title_url,
                    item_content_url,
                    item_title_soft,
                    item_content_soft,
                    item_published,
                    item_user_id,
                    item_url,
                    item_url_domain,
                    item_votes,
                    item_count,
                    item_is_soft,
                    item_is_github,
                    item_github_url,
                    item_post_related,
                    item_is_deleted,
                    votes_item_user_id, 
                    votes_item_item_id
                        FROM items 
                        LEFT JOIN votes_item ON votes_item_item_id = item_id AND  votes_item_user_id = :user_id
                        WHERE item_url_domain = :domain AND item_is_deleted = 0";


        return DB::run($sql, ['domain' => $domain, 'user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Добавим домен
    public static function add($data)
    {
        $params = [
            'item_url'          => $data['item_url'],
            'item_url_domain'   => $data['item_url_domain'],
            'item_title_url'    => $data['item_title_url'],
            'item_content_url'  => $data['item_content_url'],
            'item_published'    => $data['item_published'],
            'item_user_id'      => $data['item_user_id'],
            'item_type_url'     => $data['item_type_url'],
            'item_status_url'   => $data['item_status_url'],
            'item_is_soft'      => 0,
            'item_is_github'    => 0,
            'item_votes'        => 0,
            'item_count'        => 1,
        ];
        
        $sql = "INSERT INTO items(item_url, 
                            item_url_domain, 
                            item_title_url, 
                            item_content_url, 
                            item_published,
                            item_user_id, 
                            item_type_url, 
                            item_status_url, 
                            item_is_soft,
                            item_is_github,
                            item_votes,
                            item_count) 
                            
                       VALUES(:item_url, 
                       :item_url_domain, 
                       :item_title_url, 
                       :item_content_url, 
                       :item_published,
                       :item_user_id, 
                       :item_type_url, 
                       :item_status_url, 
                       :item_is_soft,
                       :item_is_github,
                       :item_votes,
                       :item_count)";

        DB::run($sql, $params);

        $item_id =  DB::run("SELECT LAST_INSERT_ID() as item_id")->fetch(PDO::FETCH_ASSOC);

        return $item_id;
    }

    public static function addItemCount($domain)
    {
        $sql = "UPDATE items SET item_count = (item_count + 1) WHERE item_url_domain = :domain";
        DB::run($sql, ['domain' => $domain]);
    }

    public static function edit($data)
    {
        $params = [
            'item_url'          => $data['item_url'],
            'item_title_url'    => $data['item_title_url'],
            'item_content_url'  => $data['item_content_url'],
            'item_title_soft'   => $data['item_title_soft'],
            'item_content_soft' => $data['item_content_soft'],
            'item_published'    => $data['item_published'],
            'item_status_url'   => $data['item_status_url'],
            'item_is_soft'      => $data['item_is_soft'],
            'item_is_github'    => $data['item_is_github'],
            'item_github_url'   => $data['item_github_url'],
            'item_post_related' => $data['item_post_related'], 
            'item_id'           => $data['item_id'],
        ];

        $sql = "UPDATE items 
                    SET item_url        = :item_url,  
                    item_title_url      = :item_title_url, 
                    item_content_url    = :item_content_url,
                    item_title_soft     = :item_title_soft, 
                    item_content_soft   = :item_content_soft,
                    item_published      = :item_published,
                    item_status_url     = :item_status_url,
                    item_is_soft        = :item_is_soft,
                    item_is_github      = :item_is_github,
                    item_github_url     = :item_github_url,
                    item_post_related   = :item_post_related

                        WHERE item_id   = :item_id";

        return  DB::run($sql, $params);
    }

    // Данные по id
    public static function getItemId($item_id)
    {
        $sql = "SELECT
                    item_id,
                    item_title_url,
                    item_content_url,
                    item_title_soft,
                    item_content_soft,
                    item_published,
                    item_user_id,
                    item_url,
                    item_url_domain,
                    item_votes,
                    item_count,
                    item_status_url,
                    item_is_soft,
                    item_is_github,
                    item_github_url,
                    item_post_related,
                    item_is_deleted
                        FROM items 
                        WHERE item_id = :item_id AND item_is_deleted = 0";


        return DB::run($sql, ['item_id' => $item_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Темы по ссылке
    public static function getItemTopic($item_id)
    {
        $sql = "SELECT
                    facet_id as value,
                    facet_title,
                    facet_slug,
                    facet_is_web,
                    relation_facet_id,
                    relation_item_id
                        FROM facets  
                        INNER JOIN facets_items_relation ON relation_facet_id = facet_id
                            WHERE relation_item_id  = :item_id";

        return DB::run($sql, ['item_id' => $item_id])->fetchAll(PDO::FETCH_ASSOC);
    }
}
