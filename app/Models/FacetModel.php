<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class FacetModel extends Model
{
    const NO_REMOVAL = 0;
    const DELETED = 1;

    // All facets
    // Все фасеты
    public static function getFacetsAll(int $page, int $limit, string  $sort, string $type)
    {
        $signet = self::sorts($sort, $type);

        $start  = ($page - 1) * $limit;
        $sql    = "SELECT 
                    facet_id,
                    facet_title,
                    facet_description,
                    facet_short_description,
                    facet_slug,
                    facet_img,
                    facet_user_id,
                    facet_top_level,
                    facet_focus_count,
                    facet_count,
                    signed_facet_id, 
                    signed_user_id,
                    facet_type,
                    facet_is_deleted
                        FROM facets 
                            LEFT JOIN facets_signed ON signed_facet_id = facet_id AND signed_user_id = :user_id
                                $signet LIMIT :start, :limit";

        return DB::run($sql, ['start' => $start, 'limit' => $limit, 'user_id' => self::container()->user()->id()])->fetchAll();
    }

    public static function getFacetsAllCount(string $sort, string $type)
    {
        $signet = self::sorts($sort, $type);

        $sql    = "SELECT 
                    facet_id,
                    facet_type,
                    signed_facet_id, 
                    signed_user_id,
                    facet_is_deleted
                        FROM facets 
                            LEFT JOIN facets_signed ON signed_facet_id = facet_id AND signed_user_id = :user_id $signet";

        return DB::run($sql, ['user_id' => self::container()->user()->id()])->rowCount();
    }

    public static function sorts(string $sort, string $type)
    {
		$type = $type === 'blogs' ? 'blog' : 'topic';
		
        switch ($sort) {
            case 'my':
                $signet = "WHERE facet_type = '$type' AND facet_is_deleted = " . self::NO_REMOVAL . " AND signed_user_id = " .  self::container()->user()->id() . " ORDER BY facet_count DESC";
                break;
            case 'new':
                $signet = "WHERE facet_type = '$type' AND facet_is_deleted = " . self::NO_REMOVAL . " ORDER BY facet_id DESC";
                break;
            case 'all':
                $signet = "WHERE facet_type = '$type' AND facet_is_deleted = " . self::NO_REMOVAL . " ORDER BY facet_count DESC";
                break;
            case 'ban':
                $signet = "WHERE facet_type = '$type' AND facet_is_deleted = " . self::DELETED . " ORDER BY facet_id DESC";
                break;
            default:
                $signet = "WHERE facet_type = topic ORDER BY facet_count DESC";
                break;
        }

        return $signet;
    }

    // Cell information (id, slug) 
    // Информация по фасету (id, slug)
    public static function getFacet(int|string $params, string $name, string $type)
    {
        $sort = "facet_id = :params";
        if ($name === 'slug') {
            $sort = "facet_slug = :params";
        }

        $sql = "SELECT 
                    facet_id,
                    facet_title,
                    facet_description,
                    facet_short_description,
                    facet_type,
                    facet_info,
                    facet_slug,
                    facet_img,
                    facet_cover_art,
                    facet_date,
                    facet_seo_title,
                    facet_merged_id,
                    facet_top_level,
                    facet_user_id,
                    facet_tl,
                    facet_post_related,
                    facet_focus_count,
                    facet_count,
					facet_is_comments,
                    facet_is_deleted
                        FROM facets WHERE $sort AND facet_type = :type";

        return DB::run($sql, ['params' => $params, 'type' => $type])->fetch();
    }

    // Let's check the uniqueness of slug depending on the type of tree
    // Проверим уникальность slug в зависимости от типа дерева
    public static function uniqueSlug(string $facet_slug, string $facet_type)
    {
        $sql = "SELECT facet_slug, facet_type FROM facets WHERE facet_slug = :slug AND facet_type = :type";

        return DB::run($sql, ['slug' => $facet_slug, 'type' => $facet_type])->fetch();
    }

    // Let's check the uniqueness of id
    // Проверим уникальность id
    public static function uniqueById(int $facet_id)
    {
        $sql = "SELECT facet_id, facet_slug, facet_type, facet_user_id, facet_is_deleted FROM facets WHERE facet_id = :id";

        return DB::run($sql, ['id' => $facet_id])->fetch();
    }

    // Facet owner 
    // Собственник фасета
    public static function getOwnerFacet(int $user_id, string $type)
    {
        $sql = "SELECT 
                    facet_id,
                    facet_title,
                    facet_description,
                    facet_short_description,
                    facet_type,
                    facet_info,
                    facet_slug,
                    facet_img,
                    facet_cover_art,
                    facet_date,
                    facet_seo_title,
                    facet_merged_id,
                    facet_top_level,
                    facet_user_id,
                    facet_tl,
                    facet_post_related,
                    facet_focus_count,
                    facet_count,
                    facet_is_deleted
                        FROM facets 
                            WHERE facet_type = :type AND facet_user_id = :user_id AND facet_is_deleted = 0";

        return DB::run($sql, ['user_id' => $user_id, 'type' => $type])->fetchAll();
    }

    public static function addPostFacets(array $rows, int $post_id)
    {
        self::deleteRelation($post_id, 'post');

        foreach ($rows as $row) {
            $facet_id   = $row['id'];
            $sql        = "INSERT INTO facets_posts_relation (relation_facet_id, relation_post_id) 
                                VALUES ($facet_id, $post_id)";

            DB::run($sql);
        }

        return true;
    }

    public static function addItemFacets(array $rows, int $item_id)
    {
        self::deleteRelation($item_id, 'item');

        foreach ($rows as $row) {
            $facet_id   = $row['id'];
            if ($item_id == $row['id']) return true;
            $sql = "INSERT INTO facets_items_relation (relation_facet_id, relation_item_id) 
                        VALUES ($facet_id, $item_id)";

            DB::run($sql);
        }

        return true;
    }

    // Main trees
    // Основные деревья
    public static function addLowFacetRelation(array $rows, int $topic_id)
    {
        self::deleteRelation($topic_id, 'topic');

        foreach ($rows as $row) {
            $facet_id   = $row['id'];
            if ($topic_id == $row['id']) return true;
            $sql = "INSERT INTO facets_relation (facet_parent_id, facet_chaid_id) 
                        VALUES ($topic_id, $facet_id)";

            DB::run($sql);
        }

        return true;
    }

    // Cross -links
    // Перекрестные связи
    public static function addLowFacetMatching(array $rows, int $topic_id)
    {
        self::deleteRelation($topic_id, 'matching');

        foreach ($rows as $row) {
            $facet_id   = $row['id'];
            if ($topic_id == $row['id']) return true;
            $sql = "INSERT INTO facets_matching (matching_parent_id, matching_chaid_id) 
                        VALUES ($topic_id, $facet_id)";

            DB::run($sql);
        }

        return true;
    }

    public static function deleteRelation(int $id, string $type)
    {
        $sql = "DELETE FROM facets_items_relation WHERE relation_item_id = $id";
        if ($type == 'post') {
            $sql = "DELETE FROM facets_posts_relation WHERE relation_post_id = $id";
        } elseif ($type == 'topic') {
            $sql = "DELETE FROM facets_relation WHERE facet_parent_id = $id";
        } elseif ($type == 'matching') {
            $sql = "DELETE FROM facets_matching WHERE matching_parent_id = $id";
        }

        return DB::run($sql);
    }

    // Changing img
    // Изменение img
    public static function setImg(array $params)
    {
        return DB::run("UPDATE facets SET facet_img = :facet_img WHERE facet_id = :facet_id", $params);
    }

    // TOP of facet authors.
    // TOP авторов фасета.
    public static function getWriters(int $facet_id, int $limit)
    {
        $sql = "SELECT SUM(post_hits_count) as hits_count, 
                    rel.*
                        FROM facets_posts_relation 
                        LEFT JOIN posts ON relation_post_id = post_id 
                        LEFT JOIN
                        (
                            SELECT 
                                id,
                                login,                                 
                                avatar,
								created_at,
                                is_deleted,
                                about
                                    FROM users 
                        ) AS rel
                            ON rel.id = post_user_id 
                                WHERE relation_facet_id = :facet_id AND rel.is_deleted = 0 GROUP BY post_user_id 
                                    ORDER BY hits_count DESC LIMIT $limit";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Participant contribution
    // Вклад участника
    public static function participation(int $user_id)
    {
        $sql = " SELECT 
                    relation_facet_id as count,
                    rel.*
                    FROM posts
                    LEFT JOIN facets_posts_relation ON relation_post_id = post_id
                    LEFT JOIN
                        ( SELECT 
                            facet_id,
                            facet_slug,
                            facet_type,
                            facet_title
                                FROM facets
                        ) AS rel
                            ON rel.facet_id = relation_facet_id
                                WHERE post_user_id = :user_id AND facet_type = 'topic'
                                    GROUP BY relation_facet_id 
                                        ORDER BY count DESC LIMIT 5";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll();
    }

    // Topics to which the participant is not subscribed
    // Темы на которые участник не подписан
    public static function advice(array|bool $subscription)
    {
        $result = [];
        foreach ($subscription as $ind => $row) {
            $result[$ind] = $row['facet_id'];
        }

        $result = $result ? $result : ['1' => 1];
        $sql = "SELECT 
                    facet_id,
                    facet_title,
                    facet_slug,
                    facet_description,
                    facet_img
                        FROM facets
                            WHERE facet_id NOT IN(" . implode(',', $result ?? []) . ")
                                AND facet_type = 'topic' AND facet_is_deleted = 0 
                                    ORDER BY facet_count DESC LIMIT 5";

        return DB::run($sql)->fetchAll();
    }

    public static function add(array $params)
    {
        $sql = "INSERT INTO facets(facet_title, 
                        facet_description, 
                        facet_short_description,
                        facet_slug, 
                        facet_img,
                        facet_seo_title,
                        facet_user_id,
                        facet_type) 
                            VALUES(:facet_title, 
                                :facet_description, 
                                :facet_short_description, 
                                :facet_slug, 
                                :facet_img, 
                                :facet_seo_title,
                                :facet_user_id,
                                :facet_type)";

        DB::run($sql, $params);

        return  DB::run("SELECT LAST_INSERT_ID() as facet_id")->fetch();
    }

    public static function edit(array $params)
    {
        $sql = "UPDATE facets 
                    SET facet_title         = :facet_title,  
                    facet_description       = :facet_description, 
                    facet_short_description = :facet_short_description, 
                    facet_info              = :facet_info, 
                    facet_slug              = :facet_slug, 
                    facet_seo_title         = :facet_seo_title,
                    facet_user_id           = :facet_user_id, 
                    facet_top_level         = :facet_top_level, 
                    facet_post_related      = :facet_post_related, 
                    facet_type              = :facet_type,
					facet_is_comments 		= :facet_is_comments
                        WHERE facet_id      = :facet_id";

        return  DB::run($sql, $params);
    }

    // Faces created by the participant 
    // Грани созданные участником
    /** 
     * @param  int $user_id
     * @param  string $type (topic | blog)
     */
    public static function getFacetsUser(string $type)
    {
        $sql = "SELECT 
                    facet_id, 
                    facet_title, 
                    facet_slug, 
                    facet_user_id,
                    facet_type
                        FROM facets WHERE facet_user_id = :user_id AND facet_type = :type";

        return DB::run($sql, ['user_id' => self::container()->user()->id(), 'type' => $type])->fetchAll();
    }

    // Number of faces created by the contributor
    // Количество граней созданное участником
    /** 
     * @param  int $user_id
     * @param  string $type (topic | blog)
     */
    public static function countFacetsUser(int $user_id, string $type)
    {
        $sql = "SELECT facet_id FROM facets WHERE facet_user_id = :user_id AND facet_type = :type";

        return DB::run($sql, ['user_id' => $user_id, 'type' => $type])->rowCount();
    }

    // Participants subscribed to the topic
    // Участники подписанные на тему
    /** 
     * @param  int $page
     * @param  int $facet_id
     * @param  int $limit
     */
    public static function getFocusUsers(int $facet_id, int $page, int $limit)
    {
        $start = ($page - 1) * $limit;
        $sql = "SELECT 
                    signed_facet_id, 
                    signed_user_id,
                    id,
                    login,
                    avatar,
                    about
                      FROM facets_signed 
                        LEFT JOIN users ON id = signed_user_id
                          WHERE signed_facet_id = :facet_id LIMIT :start, :limit";

        return DB::run($sql, ['facet_id' => $facet_id, 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function getFocusUsersCount(int $facet_id)
    {
        $sql = "SELECT 
                    id
                      FROM facets_signed 
                        LEFT JOIN users ON id = signed_user_id WHERE signed_facet_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->rowCount();
    }

    // Up the structure of connected trees (PARENTS)
    // Вверх по структуре связанные деревья (РОДИТЕЛИ)
    /**
     * @param  int $facet_id
     */
    public static function getHighMatching(int $facet_id): false|array
    {
        $sql = "SELECT 
                    facet_id id,
                    facet_title value,
                    facet_slug,
                    facet_img,
                    facet_type,
                    matching_chaid_id,
                    matching_parent_id
                        FROM facets  
                            LEFT JOIN facets_matching on facet_id = matching_parent_id
                                WHERE matching_chaid_id  = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Down the structure  (CHILDREN)
    // Вниз по структуре связанных деревьев (ДЕТИ)
    /**
     * @param  int $facet_id
     * @return
     */
    public static function getLowMatching(int $facet_id)
    {
        $sql = "SELECT 
                    facet_id id,
                    facet_title as value,
                    facet_slug,
                    facet_img,
                    facet_type,
                    matching_chaid_id,
                    matching_parent_id
                        FROM facets
                            LEFT JOIN facets_matching on facet_id = matching_chaid_id 
                                WHERE matching_parent_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Up the structure of the main trees (PARENTS)
    // Вверх по структуре основных деревьев (РОДИТЕЛИ)
    /**
     * @param  int $facet_id
     * @return
     */
    public static function getHighLevelList(int $facet_id)
    {
        $sql = "SELECT 
                    facet_id id,
                    facet_title value,
                    facet_slug,
                    facet_img,
                    facet_type,
                    facet_chaid_id,
                    facet_parent_id
                        FROM facets  
                            LEFT JOIN facets_relation on facet_id = facet_parent_id
                                WHERE facet_chaid_id  = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Down the structure of the main trees (CHILDREN)
    // Вниз по структуре основных деревьев (ДЕТИ)
    /**
     * @param  int $facet_id
     * @internal
     */
    public static function getLowLevelList(int $facet_id)
    {
        $sql = "SELECT 
                    facet_id id,
                    facet_title value,
                    facet_slug,
                    facet_img,
                    facet_type,
                    facet_chaid_id,
                    facet_parent_id
                        FROM facets
                            LEFT JOIN facets_relation on facet_id = facet_chaid_id 
                                WHERE facet_parent_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    // Getting root level 
    // Получение root уровня
    public static function getTopLevelList()
    {
        $sql = "SELECT 
                    facet_id,
                    facet_title,
                    facet_slug,
                    facet_img,
                    facet_top_level,
                    facet_type                    
                        FROM facets  
                            WHERE facet_top_level = 0";

        return DB::run($sql)->fetchAll();
    }

    public static function setCover(array $params)
    {
        $sql = "UPDATE facets 
                    SET facet_cover_art = :facet_cover_art
                        WHERE facet_id  = :facet_id";

        return  DB::run($sql, $params);
    }

    public static function types()
    {
        return  DB::run('SELECT type_id, type_code, type_lang FROM facets_types');
    }

    // Team Members
    // Участники в команде
    public static function getUsersTeam(int $facet_id)
    {
        $sql = "SELECT 
                    id,
                    login as value,
                    avatar
                        FROM facets_users_team
                            LEFT JOIN users ON team_user_id = id
                                WHERE team_facet_id = :facet_id";

        return DB::run($sql, ['facet_id' => $facet_id])->fetchAll();
    }

    public static function getTeamFacets(string $type)
    {
        $sql = "SELECT
                    team_facet_id as facet_id,
                    facet_title
                        FROM facets_users_team 
                            LEFT JOIN facets ON team_facet_id = facet_id
                                WHERE team_user_id = :user_id AND facet_type = :type";

        return DB::run($sql, ['user_id' => self::container()->user()->id(), 'type' => $type])->fetchAll();
    }

    // Add, change users in the team
    // Добавим, изменим пользователей в команде
    public static function editUsersTeam(null|array $rows, int $facet_id)
    {
        self::deleteUsersTeam($facet_id);

		if ($rows === null) {
			return false;
		}

        foreach ($rows as $row) { 
            $user_id    = $row['id'];
            $sql        = "INSERT INTO facets_users_team (team_facet_id, team_user_id) VALUES (:facet_id, :user_id)";

            DB::run($sql, ['facet_id' => $facet_id, 'user_id' => $user_id]);
        }

        return true;
    }

    public static function deleteUsersTeam(int $facet_id)
    {
        return DB::run("DELETE FROM facets_users_team WHERE team_facet_id = :facet_id", ['facet_id' => $facet_id]);
    }

    public static function getFacetsTopicProfile($profile_id)
    {
        $sql    = "SELECT 
                    facet_id,
                    facet_title,
                    facet_slug,
                    facet_img
                        FROM facets 
                            LEFT JOIN facets_signed ON signed_facet_id = facet_id AND signed_user_id = $profile_id
                                WHERE facet_type = 'topic' AND signed_user_id = $profile_id ORDER BY facet_count DESC LIMIT 10";

        return DB::run($sql)->fetchAll();
    }

    public static function breadcrumb(int $facet_id)
    {
        $sql = "with recursive
            n (facet_id, facet_slug, facet_title, lvl) as (
                select facet_id, facet_slug, facet_title, 1 from facets where facet_id = :id
         
         union all
            select c.facet_id, c.facet_slug, c.facet_title, n.lvl + 1
                from n
                    join facets_relation r on r.facet_chaid_id = n.facet_id
                    join facets c on c.facet_id = r.facet_parent_id
        )
        select facet_slug link, facet_title name from n where lvl <= 5 ORDER BY lvl DESC";

        return DB::run($sql, ['id' => $facet_id])->fetchAll();
    }
}
