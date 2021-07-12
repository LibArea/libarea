<?php
namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class TopicModel extends \MainModel
{
    // Все темы
    public static function getTopicsAll($page, $limit)
    {
        $start  = ($page-1) * $limit; 
        $sql    = "SELECT 
                    topic_id,
                    topic_title,
                    topic_description,
                    topic_slug,
                    topic_img,
                    topic_parent_id,
                    topic_is_parent,
                    topic_count
        
                FROM topic ORDER BY topic_count DESC LIMIT $start, $limit";
                        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }

    // Количество
    public static function getTopicsAllCount()
    {
        $sql = "SELECT topic_id FROM topic";

        return DB::run($sql)->rowCount(); 
    }
    
    // Информация по теме (id, slug)
    public static function getTopic($params, $name)
    {
        $sort = "topic_id = :params";
        if ($name == 'slug') {
            $sort = "topic_slug = :params";
        } 
        
        $sql = "SELECT 
                    topic_id,
                    topic_title,
                    topic_description,
                    topic_info,
                    topic_slug,
                    topic_img,
                    topic_add_date,
                    topic_seo_title,
                    topic_merged_id,
                    topic_parent_id,
                    topic_is_parent,
                    topic_tl,
                    topic_related,
                    topic_post_related,
                    topic_space_related,
                    topic_focus_count,
                    topic_count
        
                FROM topic WHERE $sort";

        return DB::run($sql, ['params' => $params])->fetch(PDO::FETCH_ASSOC); 
    }

    // Новые
    public static function getTopicNew()
    {
        $sql = "SELECT 
                    topic_id,
                    topic_title,
                    topic_slug
                
                FROM topic ORDER BY topic_id DESC LIMIT 10";
                        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Информация список постов
    public static function getPostsListByTopic($page, $limit, $topic_id, $uid)
    {
        // Условия: удаленный пост, запрещенный к показу в ленте
        // И ограниченный по TL
        if ($uid['trust_level'] != 5) {  
            if ($uid['id'] == 0) { 
                $tl = 'AND p.post_tl = 0';
            } else {
                $tl = 'AND p.post_tl <= '.$uid['trust_level'].'';
            }
            $display = 'AND p.post_is_delete = 0 '.$tl.'';
        } else {
            $display = ''; 
        }
        
        $start  = ($page-1) * $limit; 
        $sql = "SELECT p.*, t.*,
            r.relation_post_id, r.relation_topic_id,
            u.id, u.login, u.avatar,
            s.space_id, s.space_slug, s.space_name,
            v.votes_post_item_id, v.votes_post_user_id
            FROM topic AS t
            INNER JOIN topic_post_relation AS r ON r.relation_topic_id = t.topic_id
            INNER JOIN posts AS p ON p.post_id = r.relation_post_id
            INNER JOIN space AS s ON s.space_id = p.post_space_id
            INNER JOIN users AS u ON u.id = p.post_user_id
            LEFT JOIN votes_post AS v ON v.votes_post_item_id = p.post_id AND v.votes_post_user_id = ".$uid['id']."
            WHERE t.topic_id  = $topic_id
            $display ORDER BY p.post_top DESC, p.post_date DESC LIMIT $start, $limit ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }

    // Информация список постов
    public static function getPostsListByTopicCount($topic_id, $uid)
    {
        // Условия: удаленный пост, запрещенный к показу в ленте
        // И ограниченный по TL
        if ($uid['trust_level'] != 5) {  
            if ($uid['id'] == 0) { 
                $tl = 'AND p.post_tl = 0';
            } else {
                $tl = 'AND p.post_tl <= '.$uid['trust_level'].'';
            }
            $display = 'AND p.post_is_delete = 0 '.$tl.'';
        } else {
            $display = ''; 
        }
        
        $sql = "SELECT p.*, t.*, 
            r.relation_post_id, r.relation_topic_id,
            FROM topic AS t
            INNER JOIN topic_post_relation AS r ON r.relation_topic_id = t.topic_id
            INNER JOIN posts AS p ON p.post_id = r.relation_post_id
            WHERE t.topic_id  = $topic_id
            $display ";

        return DB::run($sql)->rowCount(); 
    }

    // Select topics
    public static function getSearchTopics($query, $main)
    {
        $and = '';
        if ($main == 'main') {
            $and = 'topic_is_parent !=0 AND';
        } 
        
        $sql = "SELECT 
                topic_id,
                topic_title
                    FROM topic 
                    WHERE $and topic_title LIKE :topic_title ORDER BY topic_id LIMIT 8";
        
        $result = DB::run($sql, ['topic_title' => "%".$query."%"]);
        $topicList  = $result->fetchall(PDO::FETCH_ASSOC);
        $response = array();
        foreach ($topicList as $topic) {
           $response[] = array(
              "id" => $topic['topic_id'],
              "text" => $topic['topic_title']
           );
        }

        echo json_encode($response);
    }
 
    // Есть пост в темах
    public static function getRelationId($id)
    {
        $sql = "SELECT r.* FROM topic_post_relation AS r WHERE r.relation_post_id = :id";
        
        return DB::run($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC); 
    }
    
 
    public static function addPostTopics($rows, $post_id)
    {
        self::deletePostRelation($post_id);
        foreach ($rows as $row) {
            $sql = "INSERT INTO topic_post_relation (relation_topic_id, relation_post_id) VALUES ($row[0], $row[1])";
            DB::run($sql);
        }

        return true;
    }
 
    // Добавим Topic
    public static function add($data)
    {
        XD::insertInto(['topic'], '(', 
            ['topic_title'], ',', 
            ['topic_description'], ',', 
            ['topic_slug'], ',', 
            ['topic_img'], ',',  
            ['topic_add_date'], ',',
            ['topic_seo_title'], ',', 
            ['topic_merged_id'], ',',
            ['topic_related'], ',',
            ['topic_count'],')')->values( '(', 
        
        XD::setList([
            $data['topic_title'], 
            $data['topic_description'],
            $data['topic_slug'], 
            $data['topic_img'], 
            $data['topic_add_date'],
            $data['topic_seo_title'],
            $data['topic_merged_id'],
            $data['topic_related'],
            $data['topic_count']]), ')' )->run();

        return XD::select()->last_insert_id('()')->getSelectValue(); 
    } 
    
    // Edit Topic
    public static function edit($data)
    {
           XD::update(['topic'])->set(['topic_title'], '=', $data['topic_title'], ',', 
            ['topic_description'], '=', $data['topic_description'], ',', 
            ['topic_info'], '=', $data['topic_info'], ',', 
            ['topic_slug'], '=', $data['topic_slug'], ',', 
            ['topic_seo_title'], '=', $data['topic_seo_title'], ',', 
            ['topic_merged_id'], '=', $data['topic_merged_id'], ',', 
            ['topic_parent_id'], '=', $data['topic_parent_id'], ',',
            ['topic_is_parent'], '=', $data['topic_is_parent'], ',',
            ['topic_post_related'], '=', $data['topic_post_related'], ',',
            ['topic_related'], '=', $data['topic_related'], ',', 
            ['topic_count'], '=', $data['topic_count'])
            ->where(['topic_id'], '=', $data['topic_id'])->run(); 

        return true;
    } 
    
    
    // Удалить записи id поста из таблицы связи
    public static function deletePostRelation($post_id)
    {
        $sql = "DELETE FROM topic_post_relation WHERE relation_post_id =  $post_id";
        
        DB::run($sql);
        
        return true; 
    } 
    
    // Изменение img
    public static function setImg($topic_id, $img)
    {
        return XD::update(['topic'])->set(['topic_img'], '=', $img)->where(['topic_id'], '=', $topic_id)->run();
    }
    
    // Связанные темы
    public static function topicRelated($topic_related)
    {
        $sql = "SELECT * FROM topic WHERE topic_id IN(0, ".$topic_related.") ";
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Связанные посты для детальной информации по теме
    public static function topicPostRelated($topic_post_related)
    {
        $sql = "SELECT * FROM posts WHERE post_id IN(0, ".$topic_post_related.") ";
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Подписан пользователь на topic
    public static function getMyFocus($topic_id, $user_id) 
    {
        $result = XD::select('*')->from(['topic_signed'])->where(['signed_topic_id'], '=', $topic_id)->and(['signed_user_id'], '=', $user_id)->getSelect();

        if ($result) {
            return true;
        } 
        return false;
    }
    
    // Подписка / отписка
    public static function focus($topic_id, $user_id)
    {
        $result  = self::getMyFocus($topic_id, $user_id);
          
        if ($result === true) {
            XD::deleteFrom(['topic_signed'])->where(['signed_topic_id'], '=', $topic_id)->and(['signed_user_id'], '=', $user_id)->run();
            
            $sql = "UPDATE topic SET topic_focus_count = (topic_focus_count - 1) WHERE topic_id = :topic_id";
            DB::run($sql,['topic_id' => $topic_id]); 
            
        } else {
            XD::insertInto(['topic_signed'], '(', ['signed_topic_id'], ',', ['signed_user_id'], ')')->values( '(', XD::setList([$topic_id, $user_id]), ')' )->run();

            $sql = "UPDATE topic SET topic_focus_count = (topic_focus_count + 1) WHERE topic_id = :topic_id";
            DB::run($sql,['topic_id' => $topic_id]);            
        }
        
        return true;
    }
    
    // Выбор корневой темы при редактирование (если они есть)
    public static function topicMain($topic_id)
    {
        return XD::select('*')->from(['topic'])->where(['topic_id'], '=', $topic_id)->and(['topic_is_parent'], '=', 1)->getSelect();
    } 
    
    // Выведем подтемы
    public static function subTopics($topic_id)
    { 
        return XD::select('*')->from(['topic'])->where(['topic_parent_id'], '=', $topic_id)->getSelect();
    }
    
    // Очистим привязку при изменение корневой темы
    public static function clearBinding($topic_id)
    {
        return XD::update(['topic'])->set(['topic_parent_id'], '=', 0)->where(['topic_parent_id'], '=', $topic_id)->run();
    }
    
}