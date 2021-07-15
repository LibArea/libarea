<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class SpaceModel extends \MainModel
{
    // Пространства все / подписан
    public static function getSpacesAll($page, $limit, $user_id, $sort) 
    {
        $signet = "";
        if ($sort == 'subscription') { 
            $signet = "AND signed_user_id = :user_id"; 
        } 
        
        $start  = ($page-1) * $limit;
        $sql = "SELECT 
                space_id, 
                space_name, 
                space_description,
                space_slug, 
                space_img,
                space_date,
                space_type,
                space_user_id,
                space_is_delete,
                id,
                login,
                avatar,
                signed_space_id, 
                signed_user_id
                    FROM space  
                    LEFT JOIN users ON id = space_user_id
                    LEFT JOIN space_signed ON signed_space_id = space_id AND signed_user_id = :user_id 
                    WHERE space_is_delete != 1 $signet
                    ORDER BY space_id DESC LIMIT $start, $limit";

       return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);  
    }

    // Количество
    public static function getSpacesAllCount()
    {
        $sql = "SELECT space_id, space_is_delete FROM space WHERE space_is_delete != 1";

        return DB::run($sql)->rowCount(); 
    }

    // Для форм добавления и изменения поста
    public static function getSpaceSelect($user_id, $trust_level)
    {
        $sql = "SELECT 
                    space_id,
                    space_name,
                    space_user_id,
                    space_permit_users
                        FROM space 
                        WHERE space_permit_users = 0 or space_user_id = :user_id ORDER BY space_id DESC";
                        
        if ($trust_level == 5) {
            $sql = "SELECT 
                    space_id,
                    space_name,
                    space_user_id
                        FROM space";
        }

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC); 
    } 

    // Информация по пространству (id, slug)
    public static function getSpace($params, $name)
    {
        $sort = "space_id = :params";
        if ($name == 'slug') {
            $sort = "space_slug = :params";
        } 
        
        $sql = "SELECT 
                    space_id,
                    space_name,
                    space_slug,
                    space_description,
                    space_img,
                    space_cover_art,
                    space_text,
                    space_short_text,
                    space_date,
                    space_color,
                    space_category_id,
                    space_user_id,
                    space_type,
                    space_permit_users,
                    space_feed,
                    space_tl,
                    space_is_delete,
                    id,
                    login,
                    avatar
                        FROM space 
                        LEFT JOIN users ON space_user_id = id
                        WHERE $sort";

        return DB::run($sql, ['params' => $params])->fetch(PDO::FETCH_ASSOC); 
    }

    // Пространства, которые создал участник
    public static function getUserCreatedSpaces($user_id)
    {
        $sql = "SELECT 
                space_id, 
                space_slug, 
                space_name,
                space_img,
                space_user_id,
                space_is_delete
 
                    FROM space  
                    WHERE space_user_id = :user_id AND space_is_delete != 1";

       return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);  
    }

    // Количество читающих
    public static function numSpaceSubscribers($space_id)
    {
        $sql = "SELECT signed_id, signed_space_id FROM space_signed WHERE signed_space_id = $space_id";

        return DB::run($sql)->rowCount(); 
    } 

    // Список постов по пространству
    public static function getPosts($page, $limit, $space_id, $uid, $type)
    {
        $display = ''; 
        if ($uid['trust_level'] != 5) {  
            $tl = "AND post_tl <= " . $uid['trust_level'];
            if ($uid['id'] == 0) { 
                $tl = "AND post_tl = 0";
            } 
            $display = "AND post_is_deleted = 0 $tl";
        } 
         
        $sort = "ORDER BY post_answers_count DESC"; 
        if ($type == 'feed') { 
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
                    votes_post_item_id, 
                    votes_post_user_id,
                    id, 
                    login, 
                    avatar
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
            
                LEFT JOIN users ON id = post_user_id 
                LEFT JOIN votes_post ON votes_post_item_id = post_id AND votes_post_user_id = ". $uid['id'] ." 
                WHERE post_space_id = $space_id and post_draft = 0
                
                $display
                $sort LIMIT $start, $limit ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Количество постов
    public static function getPostsCount($space_id, $uid, $type)
    {
        $display = ''; 
        if ($uid['trust_level'] != 5) {  
            
            $tl = "AND post_tl <= " . $uid['trust_level'];
            if ($uid['id'] == 0) { 
                $tl = "AND post_tl = 0";
            } 
            
            $display = "AND post_is_deleted = 0 $tl";
        } 
        
        $sort = "ORDER BY post_answers_count DESC";
        if ($type == 'feed') { 
            $sort = "ORDER BY post_top DESC, post_date DESC";
        }          
            
        $sql = "SELECT 
                post_id,
                post_space_id,
                post_draft,
                post_user_id,
                post_answers_count,
                post_date,
                post_top,
                post_tl,
                post_is_deleted,
                votes_post_item_id,
                votes_post_user_id,
                id, 
                login, 
                avatar
                    FROM posts
                    LEFT JOIN users ON id = post_user_id 
                    LEFT JOIN votes_post ON votes_post_item_id = post_id AND votes_post_user_id = ". $uid['id'] ."
                    WHERE post_space_id = $space_id and post_draft = 0 $display $sort";

        return DB::run($sql)->rowCount(); 
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
