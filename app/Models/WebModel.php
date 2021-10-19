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
                                MAX(topic_id), 
                                MAX(topic_slug), 
                                MAX(topic_title),
                                MAX(relation_topic_id), 
                                relation_link_id,

                                GROUP_CONCAT(topic_slug, '@', topic_title SEPARATOR '@') AS topic_list
                                FROM topics  
                                LEFT JOIN topics_link_relation 
                                    on topic_id = relation_topic_id
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
                    link_user_id,
                    link_url,
                    link_url_domain,
                    link_votes,
                    link_count,
                    link_is_deleted
                        FROM links 
                        WHERE link_url_domain != :domain AND link_is_deleted = 0 
                        ORDER BY link_count DESC LIMIT 10";

        return DB::run($sql, ['domain' => $domain])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Получаем домены по условиям
    public static function feedLink($page, $limit, $uid, $sheet, $slug)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    link_id,
                    link_title,
                    link_content,
                    link_user_id,
                    link_url,
                    link_url_domain,
                    link_votes,
                    link_count,
                    link_is_deleted,
                    rel.*,
                    votes_link_item_id, votes_link_user_id,
                    user_id, user_login, user_avatar
                    
                        FROM links
                        LEFT JOIN
                        (
                            SELECT 
                                MAX(topic_id), 
                                MAX(topic_slug), 
                                MAX(topic_title),
                                MAX(relation_topic_id), 
                                relation_link_id,

                                GROUP_CONCAT(topic_slug, '@', topic_title SEPARATOR '@') AS topic_list
                                FROM topics      
                                LEFT JOIN topics_link_relation 
                                    on topic_id = relation_topic_id
                                GROUP BY relation_link_id  
                        ) AS rel
                            ON rel.relation_link_id = link_id 
                            
                            INNER JOIN users ON user_id = link_user_id
                            LEFT JOIN votes_link ON votes_link_item_id = link_id 
                                AND votes_link_user_id = " . $uid['user_id'] . "
                                WHERE topic_list LIKE :slug     
                                LIMIT $start, $limit";

 
        return DB::run($sql, ['slug' => "%" . $slug . "%"])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function feedLinkCount($slug)
    {
        $sql = "SELECT 
                    link_id,
                    rel.*
                        FROM links
                        LEFT JOIN
                        (
                            SELECT 
                                MAX(topic_id), 
                                MAX(topic_slug), 
                                MAX(topic_title),
                                MAX(relation_topic_id), 
                                relation_link_id,

                                GROUP_CONCAT(topic_slug, '@', topic_title SEPARATOR '@') AS topic_list
                                FROM topics      
                                LEFT JOIN topics_link_relation 
                                    on topic_id = relation_topic_id
                                GROUP BY relation_link_id  
                        ) AS rel
                            ON rel.relation_link_id = link_id 
                            WHERE topic_list LIKE :slug";

         return DB::run($sql, ['slug' => "%" . $slug . "%"])->rowCount();
    }

    // Проверим наличие домена
    public static function getLinkOne($domain, $user_id)
    {
        $sql = "SELECT
                    link_id,
                    link_title,
                    link_content,
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
            'link_user_id'      => $data['link_user_id'],
            'link_type'         => $data['link_type'],
            'link_status'       => $data['link_status'],
            'link_count'        => 1,
        ];

        $sql = "INSERT INTO links(link_url, 
                            link_url_domain, 
                            link_title, 
                            link_content, 
                            link_user_id, 
                            link_type, 
                            link_status, 
                            link_count) 
                            
                       VALUES(:link_url, 
                       :link_url_domain, 
                       :link_title, 
                       :link_content, 
                       :link_user_id, 
                       :link_type, 
                       :link_status, 
                       :link_count)";

        return  DB::run($sql, $params);
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
            'link_status'   => $data['link_status'],
            'link_id'       => $data['link_id'],
        ];

        $sql = "UPDATE links 
                    SET link_url    = :link_url,  
                    link_title      = :link_title, 
                    link_content    = :link_content,
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
    
    public static function getLinkTopic($link_id)
    {
        $sql = "SELECT
                    topic_id,
                    topic_title,
                    topic_slug,
                    relation_topic_id,
                    relation_link_id
                        FROM topics  
                        INNER JOIN topics_link_relation ON relation_topic_id = topic_id
                            WHERE relation_link_id  = :link_id";

        return DB::run($sql, ['link_id' => $link_id])->fetchAll(PDO::FETCH_ASSOC);
    }

}
