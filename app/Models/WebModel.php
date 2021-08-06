<?php

namespace App\Models;

use DB;
use PDO;

class WebModel extends \MainModel
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
                    votes_link_user_id, 
                    votes_link_item_id
                        FROM links 
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
}
