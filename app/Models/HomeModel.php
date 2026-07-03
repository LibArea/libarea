<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;
use Sorting;

class HomeModel extends Model
{
    public static $limit = 15;
    
    private const ALLOWED_TYPES = [
        'feed', 'question', 'post', 'article', 'note', 'all', 'deleted'
    ];

    public static function feed(array $signed, int $page, string $type = 'feed'): array|false
    {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            throw new \InvalidArgumentException("Invalid feed type: $type");
        }

        $user_id   = (int) self::container()->user()->id();
        [$display, $displayParams] = self::display($type);
        $sort      = Sorting::day($type);
        $dateCond  = Sorting::getDateCondition();
        $start     = ($page - 1) * self::$limit;

        // ★ Собираем WHERE через массив условий
        $conditions = [
            "p.post_type != 'page'",
            "p.post_draft = 0",
        ];

        if (!self::container()->user()->nsfw()) {
            $conditions[] = "p.post_nsfw = 0";
        }

        // Добавляем условия из display()
        $conditions[] = $display;

        // Добавляем условие по дате (если есть)
        if ($dateCond !== '') {
            // $dateCond возвращает строку вида "AND p.post_date > ..."
            // Убираем ведущий AND и добавляем как условие
            $dateCond = ltrim($dateCond, 'AND ');
            $conditions[] = $dateCond;
        }

        $sql = "SELECT 
                    p.post_id, p.post_title, p.post_slug, p.post_translation,
                    p.post_draft, p.post_type, p.post_nsfw, p.post_hidden,
                    p.post_date, p.post_published, p.post_user_id, p.post_votes,
                    p.post_hits_count, p.post_comments_count, p.post_content,
                    p.post_content_img, p.post_thumb_img, p.post_merged_id,
                    p.post_closed, p.post_tl, p.post_lo, p.post_top,
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
                    AND vp.votes_post_user_id = :uid2
                WHERE " . implode(' AND ', $conditions);

        $params = array_merge([
            'uid'  => $user_id,
            'uid2' => $user_id,
        ], $displayParams);

        // Игнорируемые пользователи
        if ($user_id) {
            $sql .= " AND NOT EXISTS (
                        SELECT 1 FROM users_ignored ui 
                        WHERE ui.user_id = :uid3 
                          AND ui.ignored_id = p.post_user_id
                      )";
            $params['uid3'] = $user_id;
        }

        // Подписки
        if ($type !== 'all' && $user_id) {
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
        }

        $sql .= " $sort LIMIT :start, :limit";
        $params['start'] = (int) $start;
        $params['limit'] = (int) self::$limit;

        $posts = DB::run($sql, $params)->fetchAll(\PDO::FETCH_ASSOC);

        if (!$posts) {
            return false;
        }

        // Получаем facets только для текущей страницы
        $post_ids     = array_column($posts, 'post_id');
        $post_ids_str = implode(',', array_map('intval', $post_ids));

        $facets_sql = "SELECT 
                            fpr.relation_post_id,
                            GROUP_CONCAT(
                                f.facet_type, '@', f.facet_slug, '@', f.facet_title 
                                SEPARATOR '@'
                            ) AS facet_list
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

    public static function feedCount(array $signed, string $type = 'feed'): int
    {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            throw new \InvalidArgumentException("Invalid feed type: $type");
        }

        $user_id   = (int) self::container()->user()->id();
        [$display, $displayParams] = self::display($type);
        $dateCond  = Sorting::getDateCondition();

        // ★ Собираем WHERE через массив условий
        $conditions = [
            "p.post_type != 'page'",
            "p.post_draft = 0",
        ];

        if (!self::container()->user()->nsfw()) {
            $conditions[] = "p.post_nsfw = 0";
        }

        $conditions[] = $display;

        if ($dateCond !== '') {
            $dateCond = ltrim($dateCond, 'AND ');
            $conditions[] = $dateCond;
        }

        $sql = "SELECT COUNT(DISTINCT p.post_id)
                FROM posts p
                WHERE " . implode(' AND ', $conditions);

        $params = $displayParams;

        if ($user_id) {
            $sql .= " AND NOT EXISTS (
                        SELECT 1 FROM users_ignored ui 
                        WHERE ui.user_id = :uid AND ui.ignored_id = p.post_user_id
                      )";
            $params['uid'] = $user_id;
        }

        if ($type !== 'all' && $user_id) {
            if (!empty($signed)) {
                $signed_str = implode(',', array_map('intval', $signed));
                $sql .= " AND EXISTS (
                            SELECT 1 FROM facets_posts_relation fpr 
                            WHERE fpr.relation_post_id = p.post_id 
                              AND fpr.relation_facet_id IN ($signed_str)
                         )";
            } else {
                return 0;
            }
        }

        return (int) DB::run($sql, $params)->fetchColumn();
    }

    public static function display(string $type): array
    {
        $countLike   = (int) config('feed', 'countLike');
        $trust_level = (int) self::container()->user()->tl();

        return match ($type) {
            'question', 'post', 'article', 'note'
                => [
                    "p.post_is_deleted = 0 AND p.post_tl <= $trust_level AND p.post_type = :type",
                    ['type' => $type]
                ],
            'deleted'
                => ["p.post_is_deleted = 1", []],
            'all'
                => ["p.post_is_deleted = 0 AND p.post_tl <= $trust_level", []],
            default => self::container()->user()->active()
                ? ["p.post_is_deleted = 0 AND p.post_tl <= $trust_level", []]
                : ["p.post_is_deleted = 0 AND p.post_votes >= $countLike AND p.post_tl <= $trust_level", []],
        };
    }

    public static function getSubscription(): array
    {
        $sql = "SELECT facet_id                  
                FROM facets 
                INNER JOIN facets_signed fs ON fs.signed_facet_id = facets.facet_id 
                WHERE fs.signed_user_id = :id 
                    AND facets.facet_type IN ('topic', 'blog')
                ORDER BY facets.facet_id DESC";

        return DB::run($sql, ['id' => self::container()->user()->id()])->fetchAll();
    }
}