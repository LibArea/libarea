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
    public static function getPostHome($page, $space_user)
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
        
        $offset = ($page-1) * 10; 

        $sql = "SELECT p.post_id, p.post_title, p.post_slug, p.post_user_id, p.post_space_id, p.post_comments, p.post_date, p.post_votes,
                u.id, u.login, u.avatar,
                s.space_id, s.space_slug, s.space_name, space_tip
                fROM posts as p
                INNER JOIN users as u ON u.id = p.post_user_id
                INNER JOIN space as s ON s.space_id = p.post_space_id
                WHERE p.post_space_id NOT IN(".$string.")
                ORDER BY p.post_id DESC LIMIT 10 OFFSET ".$offset." ";
                        
        
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
        $result = ceil(count($query) / 10);

        return $result;

    }


    // TOP посты на главной 
    public static function getPostTop()
    {

        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['users.id'], '=', ['post_user_id'])
                ->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                ->orderBy(['post_comments'])->desc();

        $result = $query->getSelect();

        return $result;

    }
    
    // Полная версия поста  
    public static function getPost($slug)
    {

        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                 ->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                 ->where(['post_slug'], '=', $slug);
        
        $result = $query->getSelectOne();
        
        return $result;

    }   
    
    // Страница постов участника
    public static function getUsersPosts($login)
    {
        
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                ->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])
                ->where(['login'], '=', $login);
  
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
    public static function addPost($post_title, $post_content, $post_slug, $post_ip_int, $post_user_id, $space_id)
    {
       
        // Проверить пост на повтор slug (переделать)
        $q = XD::select('*')->from(['posts']);
        $query = $q->where(['post_slug'], '=', $post_slug);
        $result = $query->getSelectOne();
        
        if ($result) {
            $post_slug =  $post_slug . "-";
        }
       
        // toString  строковая заменя для проверки
        XD::insertInto(['posts'], '(', ['post_title'], ',', ['post_content'], ',', ['post_slug'], ',', ['post_ip_int'], ',', ['post_user_id'],',', ['post_space_id'], ')')->values( '(', XD::setList([$post_title, $post_content, $post_slug, $post_ip_int, $post_user_id, $space_id]), ')' )->run();

        return true; 
       
    } 
    
    // Получаем пост по id
    public static function getPostId($id) 
    {
      
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['space'])->on(['space_id'], '=', ['post_space_id'])->where(['post_id'], '=', $id);
        $result = $query->getSelectOne();
       
        return $result;
      
    }
    
    // Редактирование поста
    public static function editPost($post_id, $post_title, $post_content)
    {
        $edit_date = date("Y-m-d H:i:s");
       
        XD::update(['posts'])->set(['post_title'], '=', $post_title, ',', ['edit_date'], '=', $edit_date, ',', ['post_content'], '=', $post_content)
        ->where(['post_id'], '=', $post_id)->run();
 
        return true;
    }
}
