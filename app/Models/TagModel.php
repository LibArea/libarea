<?php

namespace App\Models;
use XdORM\XD;

class TagModel extends \MainModel
{
    
    // Все теги сайта
    public static function getTagHome()
    {

        $query = XD::select('*')->from(['tags']);

        $result = $query->getSelect();
 
        return $result;
 
    } 

    // По id поста теги
     public static function getTagPost($id)
    {
 
        $q = XD::select('*')->from(['tags']);
        $query = $q->leftJoin(['taggings'])->on(['taggings_tag_id'], '=', ['tags_id'])->where(['taggings_post_id'], '=', $id);
 
        $result = $query->getSelect();
 
        return $result;

    }
    
    // Списки постов по тегу
    public static function getTagPosts($tag)
    {
     
        $q = XD::select('*')->from(['tags']);
        $query = $q->leftJoin(['taggings'])->on(['taggings_tag_id'], '=', ['tags_id'])
                ->leftJoin(['posts'])->on(['post_id'], '=', ['taggings_post_id'])
                ->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                ->where(['tags_slug'], '=', $tag);
                 
        $result = $query->getSelect();

        return $result;
           
    }
    
    // Добавляем теги
    public static function TagsAddPosts($tag_id, $post_last_id)
    {
          
        XD::insertInto(['taggings'], '(', ['taggings_tag_id'], ',', ['taggings_post_id'], ')')->values( '(', XD::setList([$tag_id, $post_last_id]), ')' )->run();  
        
        return true;
        
    }
    
    // Все теги на которые отписан пользователь
    public static function getTagsUser($user_id) 
    {
        $q = XD::select('*')->from(['tagshidden'])->where(['hidden_user_id'], '=', $user_id);
        $result = $q->getSelect();

        return $result;

    }
    
    
    // Подписан пользователь на тег или нет
    public static function getMyTagHide($tag_id, $user_id) 
    {
        $q = XD::select('*')->from(['tagshidden'])->where(['hidden_tag_id'], '=', $tag_id)->and(['hidden_user_id'], '=', $user_id);
        $result = $q->getSelect();
        
        if($result) {
            return 1;
        } else {
            return false;
        }
        
    }
    
    // Добавляем теги
    public static function TagHide($tag_id, $user_id)
    {
          
        $q = XD::select('*')->from(['tagshidden'])->where(['hidden_tag_id'], '=', $tag_id)->and(['hidden_user_id'], '=', $user_id);
        $result = $q->getSelect();
        
        if(!$result){
           
            XD::insertInto(['tagshidden'], '(', ['hidden_tag_id'], ',', ['hidden_user_id'], ')')->values( '(', XD::setList([$tag_id, $user_id]), ')' )->run();             
            
        } else {
            
           XD::deleteFrom(['tagshidden'])->where(['hidden_tag_id'], '=', $tag_id)->and(['hidden_user_id'], '=', $user_id)->run(); 

        }
        
        return true;
        
    }
}
