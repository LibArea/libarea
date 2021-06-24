<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class LinkModel extends \MainModel
{
    // Список доменов
    public static function getDomainAll($user_id, $page)
    {
        $q = XD::select('*')->from(['links']);
        $query = $q->leftJoin(['votes_link'])->on(['votes_link_item_id'], '=', ['link_id'])
                ->and(['votes_link_user_id'], '=', $user_id)->where(['link_is_deleted'], '=', 0);
        
        return $query->getSelect(); 
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
    
    // 5 популярных доменов
    public static function getDomainsTop($domain)
    {
        return XD::select('*')->from(['links'])->where(['link_url_domain'], '!=', $domain)
                ->and(['link_is_deleted'], '=', 0)->orderBy(['link_count'])->desc()->limit(10)->getSelect();
    } 

    // Данные по id домена
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
            '',
            '',
            $data['link_user_id'],
            $data['link_type'],
            $data['link_status'],
            $data['link_cat_id'],
            1]), ')' )->run();

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
    public static function getLinkOne($domain)
    {
        return XD::select('*')->from(['links'])->where(['link_url_domain'], '=', $domain)->getSelectOne();
    }
    
    // Данные о домене
    public static function getLink($domain, $user_id)
    {
        $q = XD::select('*')->from(['links']);
        $query = $q->leftJoin(['votes_link'])->on(['votes_link_item_id'], '=', ['link_id'])
                ->and(['votes_link_user_id'], '=', $user_id)->where(['link_url_domain'], '=', $domain);
        
        return $query->getSelectOne();
    }   
    
 
}