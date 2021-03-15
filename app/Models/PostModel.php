<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;
use Base;

class PostModel extends \MainModel
{
    // Посты на главной без авторизации
    public static function getPostAll($page)
    {
 
        $offset = ($page-1) * 10; 

        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['users.id'], '=', ['post_user_id'])->orderBy(['post_id'])
                   ->desc()->limit(10)->offset($offset);

        $result = $query->getSelect();

        return $result;

    }
    
    public static function getPostAllCount()
    {
     
        $query= XD::select(['post_id'])->from(['posts'])->getSelect();
        $result = ceil(count($query) / 10);

        return $result;

    }
    
    // Посты на главной после авторизации 
    // $page - страницы
    // $tags_user - список id отписанных тегов
    public static function getPostFeed($page, $tags_user)
    {
 
        $result = Array();
        foreach($tags_user as $ind => $row){
            $result[$ind] = $row['hidden_tag_id'];
        }    
        $string = implode(',', $result);
        
        // print_r($string); toString
        //WHERE tags_id NOT IN(2,... теги)
        $offset = ($page-1) * 10; 
        $num = 10;
        
        // SELECT * FROM `posts` LEFT JOIN `users` ON `users`.`id` = `post_user_id` ORDER BY `post_id` DESC LIMIT ? OFFSET ?
       /*  $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['users.id'], '=', ['post_user_id'])->orderBy(['post_id'])
                   ->desc()->limit(10)->offset($offset);

        $result = $query->toString(); */ 
        
// https://toxu.ru/t/rabochij-post-po-sajtu/14527
$sql = "select p.post_id, p.post_title, p.post_slug, p.post_date, p.post_comments, p.post_votes,
               u.id, u.login, u.avatar, 
               t.taggings_post_id, t.taggings_tag_id,
               tg.tags_id, tg.tags_slug
               from posts as p

            LEFT JOIN users as u ON u.id = p.post_user_id 
            LEFT JOIN taggings as t ON p.post_id = t.taggings_post_id
            LEFT JOIN tags as tg ON tg.tags_id = t.taggings_post_id

            ORDER BY p.post_id DESC LIMIT 10 OFFSET ".$offset." ";
        
        $result = DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }
    
    public static function getPostFeedCount()
    {
     
        $query= XD::select(['post_id'])->from(['posts'])->getSelect();
        $result = ceil(count($query) / 10);

        return $result;

    }

 
    // TOP посты на главной 
    public static function getPostTop()
    {

        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['users.id'], '=', ['post_user_id'])->orderBy(['post_comments'])->desc();

        $result = $query->getSelect();

        return $result;

    }
    
    // Полная версия поста  
    public static function getPost($slug)
    {

        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                 ->where(['post_slug'], '=', $slug);
        
        $result = $query->getSelectOne();
        
        return $result;

    }   
    
    // Страница постов участника
    public static function getUsersPosts($login)
    {
        
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
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
    public static function addPost($post_title, $post_content, $post_slug, $post_ip_int, $post_user_id)
    {
       
        // Проверить пост на повтор slug (переделать)
        $q = XD::select('*')->from(['posts']);
        $query = $q->where(['post_slug'], '=', $post_slug);
        $result = $query->getSelectOne();
        
        if ($result) {
            $post_slug =  $post_slug . "-";
        }
       
        // toString  строковая заменя для проверки
        XD::insertInto(['posts'], '(', ['post_title'], ',', ['post_content'], ',', ['post_slug'], ',', ['post_ip_int'], ',', ['post_user_id'], ')')->values( '(', XD::setList([$post_title, $post_content, $post_slug, $post_ip_int, $post_user_id]), ')' )->run();
       
        // Попучим последний id поста
        $post_id = XD::select()->last_insert_id('()')->getSelectValue(); // SELECT LAST_INSERT_ID();
       
        return $post_id; 
       
    } 
    
    // Получаем пост по id
    public static function getPostId($id) 
    {
      
        $q = XD::select('*')->from(['posts']);
        $query = $q->where(['post_id'], '=', $id);
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
