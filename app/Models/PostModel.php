<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;
use Base;

class PostModel extends \MainModel
{
    // Посты на главной 
    // $page - страницы
    // $tags_user - список id отписанных тегов
    public static function getPostHome($page, $space_user, $trust_level, $uid)
    {
        $result = Array();
        foreach($space_user as $ind => $row){
            $result[$ind] = $row['signed_space_id'];
        } 
        
        // Временное решение
        // Мы должны сформировать список пространств по умолчанию (в config)
        // и добавить условие показа постов, рейтинг которых достигает > N+ значения
        // в первый час размещения, но не вошедшие в пространства по умолчанию к показу
        if($uid == 0) {
           $string = '';
        } else {
            if($result) {
                $string = "WHERE p.post_space_id IN(1, ".implode(',', $result).")";
            } else {
               $string = "WHERE p.post_space_id IN(1)"; 
            }
        }        

        $offset = ($page-1) * 15; 
        
        // Показывать удаленный пост и для персонала
        if($trust_level != 5) { 
            $display = 'AND p.post_is_delete  = 0';
        } else {
            $display = '';
        }

        $sql = "SELECT p.post_id, p.post_title, p.post_slug, p.post_user_id, p.post_space_id, p.post_answers_num, p.post_date, p.post_votes, p.post_is_delete, p.post_closed, p.post_lo, p.post_top, p.post_url, p.post_content_preview, p.post_content_img,
                u.id, u.login, u.avatar,
                v.votes_post_item_id, v.votes_post_user_id,  
                s.space_id, s.space_slug, s.space_name, space_color
                fROM posts as p
                INNER JOIN users as u ON u.id = p.post_user_id
                INNER JOIN space as s ON s.space_id = p.post_space_id
                LEFT JOIN votes_post as v ON v.votes_post_item_id = p.post_id AND v.votes_post_user_id = ".$uid."
                $string
                $display
                ORDER BY p.post_id DESC LIMIT 15 OFFSET ".$offset." ";
    
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Количество постов
    public static function getPostHomeCount($space_user, $uid)
    {
        $result = Array();
        foreach($space_user as $ind => $row){
            $result[$ind] = $row['signed_space_id'];
        }   
        
        if($uid == 0) {
           $string = '';
        } else {
            if($result) {
                $string = "WHERE p.post_space_id IN(1, ".implode(',', $result).")";
            } else {
               $string = "WHERE p.post_space_id IN(1)"; 
            }
        } 
     
        $sql = "SELECT p.post_id, p.post_space_id, s.space_id
                fROM posts as p
                INNER JOIN space as s ON s.space_id = p.post_space_id
                $string ";

        $query = DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
        $result = ceil(count($query) / 15);

        return $result;
    }

    // TOP посты на главной 
    public static function getPostTop($uid)
    {
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['users.id'], '=', ['post_user_id'])
                ->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                ->leftJoin(['votes_post'])->on(['votes_post_item_id'], '=', ['post_id'])
                ->and(['votes_post_user_id'], '=', $uid)
                ->where(['post_is_delete'], '=', 0)
                ->orderBy(['post_answers_num'])->desc();

        return $query->getSelect();
    }
    
    // Количество TOP постов
    public static function getPostTopCount()
    {
        $sql = "SELECT p.post_id, p.post_space_id, s.space_id
                fROM posts as p
                INNER JOIN space as s ON s.space_id = p.post_space_id ";

        $query = DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
        $result = ceil(count($query) / 15);

        return $result;
        
    }
    
    // Полная версия поста  
    public static function getPost($slug, $uid)
    {
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                 ->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                ->leftJoin(['votes_post'])->on(['votes_post_item_id'], '=', ['post_id'])
                ->and(['votes_post_user_id'], '=', $uid)
                 ->where(['post_slug'], '=', $slug);
        
        return $query->getSelectOne();
    }   
    
    // Рекомендованные посты
    public static function PostsSimilar($post_id, $space_id, $uid) 
    {
        $q = XD::select('*')->from(['posts']);
        $query = $q->where(['post_id'], '<', $post_id)
        ->and(['post_space_id'], '=', $space_id) // из пространства
        ->and(['post_is_delete'], '=', 0)        // не удален
        ->and(['post_user_id'], '!=', $uid)      // не участника, который смотрит
        ->orderBy(['post_id'])->desc()->limit(5);
        
        return $query->getSelect();
    }
    
    // Страница постов участника
    public static function getUsersPosts($login, $uid)
    {
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                ->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                ->leftJoin(['votes_post'])->on(['votes_post_item_id'], '=', ['post_id'])
                ->and(['votes_post_user_id'], '=', $uid)
                ->where(['login'], '=', $login)
                ->and(['post_is_delete'], '=', 0)
                ->orderBy(['post_id'])->desc();
  
        return $query->getSelect();
    } 
    
    // Пересчитываем количество комментариев в посте
    public static function getNumComments($post_id) 
    {
        $post = XD::select('*')->from(['posts'])->where(['post_id'], '=', $post_id)->getSelectOne();
        $post_comments_num = $post['post_comments_num']; // получаем количество ответов
        $new_num = $post_comments_num + 1;           // плюсуем один
        
        XD::update(['posts'])->set(['post_comments_num'], '=', $new_num)->where(['post_id'], '=', $post_id)->run();
     
        return true;
    }
    
