<?php

namespace App\Models\Item;

use DB;

class WebModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function sorts($sheet)
    {
        switch ($sheet) {
            case 'main':
                $sort     = "item_is_deleted = 0 AND item_published = 1 ORDER BY item_id DESC";
                break;
            case 'top':
                $sort     = "item_is_deleted = 0 ORDER BY item_votes DESC";
                break;
            case 'all':
                $sort     = "item_is_deleted = 0 ORDER BY item_date DESC";
                break;
            case 'deleted':
                $sort     = "item_is_deleted = 1 ORDER BY item_id DESC";
                break;
            case 'audits':
                $sort     = "item_is_deleted = 0 AND item_published = 0 ORDER BY item_id DESC";
                break;
            default:
                $sort = 'item_published = 1 ORDER BY item_id DESC';
        }

        return $sort;
    }

    public static function facets($facets, $topic_id)
    {
        if ($facets === false) {
            return '';
        }

        if ($topic_id === false) {
            return '';
        }

        $result = [];
        foreach ($facets as $ind => $row) {
            $result['9999'] = $topic_id;
            $result[$ind] = $row['facet_id'];
        }

        $enumeration = "relation_facet_id IN($topic_id) AND ";
        if ($result) {
            $enumeration = "relation_facet_id IN(" . implode(',', $result ?? []) . ") AND ";
        }

        return $enumeration;
    }

    // Grouping by conditions   
    // Группировка по условиям
    public static function group($grouping)
    {
        $gr = ' ';
        $os = ['github', 'blog', 'forum', 'portal', 'reference'];
        if (in_array($grouping, $os)) {
            $gr = "item_is_" . $grouping . " = 1 AND ";
        }

        return $gr;
    }

    // Получаем сайты по условиям
    // https://systemrequest.net/index.php/123/
    public static function feedItem($page, $limit, $facets, $user, $topic_id, $sort, $grouping)
    {
        $group  = self::group($grouping);
        $facets = self::facets($facets, $topic_id);
        $sort   = $facets . self::sorts($sort);

        $start  = ($page - 1) * $limit;
        $sql = "SELECT DISTINCT
                    item_id,
                    item_title,
                    item_content,
                    item_published,
                    item_user_id,
                    item_url,
                    item_domain,
                    item_votes,
                    item_date,
                    item_count,
                    item_title_soft,
                    item_is_github,
                    item_github_url,
                    item_following_link,
                    item_is_deleted,
                    rel.*,
                    votes_item_user_id, votes_item_item_id,
                    fav.tid, fav.user_id, fav.action_type, 
                    u.id, u.login, u.avatar
  
                        FROM facets_items_relation 
                        LEFT JOIN items ON relation_item_id = item_id
                        LEFT JOIN (
                            SELECT 
                                relation_item_id,
                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets
                                LEFT JOIN facets_items_relation on facet_id = relation_facet_id
                                    GROUP BY relation_item_id
                        ) AS rel
                            ON rel.relation_item_id = item_id
                                LEFT JOIN users u ON u.id = item_user_id
                                LEFT JOIN favorites fav ON fav.tid = item_id AND fav.user_id = :uid AND fav.action_type = 'website'
                                LEFT JOIN votes_item ON votes_item_item_id = item_id AND votes_item_user_id = :uid_two
                                    WHERE $group $sort LIMIT :start, :limit";

        return DB::run($sql, ['uid' => $user['id'], 'uid_two' => $user['id'], 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function feedItemCount($facets, $topic_id, $sort)
    {
        $facets = self::facets($facets, $topic_id);
        $sort   = $facets . self::sorts($sort);

        $sql = "SELECT item_id FROM facets_items_relation LEFT JOIN items ON relation_item_id = item_id WHERE $sort  ";

        return DB::run($sql)->rowCount();
    }

    // Check if the domain exists 
    // Проверим наличие домена
    public static function getItemOne($domain, $user_id)
    {
        $sql = "SELECT
                    item_id,
                    item_title,
                    item_content,
                    item_title_soft,
                    item_content_soft,
                    item_published,
                    item_user_id,
                    item_modified,
                    item_url,
                    item_domain,
                    item_votes,
                    item_count,
                    item_is_soft,
                    item_is_github,
                    item_github_url,
                    item_post_related,
                    item_following_link,
                    item_close_replies,
                    item_is_deleted,
                    votes_item_user_id, votes_item_item_id,
                    rel.*,
                    fav.tid, fav.user_id, fav.action_type 
                        FROM items
                        LEFT JOIN
                        (
                            SELECT 
                                relation_item_id,
                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets  
                                LEFT JOIN facets_items_relation on facet_id = relation_facet_id
                                        GROUP BY relation_item_id
                        ) AS rel
                            ON rel.relation_item_id = item_id 
                        LEFT JOIN votes_item ON votes_item_item_id = item_id AND votes_item_user_id = :user_id
                        LEFT JOIN favorites fav ON fav.tid = item_id 
                                AND fav.user_id = :uid_two AND fav.action_type = 'website'
                        WHERE item_domain = :domain AND item_is_deleted = 0";


        return DB::run($sql, ['domain' => $domain, 'user_id' => $user_id, 'uid_two' => $user_id])->fetch();
    }

    // Add a domain
    // Добавим домен
    public static function add($params)
    {
        $sql = "INSERT INTO items(item_url, 
                            item_domain, 
                            item_title, 
                            item_content, 
                            item_published,
                            item_user_id, 
                            item_close_replies) 
                            
                       VALUES(:item_url, 
                       :item_domain, 
                       :item_title, 
                       :item_content, 
                       :item_published,
                       :item_user_id, 
                       :item_close_replies)";

        DB::run($sql, $params);

        $item_id =  DB::run("SELECT LAST_INSERT_ID() as item_id")->fetch();

        return $item_id;
    }

    public static function addItemCount($domain)
    {
        $sql = "UPDATE items SET item_count = (item_count + 1) WHERE item_domain = :domain";
        DB::run($sql, ['domain' => $domain]);
    }

    public static function edit($params)
    {
        $sql = "UPDATE items 
                    SET item_url        = :item_url,  
                    item_title          = :item_title, 
                    item_content        = :item_content,
                    item_title_soft     = :item_title_soft, 
                    item_content_soft   = :item_content_soft,
                    item_published      = :item_published,
                    item_user_id        = :item_user_id,
                    item_is_forum       = :item_is_forum,
                    item_is_portal      = :item_is_portal,
                    item_is_blog        = :item_is_blog,
                    item_is_reference   = :item_is_reference,
                    item_is_soft        = :item_is_soft,
                    item_is_github      = :item_is_github,
                    item_post_related   = :item_post_related,
                    item_close_replies  = :item_close_replies,
                    item_github_url     = :item_github_url
                        WHERE item_id   = :item_id";

        return  DB::run($sql, $params);
    }

    // Get data by id
    // Получим данные по id
    public static function getItemId($item_id)
    {
        $sql = "SELECT
                    item_id,
                    item_title,
                    item_content,
                    item_title_soft,
                    item_content_soft,
                    item_published,
                    item_user_id,
                    item_url,
                    item_domain,
                    item_votes,
                    item_count,
                    item_date,
                    item_is_forum,
                    item_is_portal,
                    item_is_blog,
                    item_is_reference,
                    item_is_soft,
                    item_is_github,
                    item_github_url,
                    item_post_related,
                    item_following_link,
                    item_close_replies,
                    item_is_deleted
                        FROM items 
                        WHERE item_id = :item_id AND item_is_deleted = 0";


        return DB::run($sql, ['item_id' => $item_id])->fetch();
    }

    // Topics by reference 
    // Темы по ссылке
    public static function getItemTopic($item_id)
    {
        $sql = "SELECT
                    facet_id id,
                    facet_title as value,
                    facet_type,
                    facet_slug
                        FROM facets  
                        INNER JOIN facets_items_relation ON relation_facet_id = facet_id
                            WHERE relation_item_id  = :item_id ";

        return DB::run($sql, ['item_id' => $item_id])->fetchAll();
    }

    // Recommended content by terms 
    // Рекомендованный контент по условиям
    public static function itemSimilars($item_id, $facet_id, $limit = 3)
    {
        $sql = "SELECT 
                    item_id,
                    item_title,
                    item_domain
                        FROM items
                            LEFT JOIN facets_items_relation on item_id = relation_item_id                        
                                WHERE item_id != :item_id 
                                    AND item_published = 1 AND item_is_deleted = 0 AND relation_facet_id = :facet_id
                                        ORDER BY item_id DESC LIMIT :limit";

        return DB::run($sql, ['item_id' => $item_id, 'facet_id' => $facet_id, 'limit' => $limit])->fetchall();
    }

    public static function setCleek($id)
    {
        $sql = "UPDATE items SET item_following_link = (item_following_link + 1) WHERE item_id = :id";

        return DB::run($sql, ['id' => $id]);
    }

    // Кто подписан на данный сайт
    public static function getFocusUsersItem($item_id)
    {
        $sql = "SELECT
                    signed_item_id,
                    signed_user_id
                        FROM items_signed
                        WHERE signed_item_id = :item_id";

        return DB::run($sql, ['item_id' => $item_id])->fetchAll();
    }
}
