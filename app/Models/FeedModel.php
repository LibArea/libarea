<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;
use App\Services\Sorting;
use App\Traits\PostQueryTrait;

class FeedModel extends Model
{
    use PostQueryTrait;

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

        // ★ Используем общие методы из трейта
        $sql = self::buildBaseSelect();
        $conditions = self::buildBaseConditions($trust_level, (bool) self::container()->user()->nsfw(), $dateCond);

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

        $params['uid']   = $user_id;
        $params['uid2']  = $user_id;
        $params['start'] = (int) $start;
        $params['limit'] = (int) $limit;

        $sql .= " $sort LIMIT :start, :limit";

        $posts = DB::run($sql, $params)->fetchAll(\PDO::FETCH_ASSOC);

        // ★ Используем общий метод из трейта
        return self::fetchFacets($posts);
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

        $conditions = self::buildBaseConditions($trust_level, (bool) self::container()->user()->nsfw(), $dateCond);

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