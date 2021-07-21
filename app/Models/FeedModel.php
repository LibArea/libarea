<?php

namespace App\Models;
use DB;
use PDO;

class FeedModel extends \MainModel
{
    // Получаем посты по условиям
    public static function feed($page, $limit, $uid, $sheet, $type, $data)
    {
        if ($type == 'space') 
        {
            $selection   = $data['space_id'];
            $string     = "WHERE post_space_id = :selection"; 
        } 
        elseif ($type == 'topic') 
        {
            $qa         = $data['topic_slug'];
            $string     = "WHERE topic_list LIKE :qa";
        }
        elseif ($type == 'link') 
        {
            $selection   = $data['link_url_domain'];
            $string     = "WHERE post_url_domain  = :selection";
        }
        else 
        {
            $selection   = $data['post_user_id'];
            $string     = "WHERE post_user_id  = :selection";   
        }
        
        
        // Удаленный пост, запрещенный к показу в ленте и ограниченный по TL (trust_level)
        $display = '';
        if ($uid['trust_level'] != 5) 
        {
            $trust_level = "AND post_tl <= " . $uid['trust_level'];
            if ($uid['id'] == 0) 
            { 
                $trust_level = "AND post_tl = 0";
            } 
            
            $display = "AND post_is_deleted = 0 $trust_level";
        } 

        // По времени или по количеству ответов 
        $sort = "ORDER BY post_answers_count DESC"; 
        if ($sheet == 'feed' || $sheet == 'all') 
        { 
            $sort = "ORDER BY post_top DESC, post_date DESC";
        }  
        
        $start  = ($page-1) * $limit;
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_type,
                    post_translation,
                    post_draft,
                    post_space_id,
                    post_date,
                    post_published,
                    post_user_id,
                    post_votes,
                    post_answers_count,
                    post_comments_count,
                    post_content,
                    post_content_img,
                    post_thumb_img,
                    post_merged_id,
                    post_closed,
                    post_tl,
                    post_lo,
                    post_top,
                    post_url_domain,
                    post_is_deleted,
                    rel.*,
                    votes_post_item_id, votes_post_user_id,
                    id, login, avatar, 
                    space_id, space_slug, space_name
                    
                        FROM posts
                        LEFT JOIN
                        (
                            SELECT 
                                MAX(topic_id), 
                                MAX(topic_slug), 
                                MAX(topic_title),
                                MAX(relation_topic_id), 
                                relation_post_id,

                                GROUP_CONCAT(topic_slug, '@', topic_title SEPARATOR '@') AS topic_list
                                FROM topic      
                                LEFT JOIN topic_post_relation 
                                    on topic_id = relation_topic_id
                                GROUP BY relation_post_id  
                        ) AS rel
                            ON rel.relation_post_id = post_id 
                            
                            INNER JOIN users ON id = post_user_id
                            INNER JOIN space ON space_id = post_space_id
                            LEFT JOIN votes_post ON votes_post_item_id = post_id 
                                AND votes_post_user_id = ". $uid['id'] ."
            
                        $string 
                        $display 
                        $sort LIMIT $start, $limit"; 

        if ($type == 'topic') 
        {
            $request = ['qa' => "%".$qa."%"];
        } 
        else 
        {
            $request = ['selection' => $selection]; 
        }

        return DB::run($sql, $request)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Количество постов
    public static function feedCount($uid, $type, $data)
    {
        if ($type == 'space') 
        {
            $selection   = $data['space_id'];
            $string     = "WHERE post_space_id = :selection"; 
        } 
        elseif ($type == 'topic') 
        {
            $qa         = $data['topic_slug'];
            $string     = "WHERE topic_list LIKE :qa";
        }
        elseif ($type == 'link') 
        {
            $selection   = $data['link_url_domain'];
            $string     = "WHERE post_url_domain  = :selection";
        } 
        else 
        {
            $selection   = $data['post_user_id'];
            $string     = "WHERE post_user_id  = :selection";   
        }

        // Удаленный пост, запрещенный к показу в ленте и ограниченный по TL (trust_level)
        $display = '';
        if ($uid['trust_level'] != 5) 
        {
            $trust_level = "AND post_tl <= " . $uid['trust_level'];
            if ($uid['id'] == 0) 
            { 
                $trust_level = "AND post_tl = 0";
            } 
            
            $display = "AND post_is_deleted = 0 AND space_feed = 0 $trust_level";
        } 

        // Учитываем TL
        $display = ''; 
        if ($uid['trust_level'] != 5) {   
            $tl = "AND post_tl <= " . $uid['trust_level'];
            if ($uid['id'] == 0) { 
                $tl = "AND post_tl = 0";
            } 
            $display = "AND post_is_deleted = 0 AND space_feed = 0 $tl";
        } 
     
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_type,
                    post_translation,
                    post_draft,
                    post_space_id,
                    post_date,
                    post_published,
                    post_user_id,
                    post_votes,
                    post_answers_count,
                    post_comments_count,
                    post_content,
                    post_content_img,
                    post_thumb_img,
                    post_merged_id,
                    post_closed,
                    post_tl,
                    post_lo,
                    post_top,
                    post_url_domain,
                    post_is_deleted,
                    rel.*,
                    id, login, avatar, 
                    space_id, space_slug, space_name
                    
                        FROM posts
                        LEFT JOIN
                        (
                            SELECT 
                                MAX(topic_id), 
                                MAX(topic_slug), 
                                MAX(topic_title),
                                MAX(relation_topic_id), 
                                relation_post_id,

                                GROUP_CONCAT(topic_slug, '@', topic_title SEPARATOR '@') AS topic_list
                                FROM topic      
                                LEFT JOIN topic_post_relation 
                                    on topic_id = relation_topic_id
                                GROUP BY relation_post_id  
                        ) AS rel
                            ON rel.relation_post_id = post_id 
                            
                            INNER JOIN users ON id = post_user_id
                            INNER JOIN space ON space_id = post_space_id
            
                    $string 
                    $display "; 

        if ($type == 'topic') 
        {
            $request = ['qa' => "%".$qa."%"];
        } 
        else 
        {
            $request = ['selection' => $selection]; 
        }

        return DB::run($sql, $request)->rowCount(); 
    }
   
 }
