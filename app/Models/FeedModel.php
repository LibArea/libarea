<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;
use Sorting;

class FeedModel extends Model
{
    private const ALLOWED_SHEETS = [
        'facet.feed', 'facet.feed.topic', 'question', 'article', 
        'post', 'note', 'recommend', 'web.feed', 'profile.posts'
    ];

    public static function feed(int $page, int $limit, string $sheet, string|int $slug, string $topic = ''): array
    {
        if (!in_array($sheet, self::ALLOWED_SHEETS, true)) {
            throw new \InvalidArgumentException("Invalid sheet type: $sheet");
        }

        $user_id     = (int) self::container()->user()->id();
        $trust_level = ($user_id === 0) ? 0 : (int) self::container()->user()->tl();
        $sort        = Sorting::day($sheet);
        $dateCond    = Sorting::getDateCondition();
        $start       = ($page - 1) * $limit;
        
        $slug  = (string) $slug;
        $topic = (string) $topic;

        // ★ Собираем WHERE через массив условий
        $conditions = [
            "p.post_draft = 0",
            "p.post_is_deleted = 0",
            "p.post_tl <= $trust_level",
        ];

        if (!self::container()->user()->nsfw()) {
            $conditions[] = "p.post_nsfw = 0";
        }

        if ($dateCond !== '') {
            $dateCond = ltrim($dateCond, 'AND ');
            $conditions[] = $dateCond;
        }

        $sql = "SELECT 
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
                INNER JOIN users u ON u.id = p.post_user_id";

        $joins  = "";
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
                $params['slug'] = $slug;
                
                if (in_array($sheet, ['question', 'article', 'post', 'note'])) {
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
                $params['slug']  = $slug;
                $params['topic'] = $topic;
                $conditions[] = "p.post_type = :sheet_type";
                $params['sheet_type'] = 'post';
                if (!self::container()->user()->admin()) {
                    $conditions[] = "p.post_hidden = 0";
                }
                break;

            case 'web.feed':
                $conditions[] = "p.post_url_domain = :slug";
                $params['slug'] = $slug;
                if (!self::container()->user()->admin()) {
                    $conditions[] = "p.post_hidden = 0";
                }
                break;

            case 'profile.posts':
                $conditions[] = "p.post_user_id = :slug";
                $params['slug'] = $slug;
                break;
        }

        $sql .= " $joins
                  LEFT JOIN favorites fav ON fav.tid = p.post_id AND fav.user_id = :uid AND fav.action_type = 'post'
                  LEFT JOIN votes_post vp ON vp.votes_post_item_id = p.post_id AND vp.votes_post_user_id = :uid2
                  WHERE " . implode(' AND ', $conditions) . "
                  $sort
                  LIMIT :start, :limit";

        $params['uid']   = $user_id;
        $params['uid2']  = $user_id;
        $params['start'] = (int) $start;
        $params['limit'] = (int) $limit;

        $posts = DB::run($sql, $params)->fetchAll(\PDO::FETCH_ASSOC);

        if (!$posts) {
            return [];
        }

        $post_ids     = array_column($posts, 'post_id');
        $post_ids_str = implode(',', array_map('intval', $post_ids));

        $facets_sql = "SELECT 
                            fpr.relation_post_id,
                            GROUP_CONCAT(f.facet_type, '@', f.facet_slug, '@', f.facet_title SEPARATOR '@') AS facet_list
                       FROM facets_posts_relation fpr
                       INNER JOIN facets f ON f.facet_id = fpr.relation_facet_id
                       WHERE fpr.relation_post_id IN ($post_ids_str)
                       GROUP BY fpr.relation_post_id";

        $facets = DB::run($facets_sql)->fetchAll(\PDO::FETCH_ASSOC);

        $facets_map = [];
        foreach ($facets as $facet) {
            $facets_map[$facet['relation_post_id']] = $facet['facet_list'];
        }

        foreach ($posts as &$post) {
            $post['facet_list'] = $facets_map[$post['post_id']] ?? '';
        }

        return $posts;
    }

    public static function feedCount(string $sheet, string|int $slug, string $topic = ''): int
    {
        if (!in_array($sheet, self::ALLOWED_SHEETS, true)) {
            throw new \InvalidArgumentException("Invalid sheet type: $sheet");
        }

        $user_id     = (int) self::container()->user()->id();
        $trust_level = ($user_id === 0) ? 0 : (int) self::container()->user()->tl();
        $dateCond    = Sorting::getDateCondition();
        
        $slug  = (string) $slug;
        $topic = (string) $topic;

        // ★ Собираем WHERE через массив условий
        $conditions = [
            "p.post_draft = 0",
            "p.post_is_deleted = 0",
            "p.post_tl <= $trust_level",
        ];

        if (!self::container()->user()->nsfw()) {
            $conditions[] = "p.post_nsfw = 0";
        }

        if ($dateCond !== '') {
            $dateCond = ltrim($dateCond, 'AND ');
            $conditions[] = $dateCond;
        }

        $sql    = "SELECT COUNT(DISTINCT p.post_id) FROM posts p";
        $joins  = "";
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
                $params['slug'] = $slug;
                
                if (in_array($sheet, ['question', 'article', 'post', 'note'])) {
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
                $params['slug']  = $slug;
                $params['topic'] = $topic;
                $conditions[] = "p.post_type = :sheet_type";
                $params['sheet_type'] = 'post';
                if (!self::container()->user()->admin()) {
                    $conditions[] = "p.post_hidden = 0";
                }
                break;

            case 'web.feed':
                $conditions[] = "p.post_url_domain = :slug";
                $params['slug'] = $slug;
                if (!self::container()->user()->admin()) {
                    $conditions[] = "p.post_hidden = 0";
                }
                break;

            case 'profile.posts':
                $conditions[] = "p.post_user_id = :slug";
                $params['slug'] = $slug;
                break;
        }

        $sql .= " $joins WHERE " . implode(' AND ', $conditions);

        return (int) DB::run($sql, $params)->fetchColumn();
    }
}