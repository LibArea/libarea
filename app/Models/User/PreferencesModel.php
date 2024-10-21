<?php

declare(strict_types=1);

namespace App\Models\User;

use Hleb\Base\Model;
use Hleb\Static\DB;

class PreferencesModel extends Model
{
  public static $limit = 50;

  public const TYPE_TOPICS_BLOG = 1;  // Topics and blogs in the right "Reading" section
  public const TYPE_BLOCKS = 2;  // Various blocks, for example, recent sites

  public static function get($page)
  {
    $user_id = self::container()->user()->id();
    $start = ($page - 1) * self::$limit;

    $sql = "SELECT 
                    f.facet_id, 
                    f.facet_slug, 
                    f.facet_title,
                    f.facet_user_id,
                    f.facet_img,
                    f.facet_type,
					fs.signed_facet_id,
					pr.facet_id as facet_output
                        FROM facets f
                           LEFT JOIN facets_signed fs ON fs.signed_facet_id = f.facet_id 
						   LEFT JOIN users_preferences pr ON f.facet_id = pr.facet_id AND pr.user_id = $user_id
                                WHERE fs.signed_user_id = $user_id AND (f.facet_type = 'topic' OR f.facet_type = 'blog')
                                    ORDER BY facet_output DESC LIMIT :start, :limit";

    return DB::run($sql, ['start' => $start, 'limit' => self::$limit])->fetchAll();
  }


  public static function getCount()
  {
    $sql = "SELECT f.facet_id 
					FROM facets f
                        LEFT JOIN facets_signed fs ON fs.signed_facet_id = f.facet_id 
							WHERE fs.signed_user_id = :user_id AND (f.facet_type = 'topic' OR f.facet_type = 'blog')";

    return ceil(DB::run($sql, ['user_id' => self::container()->user()->id()])->rowCount() / self::$limit);
  }

  public static function getBlocks()
  {
    $sql = "SELECT facet_id FROM users_preferences WHERE user_id = :user_id AND type = :type";

    return DB::run($sql, ['user_id' => self::container()->user()->id(), 'type' => self::TYPE_BLOCKS])->fetchAll();
  }

  public static function edit($data)
  {
    self::removal();

    foreach ($data as $favet_id) {

      $type = $favet_id < 0 ? self::TYPE_BLOCKS : self::TYPE_TOPICS_BLOG;

      DB::run("INSERT INTO users_preferences(facet_id, user_id, type) VALUES (:facet_id, :user_id, :type)", ['facet_id' => $favet_id, 'user_id' => self::container()->user()->id(), 'type' => $type]);
    }

    return;
  }

  public static function removal()
  {
    return DB::run("DELETE FROM users_preferences WHERE user_id = ?", [self::container()->user()->id()]);
  }

  public static function getMenu()
  {
    $limit = config('facets', 'quantity_home');

    $sql = "SELECT 
                    fs.facet_id, 
                    fs.facet_slug, 
                    fs.facet_title,
                    fs.facet_user_id,
                    fs.facet_img,
                    fs.facet_type,
					pr.type
						FROM users_preferences pr
							LEFT JOIN facets fs ON fs.facet_id = pr.facet_id WHERE pr.user_id = :user_id LIMIT $limit"; // AND pr.facet_id > 0

    return DB::run($sql, ['user_id' => self::container()->user()->id()])->fetchAll();
  }
  
    public static function getBlog(int $id): false|array
    {
        return DB::run("SELECT facet_slug FROM facets WHERE facet_user_id = :id AND facet_type = 'blog'", ['id' => $id])->fetch();
    }
  
}
