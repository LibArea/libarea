<?php

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class FavoriteModel extends Model
{
    // Добавить / удалить из закладок
    public static function setFavorite($content_id, $type)
    {
        $result = self::getUserFavorite($content_id, $type);

        if (is_array($result)) {

            $sql = "DELETE FROM favorites WHERE tid = :content_id AND user_id = :user_id AND action_type = :type";

            DB::run($sql, ['content_id' => $content_id, 'user_id' => self::container()->user()->id(), 'type' => $type]);

            self::delFavoriteTag($content_id, $type);

            return 'del';
        }

        $sql = "INSERT INTO favorites(tid, user_id, action_type) VALUES(:content_id, :user_id, :type)";

        DB::run($sql, ['content_id' => $content_id, 'user_id' => self::container()->user()->id(), 'type' => $type]);

        return 'add';
    }

    // Delete data from the link table for folders in bookmarks
    // Удалим данные из таблицы связи для папок в закладках
    public static function delFavoriteTag($content_id, $type)
    {
        $sql = "DELETE FROM folders_relation WHERE tid = :content_id AND user_id = :user_id AND action_type = :type";

        return DB::run($sql, ['content_id' => $content_id, 'user_id' => self::container()->user()->id(), 'type' => $type]);
    }

    public static function getUserFavorite($content_id, $type)
    {
        $sql = "SELECT tid, user_id, action_type FROM favorites 
                    WHERE tid = :content_id AND user_id = :user_id AND action_type = :type";

        return  DB::run($sql, ['content_id' => $content_id, 'user_id' => self::container()->user()->id(), 'type' => $type])->fetch();
    }

    public static function checkingPost(int $post_id)
    {
        $sql = "SELECT post_id FROM posts WHERE post_id = :post_id AND post_draft = 0 AND post_is_deleted = 0";

        return DB::run($sql, ['post_id' => $post_id])->fetchAll();
    }
}
