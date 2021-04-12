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
                ->where(['login'], '=', $slug)
                ->orderBy(['comment_id'])->desc();
        
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
       
       // id последнего комментария
       $last_id = XD::select()->last_insert_id('()')->getSelectValue();
       
       // Отмечаем комментарий, что за ним есть ответ
       $otv = 1; // 1, значит за комментом есть ответ
       XD::update(['comments'])->set(['comment_after'], '=', $otv)->where(['comment_id'], '=', $comm_id)->run();

       return $last_id; 

    }
    
    // Последние 5 комментариев
    public static function latestComments() 
    {
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['posts'])->on(['post_id'], '=', ['comment_post_id'])
                 ->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                 ->leftJoin(['space'])->on(['post_space_id'], '=', ['space_id'])
                 ->where(['comment_del'], '=', 0)
                 ->orderBy(['comment_id'])->desc()->limit(5);


        $result = $query->getSelect();

        return $result;   
    }
    
    // Удаление комментария
    public static function CommentsDel($id)
    {
 
         XD::update(['comments'])->set(['comment_del'], '=', 1)
        ->where(['comment_id'], '=', $id)->run();
 
        return true;
    }
    
    // Получаем комментарий по id комментария
    public static function getCommentsOne($id)
    {
       return XD::select('*')->from(['comments'])->where(['comment_id'], '=', $id)->getSelectOne();
    }
    
    
    // Редактируем комментарий
    public static function CommentEdit($comm_id, $comment)
    {
        $data = date("Y-m-d H:i:s"); 
        return  XD::update(['comments'])->set(['comment_content'], '=', $comment, ',', ['comment_modified'], '=', $data)
        ->where(['comment_id'], '=', $comm_id)->run(); 
    }
    
    // Частота размещения комментариев участника 
    public static function getCommentSpeed($uid)
    {
       
        $post = XD::select(['comment_id', 'comment_user_id', 'comment_date'])->from(['comments'])
            ->where(['comment_user_id'], '=', $uid)
            ->orderBy(['comment_id'])->desc()->getSelectOne();
        
        // https://ru.stackoverflow.com/a/498675
        $comm_date  = $post['comment_date']; 
        $happy_day  = \DateTime::createFromFormat('Y-m-d H:i:s', $comm_date);
        $now        = new \DateTime('now');
        $result     = $now->diff($happy_day); 
        
        return $result;
    
   }
    
}