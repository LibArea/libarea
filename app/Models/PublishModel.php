<?php

namespace App\Models;
use XdORM\XD;

class PublishModel extends \MainModel
{
    public static function addPost($data)
    {
        // Проверить пост на повтор slug (переделать)
        $result = XD::select('*')->from(['posts'])->where(['post_slug'], '=', $data['post_slug'])->getSelectOne();
        
        if ($result) {
            $data['post_slug'] =  $data['post_slug'] . "-";
        }
           
        // toString  строковая заменя для проверки
        XD::insertInto(['posts'], '(', 
            ['post_title'], ',', 
            ['post_content'], ',', 
            ['post_content_img'], ',',  
            ['post_thumb_img'], ',',
            ['post_related'], ',',
            ['post_merged_id'], ',',
            ['post_tl'], ',',
            ['post_slug'], ',', 
            ['post_type'], ',',
            ['post_translation'], ',',
            ['post_draft'], ',',
            ['post_ip_int'], ',', 
            ['post_published'], ',', 
            ['post_user_id'], ',', 
            ['post_space_id'], ',', 
            ['post_closed'], ',',
            ['post_top'], ',',
            ['post_url'], ',',
            ['post_url_domain'],')')->values( '(', 
        
        XD::setList([
            $data['post_title'], 
            $data['post_content'], 
            $data['post_content_img'],
            $data['post_thumb_img'],
            $data['post_related'],
            $data['post_merged_id'],
            $data['post_tl'],            
            $data['post_slug'],
            $data['post_type'],
            $data['post_translation'],
            $data['post_draft'],
            $data['post_ip_int'],  
            $data['post_published'],
            $data['post_user_id'], 
            $data['post_space_id'], 
            $data['post_closed'],
            $data['post_top'],
            $data['post_url'],
            $data['post_url_domain']]), ')' )->run();

        // id поста
        return  XD::select()->last_insert_id('()')->getSelectValue();
    } 

    public static function addAnswer($data)
    { 
        XD::insertInto(['answers'], '(', ['answer_post_id'], ',', 
            ['answer_content'], ',', 
            ['answer_published'], ',', 
            ['answer_ip'], ',', 
            ['answer_user_id'], ')')->values( '(', 
        
        XD::setList([
            $data['answer_post_id'], 
            $data['answer_content'], 
            $data['answer_published'],
            $data['answer_ip'], 
            $data['answer_user_id']]), ')' )->run();
       
       return XD::select()->last_insert_id('()')->getSelectValue();
    }
   
    public static function addComment($data)
    { 
        XD::insertInto(['comments'], '(', ['comment_post_id'], ',', 
            ['comment_answer_id'], ',', 
            ['comment_comment_id'], ',', 
            ['comment_content'], ',', 
            ['comment_published'], ',', 
            ['comment_ip'], ',', 
            ['comment_user_id'], ')')->values( '(', 
        
        XD::setList([
            $data['comment_post_id'], 
            $data['comment_answer_id'], 
            $data['comment_comment_id'], 
            $data['comment_content'], 
            $data['comment_published'],
            $data['comment_ip'],    
            $data['comment_user_id']]), ')' )->run();
       
       $last_id = XD::select()->last_insert_id('()')->getSelectValue();
       
       // Отмечаем комментарий, что за ним есть ответ
       XD::update(['comments'])->set(['comment_after'], '=', $last_id)->where(['comment_id'], '=', $data['comment_comment_id'])->run();

       $answer = XD::select('*')->from(['answers'])->where(['answer_id'], '=', $data['comment_answer_id'])->getSelectOne();
       if ($answer['answer_after'] == 0) {
            // Отмечаем ответ, что за ним есть комментарии
            XD::update(['answers'])->set(['answer_after'], '=', $last_id)->where(['answer_id'], '=', $data['comment_answer_id'])->run();   
       }

       return $last_id; 
    }
    
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
 
}
