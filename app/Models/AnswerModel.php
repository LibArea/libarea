<?php
namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class AnswerModel extends \MainModel
{
    // Все ответы
    public static function getAnswersAll($page, $user_id, $trust_level)
    {
        $offset = ($page-1) * 25; 
        
        if(!$trust_level) { 
                $tl = 'AND p.post_tl = 0';
        } else {
                $tl = 'AND p.post_tl <= '.$trust_level.'';
        }
        
        $sql = "SELECT c.*, p.*, 
                u.id, u.login, u.avatar
                fROM answers as c
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
    public static function getAnswersPost($post_id, $uid, $type)
    { 
        $q = XD::select('*')->from(['answers']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['answer_user_id'])
        ->leftJoin(['votes_answ'])->on(['votes_answ_item_id'], '=', ['answer_id'])
        ->and(['votes_answ_user_id'], '=', $uid)->where(['answer_post_id'], '=', $post_id);
        
        // 0 - дискуссия, 1 - Q&A
        if($type == 0) {
            return $query->getSelect();
        } else {
            return $query->orderBy(['answer_votes'])->desc()->getSelect();
        }
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
       //XD::update(['comments'])->set(['comment_after'], '=', $otv)->where(['comment_id'], '=', $comm_id)->run();

       return $last_id; 
    }
    
    // Последние 5 ответа
    public static function latestAnswers($uid) 
    {
        $q = XD::select('*')->from(['answers']);
        $query = $q->leftJoin(['posts'])->on(['post_id'], '=', ['answer_post_id'])
                 ->leftJoin(['users'])->on(['id'], '=', ['answer_user_id'])
                 //->leftJoin(['comments'])->on(['comment_post_id'], '=', ['post_id'])
                 ->leftJoin(['space'])->on(['post_space_id'], '=', ['space_id'])->where(['answer_del'], '=', 0);
                 
                // Свои ответы показывать не будем 
                if($uid['id']){ 
                    $qa = $query->and(['answer_user_id'], '!=', $uid['id']);
                } else {
                    $qa = $query;
                } 
        
                // Просматривать ответы из пространств ограниченных в ленте может только персонал
                if($uid['trust_level'] == 5){ 
                    $result = $qa;
                } else {
                    
                    if($uid['id'] == 0) {
                        $result = $qa->and(['space_feed'], '=', 0)->and(['post_tl'], '=', 0);
                    } else {
                       $result = $qa->and(['space_feed'], '=', 0)->and(['post_tl'], '<=', $uid['trust_level']); 
                    }    
                    
                } 
                
        return $result->orderBy(['answer_id'])->desc()->limit(5)->getSelect();
    }
    
    // Удаление ответа
    public static function AnswerDel($id)
    {
         XD::update(['answers'])->set(['answer_del'], '=', 1)
        ->where(['answer_id'], '=', $id)->run();
 
        return true;
    }
    
    // Получаем ответ по id 
    public static function getAnswerOne($id)
    {
       return XD::select('*')->from(['answers'])->where(['answer_id'], '=', $id)->getSelectOne();
    }
    
    // Редактируем ответ
    public static function AnswerEdit($comm_id, $answer)
    {
        $data = date("Y-m-d H:i:s"); 
        
        return  XD::update(['answers'])->set(['answer_content'], '=', $answer, ',', ['answer_modified'], '=', $data)
        ->where(['answer_id'], '=', $comm_id)->run();
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

        if(!$result){
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
        
        if($result) {
            return 1;
        } else {
            return false;
        }
    }
    
}