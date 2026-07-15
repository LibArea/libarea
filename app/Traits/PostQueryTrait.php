<?php

declare(strict_types=1);

namespace App\Traits;

use Hleb\Static\DB;

trait PostQueryTrait
{
    protected static function buildBaseSelect(): string
    {
        return "SELECT 
                    p.post_id, p.post_title, p.post_slug, p.post_translation,
                    p.post_draft, p.post_type, p.post_nsfw, p.post_hidden,
                    p.post_date, p.post_published, p.post_user_id, p.post_votes,
                    p.post_hits_count, p.post_comments_count, p.post_content,
                    p.post_content_img, p.post_thumb_img, p.post_merged_id,
                    p.post_closed, p.post_tl, p.post_ip, p.post_lo, p.post_top,
                    p.post_url_domain, p.post_is_deleted,
                    u.id AS user_id, u.login, u.avatar, u.created_at,
                    fav.tid, fav.user_id AS fav_user_id, fav.action_type,
                    vp.votes_post_item_id, vp.votes_post_user_id
                FROM posts p
                INNER JOIN users u ON u.id = p.post_user_id
                LEFT JOIN favorites fav 
                    ON fav.tid = p.post_id 
                    AND fav.user_id = :uid 
                    AND fav.action_type = 'post'
                LEFT JOIN votes_post vp 
                    ON vp.votes_post_item_id = p.post_id 
                    AND vp.votes_post_user_id = :uid2";
    }

    protected static function buildBaseConditions(int $trust_level, bool $nsfw_enabled, string $dateCond = ''): array
    {
        $conditions = [
            "p.post_type != 'page'",
            "p.post_draft = 0",
            "p.post_is_deleted = 0",
            "p.post_tl <= :trust_level",
        ];

        if (!$nsfw_enabled) {
            $conditions[] = "p.post_nsfw = 0";
        }

        if ($dateCond !== '') {
            $conditions[] = ltrim($dateCond, 'AND ');
        }

        return $conditions;
    }

    protected static function buildFeedSpecificConditions(string $sheet, string|int $slug, string $topic): array
    {
        $conditions = [];
        $joins = "";
        $params = [];

        switch ($sheet) {
            case 'facet.feed':
            case 'question':
            case 'article':
            case 'post':
            case 'note':
            case 'recommend':
                $joins = "INNER JOIN facets_posts_relation fpr ON fpr.relation_post_id = p.post_id
                          INNER JOIN facets f ON f.facet_id = fpr.relation_facet_id AND f.facet_slug = :slug";
                $params['slug'] = (string) $slug;
                
                if (in_array($sheet, ['question', 'article', 'post', 'note'], true)) {
                    $conditions[] = "p.post_type = :sheet_type";
                    $params['sheet_type'] = $sheet;
                }
                if ($sheet === 'recommend') {
                    $conditions[] = "p.post_is_recommend = 1";
                }
                if (!self::container()->user()->admin()) {
                    $conditions[] = "p.post_hidden = 0";
                }
                break;

            case 'facet.feed.topic':
                $joins = "INNER JOIN facets_posts_relation fpr1 ON fpr1.relation_post_id = p.post_id
                          INNER JOIN facets f1 ON f1.facet_id = fpr1.relation_facet_id AND f1.facet_slug = :slug
                          INNER JOIN facets_posts_relation fpr2 ON fpr2.relation_post_id = p.post_id
                          INNER JOIN facets f2 ON f2.facet_id = fpr2.relation_facet_id AND f2.facet_slug = :topic";
                $params['slug']  = (string) $slug;
                $params['topic'] = (string) $topic;
                $conditions[] = "p.post_type = 'post'";
                if (!self::container()->user()->admin()) {
                    $conditions[] = "p.post_hidden = 0";
                }
                break;

            case 'web.feed':
                $conditions[] = "p.post_url_domain = :slug";
                $params['slug'] = (string) $slug;
                if (!self::container()->user()->admin()) {
                    $conditions[] = "p.post_hidden = 0";
                }
                break;

            case 'profile.posts':
                $conditions[] = "p.post_user_id = :user_id";
                $params['user_id'] = (int) $slug;
                break;
        }

        return [
            'conditions' => $conditions,
            'joins'      => $joins,
            'params'     => $params
        ];
    }

    protected static function fetchFacets(array $posts): array
    {
        if (!$posts) return [];

        $post_ids_str = implode(',', array_map('intval', array_column($posts, 'post_id')));
        $facets_sql = "SELECT 
                            relation_post_id,
                            GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                       FROM facets_posts_relation
                       INNER JOIN facets ON facet_id = relation_facet_id
                       WHERE relation_post_id IN ($post_ids_str)
                       GROUP BY relation_post_id";

        $facets_map = [];
        foreach (DB::run($facets_sql)->fetchAll(\PDO::FETCH_ASSOC) as $facet) {
            $facets_map[$facet['relation_post_id']] = $facet['facet_list'];
        }

        foreach ($posts as &$post) {
            $post['facet_list'] = $facets_map[$post['post_id']] ?? '';
        }

        return $posts;
    }

    protected static function addIgnoredFilter(string $sql, array &$params, int $user_id): string
    {
        if ($user_id) {
            $sql .= " AND NOT EXISTS (
                        SELECT 1 FROM users_ignored ui 
                        WHERE ui.user_id = :uid_ignored AND ui.ignored_id = p.post_user_id
                      )";
            $params['uid_ignored'] = $user_id;
        }
        return $sql;
    }

    protected static function addSubscriptionFilter(string $sql, array &$params, array $signed): string|false
    {
        if (!empty($signed)) {
            $signed_str = implode(',', array_map('intval', $signed));
            $sql .= " AND EXISTS (
                        SELECT 1 FROM facets_posts_relation fpr 
                        WHERE fpr.relation_post_id = p.post_id 
                          AND fpr.relation_facet_id IN ($signed_str)
                     )";
        } else {
            return false;
        }
        return $sql;
    }
}