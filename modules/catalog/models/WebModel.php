<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class WebModel extends Model
{
    static $limit = 15;

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

    /**
     * Grouping by conditions  
     * Группировка по условиям
     *
     * @param string $grouping
     * @return void
     */
    public static function group(string|null|bool $grouping)
    {
        $gr = ' ';
        $os = config('main', 'type');
        if (in_array($grouping, $os)) {
            $gr = "item_is_" . $grouping . " = 1 AND ";
        }

        return $gr;
    }

    // Получаем сайты по условиям
    public static function feedItem($facets, $topic_id, $page, $sort, $grouping)
    {
        $user_id = self::container()->user()->id();
        
        $group  = self::group($grouping);
        $facets = self::facets($facets, $topic_id);
        $sort   = $facets . self::sorts($sort);

        $start  = ($page - 1) * self::$limit;
        $sql = "SELECT DISTINCT
                    item_id,
                    item_title,
                    item_content,
                    item_slug,
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
                    item_telephone,
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

        return DB::run($sql, ['uid' => $user_id, 'uid_two' => $user_id, 'start' => $start, 'limit' => self::$limit])->fetchAll();
    }

    public static function feedItemCount($facets, $facet_id, $sort, $grouping)
    {
        $group  = self::group($grouping);
        $facets = self::facets($facets, $facet_id);
        $sort   = $facets . self::sorts($sort);

        $sql = "SELECT item_id FROM facets_items_relation LEFT JOIN items ON relation_item_id = item_id WHERE $group $sort ";

        return DB::run($sql)->rowCount();
    }

    /**
     * Get a list of sites by facet id. We will get characteristic features for each site.
     * It makes no sense to show a label for sorting (by attribute) if there is not at least 1 site in this facet
     * Получим список сайтов по id фасету. У каждого сайта получим характерные признаки.
     * Нет смысла показывать метку для сортировки (по признаку), если в данном фасете нет хотя бы 1 сайта
     *
     * @param integer $facet_id
     * @return void
     */
    public static function getTypesWidget(int $facet_id)
    {
        $sql = "SELECT 
                    SUM(item_is_github) as github,
                    SUM(item_is_forum) as forum,
                    SUM(item_is_portal) as portal,
                    SUM(item_is_reference) as reference,
                    SUM(item_is_goods) as goods,
                    SUM(item_is_blog) as blog
                       FROM facets_items_relation 
                           LEFT JOIN items ON relation_item_id = item_id 
                                WHERE relation_facet_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    /**
     * Detailed information on the id
     * Детальная информация по id
     *
     * @param integer $id
     * @return mixed
     */
    public static function getItemId(int $id)
    {
        $user_id = self::container()->user()->id();
        $sql = "SELECT
                    item_id,
                    item_title,
                    item_content,
                    item_url,
                    item_slug,
                    item_title_soft,
                    item_content_soft,
                    item_published,
                    item_user_id,
                    item_modified,
                    item_domain,
                    item_votes,
                    item_count,
                    item_is_soft,
                    item_is_github,
                    item_github_url,
                    item_post_related,
                    item_following_link,
                    item_close_replies,
                    item_poll,
                    item_date,
                    item_is_portal,
                    item_is_forum,
                    item_is_blog,
                    item_is_reference,
                    item_is_goods,
                    item_telephone,
                    item_email,
                    item_vk,
                    item_telegram,
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
                        WHERE item_id = :id AND item_is_deleted = 0";

        return DB::run($sql, ['id' => $id, 'user_id' => $user_id, 'uid_two' => $user_id])->fetch();
    }

    /**
     * Add a domain
     * Добавим домен
     *
     * @param array $params
     * @return mixed
     */
    public static function add(array $params)
    {
        $sql = "INSERT INTO items(item_url, 
                            item_domain, 
                            item_title, 
                            item_content, 
                            item_slug,
                            item_published,
                            item_user_id, 
                            item_poll,
                            item_close_replies) 
                            
                       VALUES(:item_url, 
                       :item_domain, 
                       :item_title, 
                       :item_content, 
                       :item_slug,
                       :item_published,
                       :item_user_id,
                       :item_poll,                        
                       :item_close_replies)";

        DB::run($sql, $params);

        $item_id =  DB::run("SELECT LAST_INSERT_ID() as item_id")->fetch();

        return $item_id;
    }

    public static function edit($params)
    {
        $sql = "UPDATE items 
                    SET item_url        = :item_url,  
                    item_domain         = :item_domain,
                    item_title          = :item_title, 
                    item_content        = :item_content,
                    item_slug           = :item_slug,
                    item_title_soft     = :item_title_soft, 
                    item_content_soft   = :item_content_soft,
                    item_published      = :item_published,
                    item_user_id        = :item_user_id,
                    item_is_forum       = :item_is_forum,
                    item_is_portal      = :item_is_portal,
                    item_is_blog        = :item_is_blog,
                    item_is_reference   = :item_is_reference,
                    item_is_goods       = :item_is_goods,
                    item_is_soft        = :item_is_soft,
                    item_is_github      = :item_is_github,
                    item_post_related   = :item_post_related,
                    item_close_replies  = :item_close_replies,
                    item_github_url     = :item_github_url,
                    item_telephone      = :item_telephone,
                    item_email          = :item_email,
                    item_vk             = :item_vk,
                    item_poll           = :item_poll,
                    item_telegram       = :item_telegram
                        WHERE item_id   = :item_id";

        return  DB::run($sql, $params);
    }

    /**
     * Topics by reference 
     * Темы по ссылке
     *
     * @param [type] $item_id
     */
    public static function getItemTopic($item_id): false|array
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

    /**
     * Recommended content by terms 
     * Рекомендованный контент по условиям
     *
     * @param integer $item_id
     * @param integer $facet_id
     * @param integer $limit
     */
    public static function itemSimilars(int $item_id, int $facet_id, int $limit = 3): false|array
    {
        $sql = "SELECT 
                    item_id,
                    item_title,
                    item_slug,
                    item_url
                        FROM items
                            LEFT JOIN facets_items_relation on item_id = relation_item_id                        
                                WHERE item_id != :item_id 
                                    AND item_published = 1 AND item_is_deleted = 0 AND relation_facet_id = :facet_id
                                        ORDER BY item_id DESC LIMIT :limit";

        return DB::run($sql, ['item_id' => $item_id, 'facet_id' => $facet_id, 'limit' => $limit])->fetchall();
    }

    public static function setCleek(int $id)
    {
        return DB::run("UPDATE items SET item_following_link = (item_following_link + 1) WHERE item_id = :id", ['id' => $id]);
    }

    public static function getFocusUsersItem(int $item_id)
    {
        return DB::run("SELECT signed_item_id, signed_user_id FROM items_signed WHERE signed_item_id = :item_id", ['item_id' => $item_id])->fetchAll();
    }

    public static function getDomains(string $url)
    {
        $sql = "SELECT item_id, item_title, item_url, item_slug, item_domain, rel.*
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
                                ON rel.relation_item_id = item_id WHERE item_domain = :url";

        return DB::run($sql, ['url' => $url])->fetchAll();
    }

    public static function getHost(string $host)
    {
        $sql = "SELECT item_id, item_title, item_url, item_slug, item_domain FROM items WHERE item_url LIKE :host LIMIT 5";

        return DB::run($sql, ['host' => "%" . $host . "%"])->fetchAll();
    }

    public static function getSlug(string $slug)
    {
        return DB::run("SELECT item_slug FROM items WHERE item_slug = :slug", ['slug' => $slug])->fetch();
    }
    
    public static function getComments(int $limit)
    {
        $sql = "SELECT 
                    reply_id,
                    reply_user_id,                
                    reply_item_id,
                    reply_parent_id,
                    reply_content as content,
                    reply_date as date,
                    item_id,
                    item_slug,
                    id, 
                    login, 
                    avatar
                        FROM replys 
                          LEFT JOIN users ON id = reply_user_id
                          LEFT JOIN items ON item_id = reply_item_id
                              ORDER BY reply_id DESC LIMIT :limit";

        return DB::run($sql, ['limit' => $limit])->fetchAll();
    }
    
    public static function getForStatus()
    {
        $url = DB::run("SELECT status_item_id FROM items_status WHERE status_date > CURDATE()-INTERVAL 1 WEEK")->fetchAll();
        
        $result = [];
        foreach ($url as $ind => $row) {
            $result[$ind] = $row['status_item_id'];
        }
        
        $not = $result ? "AND item_id NOT IN(" . implode(',', $result ?? 0) . ")" : '';
        
        $sql = "SELECT item_id, item_url FROM items WHERE item_is_deleted = 0 $not ORDER BY item_id LIMIT 30";
        
        return DB::run($sql)->fetchAll();
    }
    
    public static function statusUpdate(int $item_id, string $code)
    {
        return DB::run("INSERT INTO items_status(status_item_id,status_response) VALUES(:item_id, :code)", ['item_id' => $item_id, 'code' => $code]);
    }
    
    public static function getIdStatus(int $id)
    {
        $sql = "SELECT status_response, status_date FROM items_status WHERE status_item_id = :id ORDER BY status_item_id DESC LIMIT :start, :limit";
        
        return DB::run($sql, ['id' => $id, 'start' => 0, 'limit' => self::$limit])->fetchAll();
    }
    
    public static function getStatus(int $page, int $code)
    {    
        $code = $code ? $code : 404;
        $start = ($page - 1) * self::$limit;

        $sql = "SELECT item_id, item_url, item_slug, rel.* 
                    FROM items 
                       LEFT JOIN (
                            SELECT 
                                status_item_id, MAX(status_response) as st, 
                                GROUP_CONCAT(status_response, '@', status_date SEPARATOR '@') AS status_list
                                    FROM items_status GROUP BY status_item_id
                        ) AS rel
                             ON rel.status_item_id = item_id
                                WHERE rel.st = :code AND item_is_deleted = 0 ORDER BY item_id DESC LIMIT :start, :limit";
        
        return DB::run($sql, ['code' => $code, 'start' => $start, 'limit' => self::$limit])->fetchAll();
    }
}