    // Пересчитываем количество ответов в посте
    public static function getNumAnswers($post_id) 
    {
        $post = XD::select('*')->from(['posts'])->where(['post_id'], '=', $post_id)->getSelectOne();
        $post_answers_num = $post['post_answers_num']; // получаем количество ответов
        $new_num = $post_answers_num + 1;           // плюсуем один
        
        XD::update(['posts'])->set(['post_answers_num'], '=', $new_num)->where(['post_id'], '=', $post_id)->run();
     
        return true;
    }
    
    // Добавляем пост и проверяем uri
    public static function addPost($data)
    {
        // Проверить пост на повтор slug (переделать)
        $q = XD::select('*')->from(['posts']);
        $query = $q->where(['post_slug'], '=', $data['post_slug']);
        $result = $query->getSelectOne();
        
        if ($result) {
            $data['post_slug'] =  $data['post_slug'] . "-";
        }
           
        // toString  строковая заменя для проверки
        XD::insertInto(['posts'], '(', 
            ['post_title'], ',', 
            ['post_content'], ',', 
            ['post_content_preview'], ',', 
            ['post_content_img'], ',',  
            ['post_thumb_img'], ',',
            ['post_slug'], ',', 
            ['post_ip_int'], ',', 
            ['post_user_id'], ',', 
            ['post_space_id'], ',', 
            ['post_tag_id'], ',',
            ['post_closed'], ',',
            ['post_top'], ',',
            ['post_url'], ',',
            ['post_url_domain'],')')->values( '(', 
        
        XD::setList([
            $data['post_title'], 
            $data['post_content'], 
            $data['post_content_preview'], 
            $data['post_content_img'],
            $data['post_thumb_img'],            
            $data['post_slug'], 
            $data['post_ip_int'], 
            $data['post_user_id'], 
            $data['post_space_id'], 
            $data['post_tag_id'], 
            $data['post_closed'],
            $data['post_top'],
            $data['post_url'],
            $data['post_url_domain']]), ')' )->run();

        return true; 
    } 
    
    // Получаем пост по id
    public static function getPostId($id) 
    {
        if(!$id) { $id = 0; }  
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])->where(['post_id'], '=', $id);
        
        return $query->getSelectOne();
    }
    
    // Редактирование поста
    public static function editPost($data)
    {
        $edit_date = date("Y-m-d H:i:s"); 
       
        XD::update(['posts'])->set(
            ['post_title'], '=', $data['post_title'], ',', 
            ['edit_date'], '=', $edit_date, ',', 
            ['post_content'], '=', $data['post_content'], ',', 
            ['post_content_preview'], '=', $data['post_content_preview'], ',', 
            ['post_content_img'], '=', $data['post_content_img'], ',', 
            ['post_closed'], '=', $data['post_closed'], ',', 
            ['post_top'], '=', $data['post_top'], ',', 
            ['post_space_id'], '=', $data['post_space_id'], ',', 
            ['post_tag_id'], '=', $data['post_tag_id'], ',',
            ['post_url'], '=', $data['post_url'])
            ->where(['post_id'], '=', $data['post_id'])->run();

        return true;
    }
    
    // Добавить пост в профиль
    public static function addPostProfile($post_id, $uid)
    {
        XD::update(['users'])->set(['my_post'], '=', $post_id)
        ->where(['id'], '=', $uid)->run();
 
        return true;
    }
  
    // Добавить пост в закладки
    public static function setPostFavorite($post_id, $uid)
    {
        $result = self::getMyPostFavorite($post_id, $uid); 

        if(!$result){
           XD::insertInto(['favorite'], '(', ['favorite_tid'], ',', ['favorite_uid'], ',', ['favorite_type'], ')')->values( '(', XD::setList([$post_id, $uid, 1]), ')' )->run();
        } else {
           XD::deleteFrom(['favorite'])->where(['favorite_tid'], '=', $post_id)->and(['favorite_uid'], '=', $uid)->run(); 
        } 
        
        return true;
    }
  
    // Пост в закладках или нет
    public static function getMyPostFavorite($post_id, $uid) 
    {
        $result = XD::select('*')->from(['favorite'])->where(['favorite_tid'], '=', $post_id)
        ->and(['favorite_uid'], '=', $uid)
        ->and(['favorite_type'], '=', 1)
        ->getSelect();
        
        if($result) {
            return 1;
        } else {
            return false;
        }
    }
    
    // Удален пост или нет
    public static function isThePostDeleted($post_id) 
    {
        $result = XD::select('*')->from(['posts'])->where(['post_id'], '=', $post_id)->getSelectOne();
        
        return $result['post_is_delete'];
    }
    
    // Удаляем пост  
    public static function PostDelete($post_id) 
    {
        if(self::isThePostDeleted($post_id) == 1) {

            XD::update(['posts'])->set(['post_is_delete'], '=', 0)->where(['post_id'], '=', $post_id)->run();
        
        } else {
            
            XD::update(['posts'])->set(['post_is_delete'], '=', 1)->where(['post_id'], '=', $post_id)->run();
 
        }
        
        return true;
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
   
}
