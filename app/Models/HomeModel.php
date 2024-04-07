<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

use App\Traits\SortingPostDay;
use App\Models\IgnoredModel;

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
    public static function feed(int $page, string $type = 'feed'): array|false
    {
        $user_id = self::container()->user()->id();

        $result = [];
        foreach (self::userReads() as $ind => $row) {
            $result[$ind] = $row['facet_id'];
        }

        $resultNotUser = [];
        $ignored = IgnoredModel::getIgnoredUsers(50);
        foreach ($ignored as $ind => $row) {
            $resultNotUser[$ind] = $row['ignored_id'];
        }

        $ignoring = "post_user_id NOT IN(0) AND";
        if ($resultNotUser) $ignoring = "post_user_id NOT IN(" . implode(',', $resultNotUser ?? []) . ") AND";

        $subscription = "";
        if ($type != 'all') {
            if ($user_id) {
                $subscription = "relation_facet_id IN(0) AND";
                if ($result) $subscription = "relation_facet_id IN(" . implode(',', $result ?? []) . ") AND";
            }
        }

        $display = self::display($type);
        $sort = SortingPostDay::get($type);

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
     * @return void
     */
    public static function feedCount(string $type)
    {
        $result = [];
        foreach (self::userReads() as $ind => $row) {
            $result[$ind] = $row['facet_id'];
        }

        $resultNotUser = [];
        $ignored = IgnoredModel::getIgnoredUsers(50);
        foreach ($ignored as $ind => $row) {
            $resultNotUser[$ind] = $row['ignored_id'];
        }

        $ignoring = "post_user_id NOT IN(0) AND";
        if ($resultNotUser) $ignoring = "post_user_id NOT IN(" . implode(',', $resultNotUser ?? []) . ") AND";

        $subscription = "";
        if ($type != 'all') {
            if (self::container()->user()->id()) {
                $subscription = "f_id IN(0) AND";
                if ($result) $subscription = "f_id IN(" . implode(',', $result ?? []) . ") AND";
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
     * The last 5 responses on the main page
     * Последние 5 ответа на главной
     *
     * @param integer $limit
     * @return array
     */
    public static function latestComments(int $limit = 5): array
    {
        $trust_level = self::container()->user()->tl();
        $user_comment = "AND post_tl = 0";

        if ($user_id = self::container()->user()->id()) {
            $user_comment = "AND comment_user_id != $user_id AND post_tl <= $trust_level";
        }

        $hidden = self::container()->user()->admin() ? "" : "AND post_hidden = 0";

        $sql = "SELECT 
                    comment_id,
                    comment_post_id,
                    comment_content,
                    comment_date,
                    post_id,
					post_title,
                    post_slug,
                    post_hidden,
                    login,
                    avatar
                        FROM comments 
                        LEFT JOIN users ON id = comment_user_id
                        RIGHT JOIN posts ON post_id = comment_post_id
                            WHERE comment_is_deleted = 0 AND post_is_deleted = 0 $hidden 
                                $user_comment AND post_type = 'post'
                                    ORDER BY comment_id DESC LIMIT :limit";

        return DB::run($sql, ['limit' => $limit])->fetchAll();
    }

    public static function latestItems(int $limit = 3): array|false
    {
        $sql = "SELECT item_id, item_title, item_slug, item_domain FROM items WHERE item_published = 1 AND item_is_deleted = 0 ORDER BY item_id DESC LIMIT :limit";

        return DB::run($sql, ['limit' => $limit])->fetchAll();
    }

    public static function userReads(): iterable|object
    {
        $sql = "SELECT signed_facet_id as facet_id FROM facets_signed WHERE signed_user_id = :user_id";

        return DB::run($sql, ['user_id' => self::container()->user()->id()])->fetchAll();
    }

    /**
     * Facets (topic, blogs) all / subscribed
     * Фасеты (темы, блоги) все / подписан
     *
     * @return boolean
     */
    public static function getSubscription(): array|bool
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
