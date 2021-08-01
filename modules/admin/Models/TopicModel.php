<?php

namespace Modules\Admin\Models;

use DB;
use PDO;

class TopicModel extends \MainModel
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
        
                FROM topic ORDER BY topic_id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

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

    // Есть пост в темах
    public static function getRelationId($id)
    {
        $sql = "SELECT r.* FROM topic_post_relation AS r WHERE r.relation_post_id = :id";

        return DB::run($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC);
    }

    // Связанные темы
    public static function topicRelated($topic_related)
    {
        $sql = "SELECT * FROM topic WHERE topic_id IN(0, " . $topic_related . ") ";
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Связанные посты для детальной информации по теме
    public static function topicPostRelated($topic_post_related)
    {
        $sql = "SELECT * FROM posts WHERE post_id IN(0, " . $topic_post_related . ") ";
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
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

        $sql = "INSERT INTO topic(topic_title, 
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

        $sql = "SELECT topic_id FROM topic ORDER BY topic_id DESC";

        return DB::run($sql)->fetch(PDO::FETCH_ASSOC);
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

        $sql = "UPDATE topic 
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
}
