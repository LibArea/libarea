<?php

namespace Modules\Admin\Models;

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

    // Данные по id
    public static function getLinkId($link_id)
    {
        // return XD::select('*')->from(['links'])->where(['link_id'], '=', $link_id)->getSelectOne();
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
            'link_cat_id'       => $data['link_cat_id'],
            'link_count'        => 0,
        ];

        $sql = "INSERT INTO links(link_url, 
                        link_url_domain, 
                        link_title, 
                        link_content,
                        link_user_id,
                        link_type,
                        link_status,
                        link_cat_id,
                        link_count) 
                            VALUES(:link_url, 
                                :link_url_domain, 
                                :link_title, 
                                :link_content, 
                                :link_user_id,
                                :link_type,
                                :link_status,
                                :link_cat_id,
                                :link_count)";

        DB::run($sql, $params);

        $sql = "SELECT link_id FROM links ORDER BY link_id DESC";

        return DB::run($sql)->fetch(PDO::FETCH_ASSOC);

        /*  XD::insertInto(['links'], '(', 
            ['link_url'], ',', 
            ['link_url_domain'], ',', 
            ['link_title'], ',', 
            ['link_content'], ',',  
            ['link_user_id'], ',',
            ['link_type'], ',', 
            ['link_status'], ',',
            ['link_cat_id'], ',',
            ['link_count'],')')->values( '(', 
        
        XD::setList([
            $data['link_url'], 
            $data['link_url_domain'],
            $data['link_title'],
            $data['link_content'],
            $data['link_user_id'],
            $data['link_type'],
            $data['link_status'],
            $data['link_cat_id'],
            0]), ')' )->run();

        // id домена
        return XD::select()->last_insert_id('()')->getSelectValue(); */
    }

    // Количество в системе 
    public static function addLinkCount($domain)
    {
        $sql = "UPDATE links SET link_count = (link_count + 1) WHERE link_url_domain = :domain";

        return DB::run($sql, ['domain' => $domain]);
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

    // Изменим домен
    public static function editLink($data)
    {
        $params = [
            'link_url'      => $data['link_url'],
            'link_title'    => $data['link_title'],
            'link_content'  => $data['link_content'],
            'link_id'       => $data['link_id'],
        ];

        $sql = "UPDATE links 
                    SET link_url    = :link_url,  
                    link_title      = :link_title, 
                    link_content    = :link_content 
                        WHERE link_id  = :link_id";

        return  DB::run($sql, $params);
    }
}
