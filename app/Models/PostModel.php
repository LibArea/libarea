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
            $result[$ind] = $row['hidden_space_id'];
        } 
        
        if($result) {
            $string = implode(',', $result);
        } else {
            $string = 0;
        }        

        $offset = ($page-1) * 15; 
        
        // Показывать удаленный пост и для персонала
        if($trust_level != 5) { 
            $display = 'AND p.post_is_delete  = 0';
        } else {
            $display = '';
        }

        $sql = "SELECT p.post_id, p.post_title, p.post_slug, p.post_user_id, p.post_space_id, p.post_comments, p.post_date, p.post_votes, p.post_is_delete, p.post_closed, p.post_top, p.post_url, p.post_content_preview, p.post_content_img,
                u.id, u.login, u.avatar,
                v.votes_post_item_id, v.votes_post_user_id,  
                s.space_id, s.space_slug, s.space_name, space_color
                fROM posts as p
                INNER JOIN users as u ON u.id = p.post_user_id
                INNER JOIN space as s ON s.space_id = p.post_space_id
                LEFT JOIN votes_post as v ON v.votes_post_item_id = p.post_id AND v.votes_post_user_id = ".$uid."
                WHERE p.post_space_id NOT IN(".$string.")
                $display
                ORDER BY p.post_id DESC LIMIT 15 OFFSET ".$offset." ";
                        
        $result = DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
        
        return $result;

    }
    
    public static function getPostHomeCount($space_user)
    {
     
        $result = Array();
        foreach($space_user as $ind => $row){
            $result[$ind] = $row['hidden_space_id'];
        }    
        if($result) {
            $string = implode(',', $result);
        } else {
            $string = 0;
        }     
     
        $sql = "SELECT p.post_id, p.post_space_id, s.space_id
                fROM posts as p
                INNER JOIN space as s ON s.space_id = p.post_space_id
                WHERE p.post_space_id NOT IN(".$string.") ";

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
                ->orderBy(['post_comments'])->desc();

        $result = $query->getSelect();

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
        
        $result = $query->getSelectOne();
        
        return $result;

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
  
        $result = $query->getSelect();

        return $result;
    } 
    
    // Пересчитываем количество комментариев в посте
    public static function getNumComments($post_id) 
    {
        
        $post = XD::select('*')->from(['posts'])->where(['post_id'], '=', $post_id)->getSelectOne();
        $post_comments = $post['post_comments']; // получаем количество комментариев
        $new_num = $post_comments + 1;           // плюсуем один
        
        XD::update(['posts'])->set(['post_comments'], '=', $new_num)->where(['post_id'], '=', $post_id)->run();
     
        return true;
        
    }
    
    // Проверка на дубликаты uri и запись поста
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
            ['post_slug'], ',', 
            ['post_ip_int'], ',', 
            ['post_user_id'], ',', 
            ['post_space_id'], ',', 
            ['post_url'],')')->values( '(', 
        
        XD::setList([
            $data['post_title'], 
            $data['post_content'], 
            $data['post_content_preview'], 
            $data['post_content_img'], 
            $data['post_slug'], 
            $data['post_ip_int'], 
            $data['post_user_id'], 
            $data['post_space_id'], 
            $data['post_url']]), ')' )->run();

        return true; 

    } 
    
    // Получаем пост по id
    public static function getPostId($id) 
    {
      
        if(!$id) { $id = 0; }  
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])->where(['post_id'], '=', $id);
        $result = $query->getSelectOne();
       
        return $result;
      
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
        
        $result = self::getMyFavorite($post_id, $uid); 

        if(!$result){
           XD::insertInto(['favorite'], '(', ['favorite_tid'], ',', ['favorite_uid'], ')')->values( '(', XD::setList([$post_id, $uid]), ')' )->run();
            
   
        } else {
            
           XD::deleteFrom(['favorite'])->where(['favorite_tid'], '=', $post_id)->and(['favorite_uid'], '=', $uid)->run(); 

        } 
        
        return true;
    }
  
    // Пост в закладках или нет
    public static function getMyFavorite($post_id, $uid) 
    {
        $result = XD::select('*')->from(['favorite'])->where(['favorite_tid'], '=', $post_id)->and(['favorite_uid'], '=', $uid)->getSelect();
        
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
       
        $post = XD::select(['post_id', 'post_user_id', 'post_date'])->from(['posts'])
            ->where(['post_user_id'], '=', $uid)
            ->orderBy(['post_id'])->desc()->getSelectOne();
        
        // https://ru.stackoverflow.com/a/498675
        $post_date  = $post['post_date']; 
        $happy_day  = \DateTime::createFromFormat('Y-m-d H:i:s', $post_date);
        $now        = new \DateTime('now');
        $result     = $now->diff($happy_day); 
        
        return $result;
    
   }
   
}
