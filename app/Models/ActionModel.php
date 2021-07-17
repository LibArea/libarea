<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class ActionModel extends \MainModel
{
    public static function addAudit($audit_type, $audit_user_id, $audit_content_id)
    { 
        XD::insertInto(['audits'], '(', ['audit_type'], ',', 
            ['audit_user_id'], ',',
            ['audit_content_id'], ',', 
            ['audit_read_flag'], ')')->values( '(', 
        
        XD::setList([
            $audit_type, 
            $audit_user_id,
            $audit_content_id,            
            0]), ')' )->run();
    }
    
    // Получим информацию по контенту в зависимости от типа
    public static function getInfoTypeContent($type_id, $type)
    { 
       return XD::select('*')->from([$type . 's'])->where([$type . '_id'], '=', $type_id)->getSelectOne();
    } 
 
    // Удаление / восстановление контента
    public static function setDeletingAndRestoring($type, $type_id, $status)
    { 
        if ($status == 1) {
            XD::update([$type . 's'])->set([$type . '_is_deleted'], '=', 0)->where([$type . '_id'], '=', $type_id)->run();
        } else {
            XD::update([$type . 's'])->set([$type . '_is_deleted'], '=', 1)->where([$type . '_id'], '=', $type_id)->run();
        }
    } 
    
    public static function getModerations()
    {
        $q = XD::select('*')->from(['moderations']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['mod_moderates_user_id'])
                ->leftJoin(['posts'])->on(['post_id'], '=', ['mod_post_id'])
                ->orderBy(['mod_id'])->desc()->limit(25);

        $result = $query->getSelect();

        return $result; 
    }
    
    public static function moderationsAdd($data) 
    {
       XD::insertInto(['moderations'], '(', 
            ['mod_moderates_user_id'], ',', 
            ['mod_moderates_user_tl'], ',', 
            ['mod_created_at'], ',',
            ['mod_post_id'], ',',  
            ['mod_content_id'], ',',
            ['mod_action'], ',', 
            ['mod_reason'], ')')->values( '(', 

        XD::setList([
            $data['user_id'], 
            $data['user_tl'], 
            $data['created_at'], 
            $data['post_id'], 
            $data['content_id'], 
            $data['action'], 
            $data['reason']]), ')' )->run();

        return true; 
    }
 
    // Поиск контента для форм
    public static function getSearch($search, $type)
    {   
        $field_id = $type . '_id';
        if ($type == 'post') {
            $field_name = 'post_title'; 
            $sql = "SELECT post_id, post_title, post_is_deleted, post_tl FROM posts WHERE post_title LIKE :post_title AND post_is_deleted = 0 AND post_tl = 0 ORDER BY post_id LIMIT 8";
            
        } elseif ($type == 'topic') {
            
            $field_name = 'topic_title';
            $sql = "SELECT topic_id, topic_title FROM topic 
                    WHERE topic_title LIKE :topic_title ORDER BY topic_id LIMIT 8";
            
        } elseif ($type == 'main') {
            $field_id = 'topic_id';
            $field_name = 'topic_title';
            $sql = "SELECT topic_id, topic_title FROM topic 
                    WHERE topic_is_parent !=0 AND topic_title LIKE :topic_title ORDER BY topic_id LIMIT 8";
            
        } else {
            $field_id = 'id';
            $field_name = 'login';
            $sql = "SELECT id, login FROM users WHERE login LIKE :login";
        }
        
        $result = DB::run($sql, [$field_name => $search."%"]);
        $lists  = $result->fetchall(PDO::FETCH_ASSOC);
    
        $response = array();
        foreach ($lists as $list) {
           $response[] = array(
              "id" => $list[$field_id],
              "text" => $list[$field_name]
           );
        }

        echo json_encode($response);
    }
 
}
