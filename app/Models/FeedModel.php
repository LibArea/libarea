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
        $trust_level = $user_id === 0 ? 0 : (int) self::container()->user()->tl();
        $sort        = Sorting::day($sheet);
        $dateCond    = Sorting::getDateCondition();
        $start       = ($page - 1) * $limit;

        $sql = self::buildBaseSelect();
        $conditions = self::buildBaseConditions($trust_level, (bool) self::container()->user()->nsfw(), $dateCond);
        
        $params = [
            'uid'         => $user_id,
            'uid2'        => $user_id,
            'trust_level' => $trust_level,
        ];
        
        $feedData = self::buildFeedSpecificConditions($sheet, $slug, $topic);
        $conditions = array_merge($conditions, $feedData['conditions']);
        $params = array_merge($params, $feedData['params']);

        $sql .= " " . $feedData['joins'] . " WHERE " . implode(' AND ', $conditions);
        $sql .= " $sort LIMIT :start, :limit";
        
        $params['start'] = (int) $start;
        $params['limit'] = (int) $limit;

        $posts = DB::run($sql, $params)->fetchAll(\PDO::FETCH_ASSOC);
        return self::fetchFacets($posts);
    }

    public static function feedCount(string $sheet, string|int $slug, string $topic = ''): int
    {
        if (!in_array($sheet, self::ALLOWED_SHEETS, true)) {
            throw new \InvalidArgumentException("Invalid sheet type: $sheet");
        }

        $user_id     = (int) self::container()->user()->id();
        $trust_level = $user_id === 0 ? 0 : (int) self::container()->user()->tl();
        $dateCond    = Sorting::getDateCondition();

        $conditions = self::buildBaseConditions($trust_level, (bool) self::container()->user()->nsfw(), $dateCond);
        
        $params = [
            'trust_level' => $trust_level,
        ];

        $feedData = self::buildFeedSpecificConditions($sheet, $slug, $topic);
        $conditions = array_merge($conditions, $feedData['conditions']);
        $params = array_merge($params, $feedData['params']);

		$sql = "SELECT COUNT(p.post_id) FROM posts p " . $feedData['joins'] . " WHERE " . implode(' AND ', $conditions);

        return (int) DB::run($sql, $params)->fetchColumn();
    }
}
