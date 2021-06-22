<?php
namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class CommentModel extends \MainModel
{
    // Все комментарии
    public static function getCommentsAll($page, $user_id, $trust_level)
    {
        $offset = ($page-1) * 25; 
        
        if(!$trust_level) { 
                $tl = 'AND p.post_tl = 0';
        } else {
                $tl = 'AND p.post_tl <= '.$trust_level.'';
        }
        
        $sql = "SELECT c.*, p.*, 
                u.id, u.login, u.avatar
                fROM comments as c
                JOIN users as u ON u.id = c.comment_user_id
                JOIN posts as p ON c.comment_post_id = p.post_id AND c.comment_del = 0 ".$tl."
                ORDER BY c.comment_id DESC LIMIT 25 OFFSET ".$offset." ";
                        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Количество комментариев
    public static function getCommentAllCount()
    {
        $query = XD::select('*')->from(['comments'])->where(['comment_del'], '=', 0)->getSelect();

        return ceil(count($query) / 25);
    }
    
   
    // Получаем комментарии к ответу
    public static function getCommentsAnswer($answ_id, $uid)
    { 
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                ->leftJoin(['votes_comment'])->on(['votes_comment_item_id'], '=', ['comment_id'])
                ->and(['votes_comment_user_id'], '=', $uid)
                ->where(['comment_answer_id'], '=', $answ_id);

        return $query->getSelect();
    }

    // Страница комментариев участника
    public static function userComments($slug)
    {
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                ->leftJoin(['posts'])->on(['comment_post_id'], '=', ['post_id'])
                ->where(['login'], '=', $slug)
                ->and(['comment_del'], '=', 0)
                ->and(['post_tl'], '=', 0)
                ->orderBy(['comment_id'])->desc();
        
        return $query->getSelect();
    } 
   
    // Запись комментария
    // $post_id - на какой пост ответ
    // $answ_id - на какой ответ комментарий
    // $ip      - IP отвечающего  
    // $comm_id - на какой комм. ответ, 0 - корневой
    // $comment - содержание
    // $my_id   - id автора ответа
    public static function commentAdd($post_id, $answ_id, $comm_id, $ip, $comment, $my_id)
    { 

        XD::insertInto(['comments'], '(', ['comment_post_id'], ',', ['comment_answer_id'], ',', ['comment_comment_id'], ',', ['comment_ip'], ',', ['comment_content'], ',', ['comment_user_id'], ')')->values( '(', XD::setList([$post_id, $answ_id, $comm_id, $ip, $comment, $my_id]), ')' )->run();
       
       // id последнего комментария
       $last_id = XD::select()->last_insert_id('()')->getSelectValue();
       
       // Отмечаем комментарий, что за ним есть ответ
       $otv = 1; // 1, значит за комментом есть ответ
       XD::update(['comments'])->set(['comment_after'], '=', $otv)->where(['comment_id'], '=', $answ_id)->run();

       return $last_id; 
    }
    
    // Последние 5 комментариев
    public static function latestComments($user_id) 
    {
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['posts'])->on(['post_id'], '=', ['comment_post_id'])
                 ->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                 ->leftJoin(['space'])->on(['post_space_id'], '=', ['space_id']);
                 
                if($user_id){ 
                    $result = $query->where(['comment_del'], '=', 0)->and(['comment_user_id'], '!=', $user_id);
                } else {
                    $result = $query->where(['comment_del'], '=', 0);
                } 
        
        return $result->orderBy(['comment_id'])->desc()->limit(5)->getSelect();
    }
    
    // Удаление комментария
    public static function CommentsDel($comm_id)
    {
        
         XD::update(['comments'])->set(['comment_del'], '=', 1)
        ->where(['comment_id'], '=', $comm_id)->run();
 
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
        $sql = "SELECT comment_id, comment_user_id, comment_date
                fROM comments 
                WHERE comment_user_id = ".$uid."
                AND comment_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
                
        return  DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }

}