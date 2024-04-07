<?php

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class FileModel extends Model
{
    public static function set($params)
    {
        $sql = "INSERT INTO files(
                    file_path, 
                    file_type,
                    file_content_id, 
                    file_user_id, 
                    file_is_deleted) 
                       VALUES(
                       :file_path, 
                       :file_type,
                       :file_content_id,
                       :file_user_id,
                       :file_is_deleted)";

        return DB::run($sql, $params);
    }

    public static function getFilesUser($page, $limit)
    {
        $start = ($page - 1) * $limit;
        $sql = "SELECT 
                    file_id, 
                    file_path, 
                    file_type,
                    file_content_id, 
                    file_user_id, 
                    file_date
                        FROM files 
                           WHERE file_user_id = :user_id AND file_is_deleted = 0 LIMIT :start, :limit";

        return  DB::run($sql, ['user_id' => self::container()->user()->id(), 'start' => $start, 'limit' => $limit])->fetchAll();
    }

    public static function removal($file_path)
    {
        $sql = "UPDATE files SET file_is_deleted = 1 WHERE file_path = :file_path AND file_user_id = :user_id";

        return DB::run($sql, ['file_path' => $file_path, 'user_id' => self::container()->user()->id()]);
    }
}
