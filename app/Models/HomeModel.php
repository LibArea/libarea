<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

use App\Models\IgnoredModel;
use Sorting;


class HomeModel extends Model
{
    public static $limit = 15;

    /**
     * Posts on the central page
     * Посты на центральной странице
     *
     * @param integer $page
     * @param string $type
     * @return void
     */
    public static function feed(array $signed, int $page, string $type = 'feed'): array|false
    {
        $user_id = self::container()->user()->id();

        $resultNotUser = [];
        $ignored = IgnoredModel::getIgnoredUsers(50);
        foreach ($ignored as $ind => $row) {
            $resultNotUser[$ind] = $row['ignored_id'];
        }

        $ignoring = "post_user_id NOT IN(0) AND";
        if ($resultNotUser) $ignoring = "post_user_id NOT IN(" . implode(',', $resultNotUser ?? []) . ") AND";

        $subscription = "";
        if ($type !== 'all') {
            if ($user_id) {
                $subscription = "relation_facet_id IN(0) AND";
                if ($signed) $subscription = "relation_facet_id IN(" . implode(',', $signed ?? []) . ") AND";
            }
        }

        $display = self::display($type);
        $sort = Sorting::day($type);

        $nsfw = self::container()->user()->nsfw() ? "" : "post_nsfw = 0 AND";

        $start = ($page - 1) * self::$limit;
        $sql = "SELECT DISTINCT
                post_id,
                post_title,
                post_slug,
                post_feature,
                post_translation,
                post_draft,
                post_nsfw,
                post_hidden,
                post_date,
                post_published,
                post_user_id,
                post_votes,
                post_hits_count,
                post_comments_count,
                post_content,
                post_content_img,
                post_thumb_img,
                post_merged_id,
                post_closed,
                post_tl,
                post_lo,
                post_top,
                post_url_domain,
                post_is_deleted,
                rel.*,
                votes_post_item_id, votes_post_user_id,
                u.id, u.login, u.avatar, u.created_at, 
                fav.tid, fav.user_id, fav.action_type 
                    FROM facets_posts_relation 
                        LEFT JOIN posts ON relation_post_id = post_id
                        LEFT JOIN (
                            SELECT 
                                relation_post_id,
                                GROUP_CONCAT(facet_type, '@', facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets
                                LEFT JOIN facets_posts_relation 
                                    on facet_id = relation_facet_id
                                    GROUP BY relation_post_id
                        ) AS rel
                             ON rel.relation_post_id= post_id
                            LEFT JOIN users u ON u.id = post_user_id
                            LEFT JOIN favorites fav ON fav.tid = post_id 
                                AND fav.user_id = :uid AND fav.action_type = 'post'  
                            LEFT JOIN votes_post 
                                ON votes_post_item_id = post_id AND votes_post_user_id = :uid2
                                    WHERE post_type != 'page' AND post_draft = 0 AND $ignoring $nsfw $subscription $display $sort LIMIT :start, :limit";

        return DB::run($sql, ['uid' => $user_id, 'uid2' => $user_id, 'start' => $start, 'limit' => self::$limit])->fetchAll();
    }

    /**
     * Number of posts
     * Количество постов
     *
     * @param string $type
     */
    public static function feedCount(array $signed, string $type = 'feed')
    {
        $resultNotUser = [];
        $ignored = IgnoredModel::getIgnoredUsers(50);
        foreach ($ignored as $ind => $row) {
            $resultNotUser[$ind] = $row['ignored_id'];
        }

        $ignoring = "post_user_id NOT IN(0) AND";
        if ($resultNotUser) $ignoring = "post_user_id NOT IN(" . implode(',', $resultNotUser ?? []) . ") AND";

        $subscription = "";
        if ($type !== 'all') {
            if (self::container()->user()->id()) {
                $subscription = "f_id IN(0) AND";
                if ($signed) $subscription = "f_id IN(" . implode(',', $signed ?? []) . ") AND";
            }
        }

        $nsfw = (self::container()->user()->tl()) ? "" : "post_nsfw = 0 AND";

        $display = self::display($type);


        $sql = "SELECT 
                    post_id
                        FROM posts
                            LEFT JOIN
                            (
                                SELECT 
                                    MAX(facet_id) as f_id,
                                        relation_post_id
                                        FROM facets  
                                            LEFT JOIN facets_posts_relation on facet_id = relation_facet_id GROUP BY relation_post_id
                            ) AS rel
                                ON rel.relation_post_id = post_id 
                                    INNER JOIN users ON id = post_user_id
                                        WHERE post_type != 'page' AND post_draft = 0 AND $ignoring $nsfw $subscription $display";

        return ceil(DB::run($sql)->rowCount() / self::$limit);
    }

    public static function display(string $type)
    {
        $countLike = config('feed', 'countLike');
        $trust_level = self::container()->user()->tl();

        switch ($type) {
            case 'questions':
                $display =  "post_is_deleted = 0 AND post_tl <=  $trust_level  AND post_feature = 1";
                break;
            case 'posts':
                $display =  "post_is_deleted = 0 AND post_tl <=  $trust_level  AND post_feature = 0";
                break;
            case 'deleted':
                $display =  "post_is_deleted = 1";
                break;
            case 'all':
                $display =  "post_is_deleted = 0 AND post_tl <= $trust_level";
                break;
            default:
                $display =  "post_is_deleted = 0 AND post_votes >= $countLike AND post_tl <= $trust_level";
                if (self::container()->user()->active()) {
                    $display =  "post_is_deleted = 0 AND post_tl <= $trust_level";
                }
        }

        return $display;
    }

    /**
     * Latest sites
     * Последние сайты
     *
     * @param integer $limit
     * @return array|false
     */
    public static function latestItems(int $limit = 3): array|false
    {
        $sql = "SELECT item_id, item_title, item_slug, item_domain FROM items WHERE item_published = 1 AND item_is_deleted = 0 ORDER BY item_id DESC LIMIT :limit";

        return DB::run($sql, ['limit' => $limit])->fetchAll();
    }

    /**
     * Facets (topic, blogs) all / subscribed
     * Фасеты (темы, блоги) все / подписан
     */
    public static function getSubscription()
    {
        $sql = "SELECT 
                    facet_id, 
                    facet_slug, 
                    facet_title,
                    facet_user_id,
                    facet_img,
                    facet_type                   
                        FROM facets 
                           LEFT JOIN facets_signed ON signed_facet_id = facet_id 
                                WHERE signed_user_id = :id AND (facet_type = 'topic' OR facet_type = 'blog')
                                    ORDER BY facet_id DESC";

        return DB::run($sql, ['id' => self::container()->user()->id()])->fetchAll();
    }
}
