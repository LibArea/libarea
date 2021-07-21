<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class WebModel extends \MainModel
{
    // Все сайты
    public static function getLinksAll($page, $limit, $user_id)
    {
        $start  = ($page-1) * $limit;
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
    
    // 5 популярных доменов
    public static function getLinksTop($domain)
    {
        return XD::select('*')->from(['links'])->where(['link_url_domain'], '!=', $domain)
                ->and(['link_is_deleted'], '=', 0)->orderBy(['link_count'])->desc()->limit(10)->getSelect();
    }
    
    // Данные по id
    public static function getLinkId($link_id)
    {
        return XD::select('*')->from(['links'])->where(['link_id'], '=', $link_id)->getSelectOne();
    } 

    // Добавим домен
    public static function addLink($data)
    {
        XD::insertInto(['links'], '(', 
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
        return XD::select()->last_insert_id('()')->getSelectValue(); 
    }
    
    // Количество в системе 
    public static function addLinkCount($domain)
    {
        $sql = "UPDATE links SET link_count = (link_count + 1) WHERE link_url_domain = :domain";
        DB::run($sql, ['domain' => $domain]); 
    }
    
    // Проверим наличие домена
    public static function getLinkOne($domain, $user_id)
    {
        $q = XD::select('*')->from(['links']);
        $query = $q->leftJoin(['votes_link'])->on(['votes_link_item_id'], '=', ['link_id'])
                ->and(['votes_link_user_id'], '=', $user_id)->where(['link_url_domain'], '=', $domain);
        
        return $query->getSelectOne();
    }
    
    // Изменим домен
    public static function editLink($data)
    {
        XD::update(['links'])->set(['link_url'], '=', $data['link_url'], ',', 
            ['link_title'], '=', $data['link_title'], ',',
            ['link_content'], '=', $data['link_content'])->where(['link_id'], '=', $data['link_id'])->run(); 
        return true;  
    }
}