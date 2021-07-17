<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class PostModel extends \MainModel
{
    // Добавляем пост
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
    
    // Полная версия поста  
    public static function getPostSlug($slug, $user_id, $trust_level)
    {
        // Ограничение по TL
        $trust_level = $trust_level;
        if ($user_id == 0) {
            $trust_level = 0; 
        }
        
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                 ->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                ->leftJoin(['votes_post'])->on(['votes_post_item_id'], '=', ['post_id'])
                ->and(['votes_post_user_id'], '=', $user_id)
                ->where(['post_slug'], '=', $slug)
                ->and(['post_tl'], '<=', $trust_level);
        
        return $query->getSelectOne();
    }   
    
    // Получаем пост по id
    public static function getPostId($id) 
    {
        if (!$id) { $id = 0; }  
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])->where(['post_id'], '=', $id);
        
        return $query->getSelectOne();
    }
    
    // Рекомендованные посты
    public static function postsSimilar($post_id, $space_id, $uid, $quantity) 
    {
        $q = XD::select('*')->from(['posts']);
        $query = $q->where(['post_id'], '<', $post_id)
        ->and(['post_space_id'], '=', $space_id) // из пространства
        ->and(['post_is_deleted'], '=', 0)        // не удален
        ->and(['post_user_id'], '!=', $uid)      // не участника, который смотрит
        ->orderBy(['post_id'])->desc()->limit($quantity);
        
        return $query->getSelect();
    }
    
    // Страница постов участника
    public static function userPosts($login, $uid)
    {
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                ->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                ->leftJoin(['votes_post'])->on(['votes_post_item_id'], '=', ['post_id'])
                ->and(['votes_post_user_id'], '=', $uid)
                ->where(['login'], '=', $login)
                ->and(['post_is_deleted'], '=', 0)
                ->and(['post_draft'], '=', 0)
                ->and(['post_tl'], '=', 0)
                ->orderBy(['post_date'])->desc();
  
        return $query->getSelect();
    } 
    
    // Пересчитываем количество
    // $type (comments / answers / hits)
    public static function updateCount($post_id, $type) 
    {
        $sql = "UPDATE posts SET post_". $type ."_count = (post_". $type ."_count + 1) WHERE post_id = :post_id";
        DB::run($sql,['post_id' => $post_id]); 
     
        return true;
    }
    
    // Редактирование поста
    public static function editPost($data)
    {
           XD::update(['posts'])->set(['post_title'], '=', $data['post_title'], ',', 
            ['post_type'], '=', $data['post_type'], ',',
            ['post_translation'], '=', $data['post_translation'], ',',
            ['post_draft'], '=', $data['post_draft'], ',',
            ['post_space_id'], '=', $data['post_space_id'], ',',
            ['post_date'], '=', $data['post_date'], ',', 
            ['post_modified'], '=', date("Y-m-d H:i:s"), ',',
            ['post_user_id'], '=', $data['post_user_id'], ',', 
            ['post_content'], '=', $data['post_content'], ',', 
            ['post_content_img'], '=', $data['post_content_img'], ',',
            ['post_related'], '=', $data['post_related'], ',', 
            ['post_merged_id'], '=', $data['post_merged_id'], ',', 
            ['post_tl'], '=', $data['post_tl'], ',', 
            ['post_closed'], '=', $data['post_closed'], ',', 
            ['post_top'], '=', $data['post_top'])
            ->where(['post_id'], '=', $data['post_id'])->run(); 

        return true;
    }
    
    // Связанные посты для поста
    public static function postRelated($post_related)
    {
        
        $sql = "SELECT post_id, post_title, post_slug, post_type, post_draft, post_related, post_is_deleted
                FROM posts WHERE post_id IN(0, ".$post_related.") AND post_is_deleted = 0 AND post_tl = 0";
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Удаление фото- заставки
    public static function setPostImgRemove($post_id)
    {
        XD::update(['posts'])->set(['post_content_img'], '=', '')->where(['post_id'], '=', $post_id)->run();
        
        return true;
    }
    
    // Добавить пост в профиль
    public static function addPostProfile($post_id, $uid)
    {
        XD::update(['users'])->set(['my_post'], '=', $post_id)
        ->where(['id'], '=', $uid)->run();
 
        return true;
    }
  
    // Удален пост или нет
    public static function isThePostDeleted($post_id) 
    {
        $result = XD::select('*')->from(['posts'])->where(['post_id'], '=', $post_id)->getSelectOne();
        
        return $result['post_is_deleted'];
    }
    
   // Частота размещения постов участника 
   public static function getPostSpeed($uid)
   {
        $sql = "SELECT post_id, post_user_id, post_date
                fROM posts 
                WHERE post_user_id = ".$uid."
                AND post_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
                
        return  DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
   }
   
    // Информация список постов
    public static function getPostTopic($post_id)
    {
        $sql = "SELECT *
                fROM topic  
                INNER JOIN topic_post_relation  ON relation_topic_id = topic_id
                WHERE relation_post_id  = ".$post_id." ";
                
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
   
}
