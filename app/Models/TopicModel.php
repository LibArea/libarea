<?php

namespace App\Models;

use DB;
use PDO;

class TopicModel extends \MainModel
{
    // Все темы
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
                        FROM topics ORDER BY topic_count DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество
    public static function getTopicsAllCount()
    {
        return DB::run("SELECT topic_id FROM topics")->rowCount();
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
        $sql = "SELECT 
                    relation_topic_id, 
                    relation_post_id 
                        FROM topics_post_relation 
                            WHERE relation_post_id = :id";

        return DB::run($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC);
    }

    public static function addPostTopics($rows, $post_id)
    {
        self::deletePostRelation($post_id);

        foreach ($rows as $row) {
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

        return DB::run($sql);
    }

    // Изменение img
    public static function setImg($topic_id, $img)
    {
        $sql = "UPDATE topics SET topic_img = :img WHERE topic_id = :topic_id";

        return DB::run($sql, ['topic_id' => $topic_id, 'img' => $img]);
    }

    // Связанные темы
    public static function topicRelated($topic_related)
    {
        $sql = "SELECT * FROM topics WHERE topic_id IN(0, " . $topic_related . ") ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Связанные посты для детальной информации по теме
    public static function topicPostRelated($topic_post_related)
    {
        $sql = "SELECT * FROM posts WHERE post_id IN(0, " . $topic_post_related . ") ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Выведем подтемы
    public static function subTopics($topic_id)
    {
        $sql = "SELECT topic_id, topic_title, topic_slug, topic_parent_id FROM topics WHERE topic_parent_id = :topic_id";

        return DB::run($sql, ['topic_id' => $topic_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Очистим привязку при изменение корневой темы
    public static function clearBinding($topic_id)
    {
        $sql = "UPDATE topics SET topic_parent_id = 0 WHERE topic_parent_id = :topic_id";

        return DB::run($sql, ['topic_id' => $topic_id]);
    }
}
