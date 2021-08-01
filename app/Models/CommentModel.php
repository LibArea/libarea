<?php
namespace App\Models;

use XdORM\XD;
use DB;
use PDO;

class CommentModel extends \MainModel
{
    // Добавляем комментарий
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
    
    // Все комментарии
    public static function getCommentsAll($page, $limit, $uid)
    {
        if (!$uid['trust_level']) { 
                $tl = 'AND post_tl = 0';
        } else {
                $tl = 'AND post_tl <= '.$uid['trust_level'].'';
        }
        
        $start  = ($page-1) * $limit;
        $sql = "SELECT
                    post_id,
                    post_title,
                    post_slug,
                    post_tl,
                    comment_id,
                    comment_date,
                    comment_content,
                    comment_post_id,
                    comment_user_id,
                    comment_votes,
                    comment_is_deleted,
                    id, 
                    login, 
                    avatar
                        FROM comments 
                        JOIN users ON id = comment_user_id
                        JOIN posts ON comment_post_id = post_id AND comment_is_deleted = 0 ".$tl."
                        ORDER BY comment_id DESC LIMIT $start, $limit ";
                        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Количество комментариев
    public static function getCommentAllCount()
    {
        $sql = "SELECT comment_id, comment_is_deleted FROM comments WHERE comment_is_deleted = 0";

        return DB::run($sql)->rowCount(); 
    }
    
    // Получаем комментарии к ответу
    public static function getComments($answer_id, $user_id)
    { 
        $sql = "SELECT 
                    comment_id,
                    comment_user_id,                
                    comment_answer_id,
                    comment_comment_id,
                    comment_content,
                    comment_date,
                    comment_votes,
                    comment_ip,
                    comment_after,
                    comment_is_deleted,
                    votes_comment_item_id, 
                    votes_comment_user_id,
                    id, 
                    login, 
                    avatar
                        FROM comments 
                        LEFT JOIN users  ON id = comment_user_id
                        LEFT JOIN votes_comment  ON votes_comment_item_id = comment_id 
                        AND votes_comment_user_id = $user_id
                        WHERE comment_answer_id = " . $answer_id;
        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }

    // Страница комментариев участника
    public static function userComments($slug)
    {
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                ->leftJoin(['posts'])->on(['comment_post_id'], '=', ['post_id'])
                ->where(['login'], '=', $slug)
                ->and(['comment_is_deleted'], '=', 0)
                ->and(['post_tl'], '=', 0)
                ->orderBy(['comment_id'])->desc();
        
        return $query->getSelect();
    } 
    
    // Получаем комментарий по id комментария
    public static function getCommentsId($comment_id)
    {
       return XD::select('*')->from(['comments'])->where(['comment_id'], '=', $comment_id)->getSelectOne();
    }
    
    // Редактируем комментарий
    public static function CommentEdit($comment_id, $comment)
    {
        $data = date("Y-m-d H:i:s"); 
        return  XD::update(['comments'])->set(['comment_content'], '=', $comment, ',', ['comment_modified'], '=', $data)
        ->where(['comment_id'], '=', $comment_id)->run(); 
    }
    
    // Частота размещения комментариев участника 
    public static function getCommentSpeed($uid)
    {
        $sql = "SELECT comment_id, comment_user_id, comment_date
                FROM comments 
                WHERE comment_user_id = ".$uid."
                AND comment_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
                
        return  DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }

}