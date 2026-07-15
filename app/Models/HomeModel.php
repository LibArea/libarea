<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;
use App\Services\Sorting;
use App\Traits\PostQueryTrait;

class HomeModel extends Model
{
    use PostQueryTrait;

    public static $limit = 15;
    
    private const ALLOWED_TYPES = [
        'feed', 'question', 'post', 'article', 'note', 'all', 'deleted'
    ];

    public static function feed(array $signed, int $page, string $type = 'feed'): array|false
    {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            throw new \InvalidArgumentException("Invalid feed type: $type");
        }

        $user_id      = (int) self::container()->user()->id();
        $trust_level  = (int) self::container()->user()->tl();
        $nsfw_enabled = (bool) self::container()->user()->nsfw();
        [$display, $displayParams] = self::display($type);
        $sort         = Sorting::day($type);
        $dateCond     = Sorting::getDateCondition();
        $start        = ($page - 1) * self::$limit;

        $sql = self::buildBaseSelect();
        $conditions = self::buildBaseConditions($trust_level, $nsfw_enabled, $dateCond);
        $conditions[] = $display;

        $params = array_merge([
            'uid'         => $user_id,
            'uid2'        => $user_id,
            'trust_level' => $trust_level,
        ], $displayParams);
        
        $sql .= " WHERE " . implode(' AND ', $conditions);

        $sql = self::addIgnoredFilter($sql, $params, $user_id);

        if ($type !== 'all' && $user_id) {
            $result = self::addSubscriptionFilter($sql, $params, $signed);
            if ($result === false) return false;
            $sql = $result;
        }

        $sql .= " $sort LIMIT :start, :limit";
        $params['start'] = (int) $start;
        $params['limit'] = (int) self::$limit;

        $posts = DB::run($sql, $params)->fetchAll(\PDO::FETCH_ASSOC);
        return self::fetchFacets($posts) ?: false;
    }

    public static function feedCount(array $signed, string $type = 'feed'): int
    {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            throw new \InvalidArgumentException("Invalid feed type: $type");
        }

        $user_id      = (int) self::container()->user()->id();
        $trust_level  = (int) self::container()->user()->tl();
        $nsfw_enabled = (bool) self::container()->user()->nsfw();
        [$display, $displayParams] = self::display($type);
        $dateCond     = Sorting::getDateCondition();

        $conditions = self::buildBaseConditions($trust_level, $nsfw_enabled, $dateCond);
        $conditions[] = $display;

        $sql = "SELECT COUNT(p.post_id) FROM posts p WHERE " . implode(' AND ', $conditions);
        $params = array_merge(['trust_level' => $trust_level], $displayParams);

        $sql = self::addIgnoredFilter($sql, $params, $user_id);

        if ($type !== 'all' && $user_id) {
            $result = self::addSubscriptionFilter($sql, $params, $signed);
            if ($result === false) return 0;
            $sql = $result;
        }

        return (int) DB::run($sql, $params)->fetchColumn();
    }

    public static function display(string $type): array
    {
        $countLike = (int) config('feed', 'countLike');

        return match ($type) {
            'question', 'post', 'article', 'note'
                => [
                    "p.post_type = :type",
                    ['type' => $type]
                ],
            'deleted'
                => ["p.post_is_deleted = 1", []],
            'all'
                => ["1=1", []],
            default => self::container()->user()->active()
                ? ["1=1", []]
                : ["p.post_votes >= :countLike", ['countLike' => $countLike]],
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