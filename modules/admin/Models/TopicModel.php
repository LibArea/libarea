<?php

namespace Modules\Admin\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class TopicModel extends MainModel
{
    // All topics
    public static function getTopicsAll($page, $limit)
    {
        $start  = ($page - 1) * $limit;
        $sql    = "SELECT 
                    topic_id,
                    topic_title,
                    topic_description,
                    topic_slug,
                    topic_img,
                    topic_parent_id,
                    topic_is_parent,
                    topic_count
        
                FROM topics ORDER BY topic_id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

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

    // Есть пост в темах
    public static function getRelationId($id)
    {
        $sql = "SELECT relation_topic_id, relation_post_id FROM topics_post_relation WHERE relation_post_id = :id";

        return DB::run($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC);
    }

    // Связанные темы
    public static function topicRelated($topic_related)
    {
        $string = "topic_id IN(0)";
        if ($topic_related) {
            $string = "topic_id IN(0, " . $topic_related . ")";
        }
        
        $sql = "SELECT topic_id, topic_title FROM topics WHERE $string ";
        
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Связанные посты для детальной информации по теме
    public static function topicPostRelated($topic_post_related)
    {
        $sql = "SELECT post_id, post_title, post_slug FROM posts WHERE post_id IN(0, " . $topic_post_related . ") ";
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Выбор корневой темы при редактирование (если они есть)
    public static function topicMain($topic_id)
    {
        $sql = "SELECT 
                    topic_id,
                    topic_title,
                    topic_slug, 
                    topic_is_parent 
                        FROM topics WHERE topic_id = :topic_id AND topic_is_parent = 1";

        return DB::run($sql, ['topic_id' => $topic_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function add($data)
    {
        $params = [
            'topic_title'       => $data['topic_title'],
            'topic_description' => $data['topic_description'],
            'topic_slug'        => $data['topic_slug'],
            'topic_img'         => $data['topic_img'],
            'topic_add_date'    => $data['topic_add_date'],
            'topic_seo_title'   => $data['topic_seo_title'],
            'topic_merged_id'   => $data['topic_merged_id'],
            'topic_related'     => $data['topic_related'],
            'topic_count'       => $data['topic_count'],
        ];

        $sql = "INSERT INTO topics(topic_title, 
                        topic_description, 
                        topic_slug, 
                        topic_img,
                        topic_add_date,
                        topic_seo_title,
                        topic_merged_id,
                        topic_related,
                        topic_count) 
                            VALUES(:topic_title, 
                                :topic_description, 
                                :topic_slug, 
                                :topic_img, 
                                :topic_add_date,
                                :topic_seo_title,
                                :topic_merged_id,
                                :topic_related,
                                :topic_count)";

        DB::run($sql, $params);

        return  DB::run("SELECT LAST_INSERT_ID() as topic_id")->fetch(PDO::FETCH_ASSOC);
    }

    public static function edit($data)
    {
        $params = [
            'topic_title'           => $data['topic_title'],
            'topic_description'     => $data['topic_description'],
            'topic_info'            => $data['topic_info'],
            'topic_slug'            => $data['topic_slug'],
            'topic_seo_title'       => $data['topic_seo_title'],
            'topic_parent_id'       => $data['topic_parent_id'],
            'topic_is_parent'       => $data['topic_is_parent'],
            'topic_post_related'    => $data['topic_post_related'],
            'topic_related'         => $data['topic_related'],
            'topic_count'           => $data['topic_count'],
            'topic_id'              => $data['topic_id'],
        ];

        $sql = "UPDATE topics 
                    SET topic_title     = :topic_title,  
                    topic_description   = :topic_description, 
                    topic_info          = :topic_info, 
                    topic_slug          = :topic_slug, 
                    topic_seo_title     = :topic_seo_title, 
                    topic_parent_id     = :topic_parent_id, 
                    topic_is_parent     = :topic_is_parent, 
                    topic_post_related  = :topic_post_related, 
                    topic_related       = :topic_related, 
                    topic_count         = :topic_count 
                        WHERE topic_id  = :topic_id";

        return  DB::run($sql, $params);
    }

    // Обновим данные
    public static function setUpdateQuantity()
    {
        $sql = "UPDATE topics SET topic_count = (SELECT count(relation_post_id) FROM topics_post_relation where relation_topic_id = topic_id )";

        return DB::run($sql);
    }

    // Очистим привязку при изменение корневой темы
    public static function clearBinding($topic_id)
    {
        $sql = "UPDATE topics SET topic_parent_id = 0 WHERE topic_parent_id = :topic_id";

        return DB::run($sql, ['topic_id' => $topic_id]);
    }
}
