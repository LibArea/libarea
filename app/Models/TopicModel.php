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
        
                FROM topics ORDER BY topic_count DESC LIMIT $start, $limit";
                        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }

    // Количество
    public static function getTopicsAllCount()
    {
        $sql = "SELECT topic_id FROM topics";

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
        
                FROM topics WHERE $sort";

        return DB::run($sql, ['params' => $params])->fetch(PDO::FETCH_ASSOC); 
    }

    // Новые
    public static function getTopicNew()
    {
        $sql = "SELECT 
                    topic_id,
                    topic_title,
                    topic_slug
                
                FROM topics ORDER BY topic_id DESC LIMIT 10";
                        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Есть пост в темах
    public static function getRelationId($id)
    {
        $sql = "SELECT r.* FROM topics_post_relation AS r WHERE r.relation_post_id = :id";
        
        return DB::run($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC); 
    }
    
    public static function addPostTopics($rows, $post_id)
    {
        self::deletePostRelation($post_id);
        foreach ($rows as $row) 
        {
            $sql = "INSERT INTO topics_post_relation (relation_topic_id, relation_post_id) 
                        VALUES ($row[0], $row[1])";
                    
            DB::run($sql);
        }

        return true;
    }
 
    // Удалить записи id поста из таблицы связи
    public static function deletePostRelation($post_id)
    {
        $sql = "DELETE FROM topics_post_relation WHERE relation_post_id =  $post_id";
        
        DB::run($sql);
        
        return true; 
    } 
    
    // Изменение img
    public static function setImg($topic_id, $img)
    {
        return XD::update(['topics'])->set(['topic_img'], '=', $img)->where(['topic_id'], '=', $topic_id)->run();
    }
    
    // Связанные темы
    public static function topicRelated($topic_related)
    {
        $sql = "SELECT * FROM topics WHERE topic_id IN(0, ".$topic_related.") ";
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Связанные посты для детальной информации по теме
    public static function topicPostRelated($topic_post_related)
    {
        $sql = "SELECT * FROM posts WHERE post_id IN(0, ".$topic_post_related.") ";
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // Выбор корневой темы при редактирование (если они есть)
    public static function topicMain($topic_id)
    {
        return XD::select('*')->from(['topics'])->where(['topic_id'], '=', $topic_id)->and(['topic_is_parent'], '=', 1)->getSelect();
    } 
    
    // Выведем подтемы
    public static function subTopics($topic_id)
    { 
        return XD::select('*')->from(['topics'])->where(['topic_parent_id'], '=', $topic_id)->getSelect();
    }
    
    // Очистим привязку при изменение корневой темы
    public static function clearBinding($topic_id)
    {
        return XD::update(['topics'])->set(['topic_parent_id'], '=', 0)->where(['topic_parent_id'], '=', $topic_id)->run();
    }
}