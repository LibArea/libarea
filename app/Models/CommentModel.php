<?php
namespace App\Models;
use XdORM\XD;

class CommentModel extends \MainModel
{
    // Все комментарии
    public static function getCommentsAll()
    {
 
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                ->leftJoin(['posts'])->on(['comment_post_id'], '=', ['post_id'])->orderBy(['comment_id'])->desc();
        
        $result = $query->getSelect();
 
        return $result;
    }
    
    // Получаем комментарии в посте
    public static function getCommentsPost($post_id)
    {
        
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])->where(['comment_post_id'], '=', $post_id);

        $result = $query->getSelect();

        return $result;
    
    }

    // Страница комментариев участника
    public static function getUsersComments($slug)
    {
        
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                ->leftJoin(['posts'])->on(['comment_post_id'], '=', ['post_id'])
                ->where(['login'], '=', $slug);
        
        $result = $query->getSelect();
        
        return $result;
    } 
   
    // Запись комментария
    // $post_id - на какой пост ответ
    // $ip      - IP отвечающего  
    // $comm_id - на какой комм. ответ, 0 - корневой
    // $comment - содержание
    // $my_id   - id автора ответа
    public static function commentAdd($post_id, $ip, $comm_id, $comment, $my_id)
    { 

       XD::insertInto(['comments'], '(', ['comment_post_id'], ',', ['comment_ip'], ',', ['comment_on'], ',', ['comment_content'], ',', ['comment_user_id'], ')')->values( '(', XD::setList([$post_id, $ip, $comm_id, $comment, $my_id]), ')' )->run();
       
       // Отмечаем комментарий, что за ним есть ответ
       $otv = 1; // 1, значит за комментом есть ответ
       XD::update(['comments'])->set(['comment_after'], '=', $otv)->where(['comment_id'], '=', $comm_id)->run();

        return true; 

    }
    
    // Последние 5 комментариев
    public static function latestComments() 
    {
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['posts'])->on(['post_id'], '=', ['comment_post_id'])
                 ->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                 ->orderBy(['comment_id'])->desc()->limit(5);


        $result = $query->getSelect();

        return $result;   
    }
    
}