<?php
namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class AnswerModel extends \MainModel
{
    // Все ответы
    public static function getAnswersAll($page, $trust_level)
    {
        $offset = ($page-1) * 25; 
        
        $tl = 'AND p.post_tl = 0';
        if ($trust_level) { 
                $tl = 'AND p.post_tl <= '.$trust_level.'';
        } 
        
        $sql = "SELECT c.*, p.*, 
                u.id, u.login, u.avatar
                FROM answers as c
                JOIN users as u ON u.id = c.answer_user_id
                JOIN posts as p ON c.answer_post_id = p.post_id AND c.answer_del = 0 ".$tl."
                ORDER BY c.answer_id DESC LIMIT 25 OFFSET ".$offset." ";
                        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Количество ответов
    public static function getAnswersAllCount()
    {
        $query = XD::select('*')->from(['answers'])->where(['answer_del'], '=', 0)->getSelect();
        
        return ceil(count($query) / 25);
    }
    
    // Получаем лучший комментарий (LO)
    public static function getAnswerLo($post_id)
    {
        return XD::select('*')->from(['answers'])
                ->where(['answer_post_id'], '=', $post_id)
                ->and(['answer_lo'], '>', 0)->getSelectOne();
    }
    
    // Получаем ответы в посте
    public static function getAnswersPost($post_id, $user_id, $type)
    { 
        $sort = "";
        if ($type == 1) {
            $sort = 'ORDER BY a.answer_votes DESC ';
        }    
        
        $sql = "SELECT 
                a.answer_id,
                a.answer_user_id,
                a.answer_post_id,
                a.answer_date,
                a.answer_content,
                a.answer_modified,
                a.answer_ip,
                a.answer_votes,
                a.answer_after,
                a.answer_del,
        
                v.votes_answer_item_id, 
                v.votes_answer_user_id,
                
                f.favorite_tid,
                f.favorite_uid,
                f.favorite_type,
                
                u.id, 
                u.login, 
                u.avatar
                
                FROM answers AS a
                LEFT JOIN users AS u ON u.id = a.answer_user_id
                LEFT JOIN votes_answer AS v ON v.votes_answer_item_id = a.answer_id
                    AND v.votes_answer_user_id = $user_id
                LEFT JOIN favorite AS f ON f.favorite_tid = a.answer_id
                    AND f.favorite_uid  = $user_id
                    AND f.favorite_type = 2
                    WHERE a.answer_post_id = $post_id
                    $sort ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }

    // Страница ответов участника
    public static function userAnswers($slug)
    {
        $q = XD::select('*')->from(['answers']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['answer_user_id'])
                ->leftJoin(['posts'])->on(['answer_post_id'], '=', ['post_id'])
                ->where(['login'], '=', $slug)
                ->and(['answer_del'], '=', 0)
                ->and(['post_tl'], '=', 0)
                ->orderBy(['answer_id'])->desc();
        
        return $query->getSelect();
    } 
   
    // Запись ответа
    // $post_id - на какой пост ответ
    // $ip      - IP отвечающего  
    // $comment - содержание
    // $my_id   - id автора ответа
    public static function answerAdd($post_id, $ip, $comment, $my_id)
    { 
        XD::insertInto(['answers'], '(', ['answer_post_id'], ',', ['answer_ip'], ',', ['answer_content'], ',', ['answer_user_id'], ')')->values( '(', XD::setList([$post_id, $ip, $comment, $my_id]), ')' )->run();
       
       // id последнего ответа
       $last_id = XD::select()->last_insert_id('()')->getSelectValue();
       
       // Отмечаем комментарий, что за ним есть ответ
       //$otv = 1; // 1, значит за комментом есть ответ
       //XD::update(['comments'])->set(['comment_after'], '=', $otv)->where(['comment_id'], '=', $comment_id)->run();

       return $last_id; 
    }
    
    // Удаление ответа
    public static function AnswerDel($id)
    {
       return  XD::update(['answers'])->set(['answer_del'], '=', 1)->where(['answer_id'], '=', $id)->run();
    }
    
    // Информацию по id ответа
    public static function getAnswerOne($id)
    {
       return XD::select('*')->from(['answers'])->where(['answer_id'], '=', $id)->getSelectOne();
    }
    
    // Редактируем ответ
    public static function AnswerEdit($comment_id, $answer)
    {
        $data = date("Y-m-d H:i:s"); 
        
        return  XD::update(['answers'])->set(['answer_content'], '=', $answer, ',', ['answer_modified'], '=', $data)
        ->where(['answer_id'], '=', $comment_id)->run();
    }
    
    // Частота размещения ответа участника 
    public static function getAnswerSpeed($uid)
    {
        $sql = "SELECT answer_id, answer_user_id, answer_date
                fROM answers 
                WHERE answer_user_id = ".$uid."
                AND answer_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
                
        return  DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Добавить ответы в закладки
    public static function setAnswerFavorite($post_id, $uid)
    {
        $result = self::getMyAnswerFavorite($post_id, $uid); 

        if (!$result) {
           XD::insertInto(['favorite'], '(', ['favorite_tid'], ',', ['favorite_uid'], ',', ['favorite_type'], ')')->values( '(', XD::setList([$post_id, $uid, 2]), ')' )->run();
        } else {
           XD::deleteFrom(['favorite'])->where(['favorite_tid'], '=', $post_id)->and(['favorite_uid'], '=', $uid)->run(); 
        } 
        
        return true;
    }
  
    // Ответы в закладках или нет
    public static function getMyAnswerFavorite($post_id, $uid) 
    {
        $result = XD::select('*')->from(['favorite'])->where(['favorite_tid'], '=', $post_id)
        ->and(['favorite_uid'], '=', $uid)
        ->and(['favorite_type'], '=', 2)
        ->getSelect();

        if ($result) {
            return true;
        } 
        return false;
    }
    
    // Удаленные ответы
    public static function getAnswersDeleted($page, $limit) 
    {
        $start  = ($page-1) * $limit;
        $sql = "SELECT 
                    a.answer_id, 
                    a.answer_user_id, 
                    a.answer_date,
                    a.answer_content,
                    a.answer_votes,
                    a.answer_del,
                    u.id,
                    u.login,
                    u.avatar,
                    p.post_id,
                    p.post_title,
                    p.post_slug
                    
                        FROM answers AS a
                        LEFT JOIN users AS u ON u.id = a.answer_user_id
                        LEFT JOIN posts AS p ON p.post_id = a.answer_post_id
                        WHERE a.answer_del = 1
                        ORDER BY a.answer_id DESC LIMIT $start, $limit";
                
        return  DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Количество
    public static function getAnswersDeletedCount()
    {
        $sql = "SELECT answer_id, answer_del FROM answers WHERE answer_del = 1";

        return DB::run($sql)->rowCount(); 
    }
    
    // Восстановление
    public static function answerRecover($id)
    {
         XD::update(['answers'])->set(['answer_del'], '=', 0)
        ->where(['answer_id'], '=', $id)->run();
 
        return true;
    }    
}