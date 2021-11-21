<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class WebModel extends MainModel
{
    // Все сайты
    public static function getLinksAll($page, $limit, $user_id)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT
                    link_id,
                    link_title,
                    link_content,
                    link_published,
                    link_user_id,
                    link_url,
                    link_url_domain,
                    link_votes,
                    link_count,
                    link_is_deleted,
                    rel.*,
                    votes_link_user_id, 
                    votes_link_item_id
                        FROM links
                        LEFT JOIN
                        (
                            SELECT 
                                MAX(facet_id), 
                                MAX(facet_slug), 
                                MAX(facet_title),
                                MAX(facet_type),
                                MAX(relation_facet_id), 
                                relation_link_id,

                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets  
                                LEFT JOIN facets_links_relation 
                                    on facet_id = relation_facet_id
                                GROUP BY relation_link_id
                        ) AS rel
                            ON rel.relation_link_id = link_id 

                        LEFT JOIN votes_link ON votes_link_item_id = link_id AND  votes_link_user_id = $user_id
                        WHERE link_is_deleted = 0
                        ORDER BY link_id DESC LIMIT $start, $limit ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getLinksAllCount()
    {
        $sql = "SELECT link_id, link_is_deleted FROM links WHERE link_is_deleted = 0";

        return DB::run($sql)->rowCount();
    }

    // 5 популярных доменов
    public static function getLinksTop($domain)
    {
        $sql = "SELECT
                    link_id,
                    link_title,
                    link_content,
                    link_published,
                    link_user_id,
                    link_url,
                    link_url_domain,
                    link_votes,
                    link_count,
                    link_is_deleted
                        FROM links 
                        WHERE link_url_domain != :domain AND link_published = 1 AND link_is_deleted = 0 
                        ORDER BY link_count DESC LIMIT 10";

        return DB::run($sql, ['domain' => $domain])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Получаем домены по условиям
    // https://systemrequest.net/index.php/123/
    public static function feedLink($page, $limit, $facets, $uid, $topic_id, $type)
    {
        $result = [];
        foreach ($facets as $ind => $row) {
            $result['9999'] = $topic_id;
            $result[$ind] = $row['facet_id'];
        }

        $sort = "ORDER BY link_votes DESC";
        if ($type == 'new') $sort = "ORDER BY link_date DESC";

        $string = "relation_facet_id IN($topic_id)";
        if ($result) $string = "relation_facet_id IN(" . implode(',', $result ?? []) . ")";

        $start  = ($page - 1) * $limit;

        $sql = "SELECT DISTINCT
                    link_id,
                    link_title,
                    link_content,
                    link_published,
                    link_user_id,
                    link_url,
                    link_url_domain,
                    link_votes,
                    link_date,
                    link_count,
                    link_is_deleted,
                    rel.*,
                    votes_link_item_id, votes_link_user_id,
                    user_id, user_login, user_avatar
  
                        FROM facets_links_relation 
                        LEFT JOIN links ON relation_link_id = link_id
            
                        LEFT JOIN (
                            SELECT 
                                MAX(facet_id), 
                                MAX(facet_slug), 
                                MAX(facet_title),
                                MAX(relation_facet_id), 
                                MAX(relation_link_id) as l_id,
                                GROUP_CONCAT(facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets
                                LEFT JOIN facets_links_relation 
                                    on facet_id = relation_facet_id
                                    GROUP BY relation_link_id
                        ) AS rel
                             ON rel.l_id = link_id
                            LEFT JOIN users ON user_id = link_user_id
                            LEFT JOIN votes_link 
                                ON votes_link_item_id = link_id AND votes_link_user_id = :user_id

                                WHERE $string $sort LIMIT $start, $limit";

        return DB::run($sql, ['user_id' => $uid['user_id']])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function feedLinkCount($facets, $topic_id)
    {
        $result = [];
        foreach ($facets as $ind => $row) {
            $result['9999'] = $topic_id;
            $result[$ind] = $row['facet_id'];
        }

        $string = "relation_facet_id IN($topic_id)";
        if ($result) $string = "relation_facet_id IN(" . implode(',', $result ?? []) . ")";

        $sql = "SELECT DISTINCT
                    link_id,
                    link_published,
                    link_url,
                    link_is_deleted,
                    rel.*
                        FROM facets_links_relation 
                        LEFT JOIN links ON relation_link_id = link_id
            
                        LEFT JOIN (
                            SELECT 
                                MAX(facet_id), 
                                MAX(facet_slug), 
                                MAX(facet_title),
                                MAX(relation_facet_id), 
                                MAX(relation_link_id) as l_id,
                                GROUP_CONCAT(facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets
                                LEFT JOIN facets_links_relation 
                                    on facet_id = relation_facet_id
                                    GROUP BY relation_link_id
                        ) AS rel
                             ON rel.l_id = link_id

                                WHERE $string";

        return DB::run($sql)->rowCount();
    }

    // Проверим наличие домена
    public static function getLinkOne($domain, $user_id)
    {
        $sql = "SELECT
                    link_id,
                    link_title,
                    link_content,
                    link_published,
                    link_user_id,
                    link_url,
                    link_url_domain,
                    link_votes,
                    link_count,
                    link_is_deleted,
                    votes_link_user_id, 
                    votes_link_item_id
                        FROM links 
                        LEFT JOIN votes_link ON votes_link_item_id = link_id AND  votes_link_user_id = :user_id
                        WHERE link_url_domain = :domain AND link_is_deleted = 0";


        return DB::run($sql, ['domain' => $domain, 'user_id' => $user_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Добавим домен
    public static function add($data)
    {
        $params = [
            'link_url'          => $data['link_url'],
            'link_url_domain'   => $data['link_url_domain'],
            'link_title'        => $data['link_title'],
            'link_content'      => $data['link_content'],
            'link_published'    => $data['link_published'],
            'link_user_id'      => $data['link_user_id'],
            'link_type'         => $data['link_type'],
            'link_status'       => $data['link_status'],
            'link_count'        => 1,
        ];

        $sql = "INSERT INTO links(link_url, 
                            link_url_domain, 
                            link_title, 
                            link_content, 
                            link_published,
                            link_user_id, 
                            link_type, 
                            link_status, 
                            link_count) 
                            
                       VALUES(:link_url, 
                       :link_url_domain, 
                       :link_title, 
                       :link_content, 
                       :link_published,
                       :link_user_id, 
                       :link_type, 
                       :link_status, 
                       :link_count)";

        DB::run($sql, $params);

        $link_id =  DB::run("SELECT LAST_INSERT_ID() as link_id")->fetch(PDO::FETCH_ASSOC);

        return $link_id;
    }

    public static function addLinkCount($domain)
    {
        $sql = "UPDATE links SET link_count = (link_count + 1) WHERE link_url_domain = :domain";
        DB::run($sql, ['domain' => $domain]);
    }

    // Изменим домен
    public static function editLink($data)
    {
        $params = [
            'link_url'      => $data['link_url'],
            'link_title'    => $data['link_title'],
            'link_content'  => $data['link_content'],
            'link_published' => $data['link_published'],
            'link_status'   => $data['link_status'],
            'link_id'       => $data['link_id'],
        ];

        $sql = "UPDATE links 
                    SET link_url    = :link_url,  
                    link_title      = :link_title, 
                    link_content    = :link_content,
                    link_published  = :link_published,
                    link_status     = :link_status 
                        WHERE link_id  = :link_id";

        return  DB::run($sql, $params);
    }

    // Данные по id
    public static function getLinkId($link_id)
    {
        $sql = "SELECT
                    link_id,
                    link_title,
                    link_content,
                    link_published,
                    link_user_id,
                    link_url,
                    link_url_domain,
                    link_votes,
                    link_count,
                    link_status,
                    link_is_deleted
                        FROM links 
                        WHERE link_id = :link_id AND link_is_deleted = 0";


        return DB::run($sql, ['link_id' => $link_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Темы по ссылке
    public static function getLinkTopic($link_id)
    {
        $sql = "SELECT
                    facet_id,
                    facet_title,
                    facet_slug,
                    facet_is_web,
                    relation_facet_id,
                    relation_link_id
                        FROM facets  
                        INNER JOIN facets_links_relation ON relation_facet_id = facet_id
                            WHERE relation_link_id  = :link_id";

        return DB::run($sql, ['link_id' => $link_id])->fetchAll(PDO::FETCH_ASSOC);
    }
}
