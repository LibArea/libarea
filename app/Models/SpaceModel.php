<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class SpaceModel extends \MainModel
{
    // Пространства все / подписан
    public static function getSpaces($user_id, $sort) 
    {
        $signet = "";
        if ($sort == 'subscription') { 
            $signet = "AND f.signed_user_id = :user_id"; 
        } 
        
        $sql = "SELECT 
                s.space_id, 
                s.space_name, 
                s.space_description,
                s.space_slug, 
                s.space_img,
                s.space_date,
                s.space_type,
                s.space_user_id,
                s.space_is_delete,
                u.id,
                u.login,
                u.avatar,
                f.signed_space_id, 
                f.signed_user_id
 
                    FROM space s 
                    LEFT JOIN users as u ON u.id = s.space_user_id
                    LEFT JOIN space_signed as f ON f.signed_space_id = s.space_id AND f.signed_user_id = :user_id 
                    WHERE s.space_is_delete != 1 $signet";

       return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);  
    }

    // Для форм добавления и изменения поста
    public static function getSpaceSelect($user_id, $trust_level)
    {
        $sql = "SELECT * FROM space WHERE space_permit_users = 0 or space_user_id = :user_id ORDER BY space_id DESC";
        if ($trust_level == 5) {
            $sql = "SELECT * FROM space";
        }

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC); 
    } 

   // По id
    public static function getSpaceId($space_id)
    {
        return XD::select('*')->from(['space'])->where(['space_id'], '=', $space_id)->getSelectOne();
    }

   // Пространства участника
    public static function getSpaceUserId($user_id)
    {
        return XD::select('*')->from(['space'])->where(['space_user_id'], '=', $user_id)->getSelect();
    }

    // Количество читающих
    public static function numSpaceSubscribers($space_id)
    {
        $result = XD::select('*')->from(['space_signed'])->where(['signed_space_id'], '=', $space_id)->getSelect();
       
        return count($result);
    } 

    // Списки постов по пространству
    public static function getPosts($space_id, $user_id, $user_tl, $type, $page)
    {
        $display = ''; 
        if ($user_tl != 5) {  
            $tl = "AND p.post_tl <= $user_tl";
            if ($user_id == 0) { 
                $tl = "AND p.post_tl = 0";
            } 
            $display = "AND p.post_is_delete = 0 $tl";
        } 
         
        $sort = "ORDER BY p.post_answers_count DESC"; 
        if ($type == 'feed') { 
            $sort = "ORDER BY p.post_top DESC, p.post_date DESC";
        }           
            
        $offset = ($page-1) * 25;   

        $sql = "SELECT p.*, 
                    rel.*,
                    v.votes_post_item_id, v.votes_post_user_id,
                    u.id, u.login, u.avatar
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
            
                LEFT JOIN users AS u ON u.id = p.post_user_id 
                LEFT JOIN votes_post AS v ON v.votes_post_item_id = p.post_id AND v.votes_post_user_id = $user_id
                WHERE p.post_space_id = $space_id and p.post_draft = 0
                
                $display
                $sort LIMIT 25 OFFSET ".$offset." ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Количество постов
    public static function getCount($space_id, $user_id, $user_tl, $type, $page)
    {
        $display = ''; 
        if ($user_tl != 5) {  
            
            $tl = "AND p.post_tl <= $user_tl";
            if ($user_id == 0) { 
                $tl = "AND p.post_tl = 0";
            } 
            
            $display = "AND p.post_is_delete = 0 $tl";
        } 
        
        $sort = "ORDER BY p.post_answers_count DESC";
        if ($type == 'feed') { 
            $sort = "ORDER BY p.post_top DESC, p.post_date DESC";
        }          
            
        $sql = "SELECT p.*, v.*,
                u.id, u.login, u.avatar,
                FROM posts AS p
                LEFT JOIN users AS u ON id = post_user_id 
                LEFT JOIN votes_post AS v ON v.votes_post_item_id = p.post_id AND v.votes_post_user_id = $user_id
                WHERE p.post_space_id = $space_id and p.post_draft = 0 $display $sort";

        $quantity = DB::run($sql)->rowCount(); 
       
        return ceil($quantity / 25);
    }
    
    // Информация пространства по slug
    public static function getSpaceSlug($slug)
    {
        $q = XD::select('*')->from(['space'])->leftJoin(['users'])->on(['space_user_id'], '=', ['id']);
        return $q->where(['space_slug'], '=', $slug)->getSelectOne();
    }
    
    // Подписан пользователь на пространство или нет
    public static function getMyFocus($space_id, $user_id) 
    {
        $result = XD::select('*')->from(['space_signed'])->where(['signed_space_id'], '=', $space_id)->and(['signed_user_id'], '=', $user_id)->getSelect();

        if ($result) {
            return true;
        } 
        return false;
    }
    
    // Подписка / отписка от пространства
    public static function focus($space_id, $user_id)
    {
        $result  = self::getMyFocus($space_id, $user_id);
          
        if ($result === true) {
           XD::deleteFrom(['space_signed'])->where(['signed_space_id'], '=', $space_id)->and(['signed_user_id'], '=', $user_id)->run(); 
        } else {
            XD::insertInto(['space_signed'], '(', ['signed_space_id'], ',', ['signed_user_id'], ')')->values( '(', XD::setList([$space_id, $user_id]), ')' )->run(); 
        }
        
        return true;
    }
    
    // Изменение пространства
    public static function edit($data)
    {
        XD::update(['space'])->set(['space_slug'], '=', $data['space_slug'], ',', 
                ['space_name'], '=', $data['space_name'], ',', 
                ['space_description'], '=', $data['space_description'], ',', 
                ['space_color'], '=', $data['space_color'], ',', 
                ['space_text'], '=', $data['space_text'], ',',
                ['space_short_text'], '=', $data['space_short_text'], ',',
                ['space_permit_users'], '=', $data['space_permit_users'], ',',
                ['space_feed'], '=', $data['space_feed'], ',',            
                ['space_tl'], '=', $data['space_tl'])->where(['space_id'], '=', $data['space_id'])->run();
        
        return true;
    }
    
    // Изменение фото / обложки
    public static function setImg($space_id, $img)
    {
        return XD::update(['space'])->set(['space_img'], '=', $img)->where(['space_id'], '=', $space_id)->run();
    }

    public static function setCover($space_id, $cover)
    {
        return XD::update(['space'])->set(['space_cover_art'], '=', $cover)->where(['space_id'], '=', $space_id)->run();
    }
 
    // Удалим обложку для пространства
    public static function CoverRemove($space_id)
    {
        return XD::update(['space'])->set(['space_cover_art'], '=', 'space_cover_no.jpeg')
                ->where(['space_id'], '=', $space_id)->run();
    }

    // Удалено пространство или нет
    public static function isTheSpaceDeleted($space_id) 
    {
        $result = XD::select('*')->from(['space'])->where(['space_id'], '=', $space_id)->getSelectOne();
        
        return $result['space_is_delete'];
    }
    
    // Удаление, восстановление пространства
    public static  function SpaceDelete($space_id)
    {
        if (self::isTheSpaceDeleted($space_id) == 1) {
            XD::update(['space'])->set(['space_is_delete'], '=', 0)->where(['space_id'], '=', $space_id)->run();
        } else {
            XD::update(['space'])->set(['space_is_delete'], '=', 1)->where(['space_id'], '=', $space_id)->run();
        }
        return true;
    } 
    
    // Добавляем пространства
    public static function AddSpace($data) 
    {
        XD::insertInto(['space'], '(', 
            ['space_name'], ',', 
            ['space_slug'], ',', 
            ['space_description'], ',', 
            ['space_color'], ',',  
            ['space_img'], ',',
            ['space_text'], ',',  
            ['space_short_text'], ',', 
            ['space_date'], ',',
            ['space_category_id'], ',',
            ['space_user_id'], ',', 
            ['space_type'], ',', 
            ['space_permit_users'], ',', 
            ['space_feed'], ',', 
            ['space_tl'], ',',
            ['space_is_delete'], ')')->values( '(', 
        
        XD::setList([
            $data['space_name'], 
            $data['space_slug'], 
            $data['space_description'], 
            $data['space_color'],
            $data['space_img'],            
            $data['space_text'], 
            $data['space_short_text'], 
            $data['space_date'], 
            $data['space_category_id'], 
            $data['space_user_id'], 
            $data['space_type'], 
            $data['space_permit_users'],
            $data['space_feed'],
            $data['space_tl'],
            $data['space_is_delete']]), ')' )->run();
        
        // id добавленного пространства
        $space_id = XD::select()->last_insert_id('()')->getSelectValue();

        // Подписываем на созданное пространство   
        XD::insertInto(['space_signed'], '(', ['signed_space_id'], ',', ['signed_user_id'], ')')->values( '(', XD::setList([$space_id, $data['space_user_id']]), ')' )->run(); 

        return true; 
    }
}
