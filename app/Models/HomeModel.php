<?php

namespace App\Models;
use DB;
use PDO;

class HomeModel extends \MainModel
{
    // Посты на центральной странице
    public static function feed($page, $space_user, $uid, $type)
    {
        $result = Array();
        foreach ($space_user as $ind => $row) {
            $result[$ind] = $row['signed_space_id'];
        } 
        
        // Временное решение
        // Мы должны сформировать список пространств по умолчанию (в config)
        // и добавить условие показа постов, рейтинг которых достигает > N+ значения
        // в первый час размещения, но не вошедшие в пространства по умолчанию к показу
        if ($uid['id'] == 0) {
           $string = 'WHERE p.post_draft  = 0';
        } else {
            if ($type == 'all') {
                $string = "WHERE p.post_draft  = 0"; 
            } else {    
                if ($result) {
                    $string = "WHERE p.post_space_id IN(1, ".implode(',', $result).") AND p.post_draft  = 0";
                } else {
                   $string = "WHERE p.post_space_id IN(1) AND p.post_draft  = 0"; 
                }
            }    
        }        

        $offset = ($page-1) * 25; 
   
        // Условия: удаленный пост, запрещенный к показу в ленте
        // И ограниченный по TL
        $display = '';
        if ($uid['trust_level'] != 5) {  
            $tl = 'AND p.post_tl <= '.$uid['trust_level'].'';
            if ($uid['id'] == 0) { 
                $tl = 'AND p.post_tl = 0';
            } 
            $display = 'AND p.post_is_delete = 0 AND s.space_feed = 0 '.$tl.'';
        } 
         
        $sort = ' ORDER BY p.post_answers_num DESC'; 
        if ($type == 'feed' || $type == 'all') { 
            $sort = ' ORDER BY p.post_top DESC, p.post_date DESC';
        }  

        $sql = "SELECT p.*, 
                    rel.*,
                    v.votes_post_item_id, v.votes_post_user_id,
                    u.id, u.login, u.avatar, 
                    s.space_id, s.space_slug, s.space_name
                FROM posts AS p
                
                LEFT JOIN
                (
                    SELECT 
                        MAX(t.topic_id), 
                        MAX(t.topic_slug), 
                        MAX(t.topic_title),
                        MAX(r.relation_topic_id), 
                        r.relation_post_id,

                        GROUP_CONCAT(t.topic_slug, '@', t.topic_title SEPARATOR '@') AS topic_list
                        FROM topic  AS t
                        LEFT JOIN topic_post_relation AS r
                            on t.topic_id = r.relation_topic_id
                        GROUP BY r.relation_post_id
                ) AS rel
                    ON rel.relation_post_id = p.post_id 

            INNER JOIN users AS u ON u.id = p.post_user_id
            INNER JOIN space AS s ON s.space_id = p.post_space_id
            LEFT JOIN votes_post AS v ON v.votes_post_item_id = p.post_id AND v.votes_post_user_id = ".$uid['id']."
            
            $string
            $display
            $sort LIMIT 25 OFFSET $offset "; 

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Количество постов
    public static function feedCount($space_user, $uid)
    {
        $result = Array();
        foreach ($space_user as $ind => $row) {
            $result[$ind] = $row['signed_space_id'];
        }   
        
        $string = "WHERE post_space_id IN(1)"; 
        if ($result) {
                $string = "WHERE post_space_id IN(1, ".implode(',', $result).")";
        } 

        // Учитываем подписку на пространства
        if ($uid['id'] == 0) {
           $string = '';
        } 
            
        // Учитываем TL
        $display = ''; 
        if ($uid['trust_level'] != 5) {   
            $tl = 'AND post_tl <= '.$uid['trust_level'].'';
            if ($uid['id'] == 0) { 
                $tl = 'AND post_tl = 0';
            } 
            $display = 'AND post_is_delete = 0 AND space_feed = 0 '.$tl.'';
        } 
     
        $sql = "SELECT post_id, post_space_id, space_id
                FROM posts
                INNER JOIN space ON space_id = post_space_id
                $string $display";

        $quantity = DB::run($sql)->rowCount(); 
       
        return ceil($quantity / 25);
    }
   
    // Последние 5 ответа на главной
    public static function latestAnswers($uid) 
    {
        $user_answer = "AND s.space_feed = 0 AND p.post_tl = 0";
        if ($uid['id']) { 
            $user_answer = "AND s.space_feed = 0 AND a.answer_user_id != ".$uid['id']." AND p.post_tl <= ".$uid['trust_level'];
         
            if ($uid['trust_level'] != 5) { 
                 $user_answer = "AND a.answer_user_id != " . $uid['id'];
            } 
        }
        
        $sql = "SELECT 
                    a.answer_id,
                    a.answer_post_id,
                    a.answer_user_id,
                    a.answer_del,
                    a.answer_content,
                    a.answer_date,
                    p.post_id,
                    p.post_tl,
                    p.post_slug,
                    p.post_space_id,
                    u.id,
                    u.login,
                    u.avatar,
                    s.space_id,
                    s.space_color,
                    s.space_feed
                        FROM answers AS a
                        LEFT JOIN posts AS p ON p.post_id = a.answer_post_id
                        LEFT JOIN users AS u ON u.id = a.answer_user_id
                        LEFT JOIN space AS s ON p.post_space_id = s.space_id 
                        WHERE a.answer_del = 0 
                        $user_answer 
                        ORDER BY a.answer_id DESC LIMIT 5";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
}
