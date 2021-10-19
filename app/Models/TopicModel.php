<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class TopicModel extends MainModel
{
    // Все темы
    public static function getTopicsAll($page, $limit, $user_id, $sort)
    {
        $signet = "";
        if ($sort == 'subscription') {
            $signet = "WHERE signed_user_id = :user_id";
        }

        $start  = ($page - 1) * $limit;
        $sql    = "SELECT 
                    topic_id,
                    topic_title,
                    topic_description,
                    topic_slug,
                    topic_img,
                    topic_parent_id,
                    topic_is_parent,
                    topic_count,
                    signed_topic_id, 
                    signed_user_id
                        FROM topics 
                        LEFT JOIN topics_signed ON signed_topic_id = topic_id AND signed_user_id = :user_id
                        $signet
                        ORDER BY topic_count DESC LIMIT $start, $limit";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество
    public static function getTopicsAllCount($user_id, $sort)
    {
        $signet = "";
        if ($sort == 'subscription') {
            $signet = "WHERE signed_user_id = :user_id";
        }

        $sql    = "SELECT 
                    topic_id,
                    signed_topic_id, 
                    signed_user_id
                        FROM topics 
                        LEFT JOIN topics_signed ON signed_topic_id = topic_id AND signed_user_id = :user_id
                        $signet";

        return DB::run($sql, ['user_id' => $user_id])->rowCount();
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
                    topic_user_id,
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
    public static function getTopicNew($count)
    {
        $sql = "SELECT 
                    topic_id,
                    topic_title,
                    topic_slug
                        FROM topics ORDER BY topic_id DESC LIMIT $count";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
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

    public static function addLinkTopics($rows, $link_id)
    {
        self::deleteLinkRelation($link_id);

        foreach ($rows as $row) {
            $sql = "INSERT INTO topics_link_relation (relation_topic_id, relation_link_id) 
                        VALUES ($row[0], $row[1])";

            DB::run($sql);
        }

        return true;
    }

    public static function deleteLinkRelation($link_id)
    {
        $sql = "DELETE FROM topics_link_relation WHERE relation_link_id =  $link_id";

        return DB::run($sql);
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
        $string = "topic_id IN(0)";
        if ($topic_related) {
            $string = "topic_id IN(0, " . $topic_related . ")";
        }

        $sql = "SELECT topic_id, topic_title, topic_slug FROM topics WHERE $string ";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Связанные посты для детальной информации по теме
    public static function topicPostRelated($topic_post_related)
    {
        $sql = "SELECT post_id, post_title, post_slug FROM posts WHERE post_id IN(0, " . $topic_post_related . ") ";
        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Выведем подтемы
    public static function subTopics($topic_id)
    {
        $sql = "SELECT 
                    topic_id, 
                    topic_title, 
                    topic_slug, 
                    topic_parent_id 
                        FROM topics WHERE topic_parent_id = :topic_id";

        return DB::run($sql, ['topic_id' => $topic_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // TOP авторов темы. Limit 10
    public static function getWriters($topic_id)
    {
        $sql = "SELECT MAX(relation_topic_id), 
                    MAX(relation_post_id), 
                    MAX(post_id), 
                    SUM(post_hits_count) as hits_count, 
                    post_user_id,
                    rel.*
                        FROM topics_post_relation 
                        LEFT JOIN posts ON relation_post_id = post_id 
                        LEFT JOIN
                        (
                            SELECT 
                                user_id,
                                user_login,                                 
                                user_avatar 
                                FROM users 
                        ) AS rel
                            ON rel.user_id = post_user_id
                 WHERE relation_topic_id = :topic_id  GROUP BY post_user_id 
                 ORDER BY hits_count DESC LIMIT 5";

        return DB::run($sql, ['topic_id' => $topic_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function participation($user_id)
    {
        $sql = " SELECT 
                    MAX(post_id), 
                    MAX(post_user_id),
                    MAX(post_is_deleted),
                    SUM(relation_topic_id) as count,
                    rel.*
                    FROM posts
                    LEFT JOIN topics_post_relation ON relation_post_id = post_id
                    LEFT JOIN
                        ( SELECT 
                            topic_id,
                            topic_slug,
                            topic_title
                            FROM topics
                        ) AS rel
                            ON rel.topic_id = relation_topic_id
                               WHERE post_user_id = :user_id
                               GROUP BY relation_topic_id 
                               ORDER BY count DESC LIMIT 5";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function userReads($user_id)
    {
        $sql = "SELECT 
                    signed_topic_id                  
                        FROM topics_signed 
                            WHERE signed_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function advice($user_id)
    {
        if ($user_id == 0) return true;

        $userReads = self::userReads($user_id);
        $result = [];
        foreach ($userReads as $ind => $row) {
            $result[$ind] = $row['signed_topic_id'];
        }

        $result = $result ? $result : ['1' => 1];
        $sql = "SELECT 
                topic_id,
                topic_title,
                topic_slug,
                topic_img,
                topic_description,
                topic_count
                    FROM topics
                        WHERE topic_id NOT IN(" . implode(',', $result ?? []) . ")
                        ORDER BY topic_count DESC LIMIT 6";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    // Add / Edit
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
            'topic_user_id'     => $data['topic_user_id'],
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
                        topic_user_id,
                        topic_related,
                        topic_count) 
                            VALUES(:topic_title, 
                                :topic_description, 
                                :topic_slug, 
                                :topic_img, 
                                :topic_add_date,
                                :topic_seo_title,
                                :topic_merged_id,
                                :topic_user_id,
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
            'topic_user_id'         => $data['topic_user_id'],
            'topic_tl'              => $data['topic_tl'],
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
                    topic_user_id       = :topic_user_id, 
                    topic_tl            = :topic_tl,                    
                    topic_is_parent     = :topic_is_parent, 
                    topic_post_related  = :topic_post_related, 
                    topic_related       = :topic_related, 
                    topic_count         = :topic_count 
                        WHERE topic_id  = :topic_id";

        return  DB::run($sql, $params);
    }

    // Очистим привязку при изменение корневой темы
    public static function clearBinding($topic_id)
    {
        $sql = "UPDATE topics SET topic_parent_id = 0 WHERE topic_parent_id = :topic_id";

        return DB::run($sql, ['topic_id' => $topic_id]);
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

    // Количество тем созданное участником
    public static function countTopicsUser($user_id)
    {
        $sql = "SELECT 
                    topic_id, 
                    topic_title, 
                    topic_slug, 
                    topic_user_id 
                        FROM topics WHERE topic_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->rowCount();
    }
}
