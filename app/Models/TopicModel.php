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
    
    // Есть пост в темах
    public static function getRelationId($id)
    {
        $sql = "SELECT r.* FROM topic_post_relation AS r WHERE r.relation_post_id = :id";
        
        return DB::run($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC); 
    }
    
    public static function addPostTopics($rows, $post_id)
    {
        self::deletePostRelation($post_id);
        foreach ($rows as $row) 
        {
            $sql = "INSERT INTO topic_post_relation (relation_topic_id, relation_post_id) 
                        VALUES ($row[0], $row[1])";
                    
            DB::run($sql);
        }

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