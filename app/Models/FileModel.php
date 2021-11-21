<?php

namespace App\Models;

use Hleb\Scheme\App\Models\MainModel;
use DB;
use PDO;

class FileModel extends MainModel
{
    public static function set($data)
    {
        $params = [
            'file_path'         => $data['file_path'],
            'file_type'         => $data['file_type'],
            'file_content_id'   => $data['file_content_id'],
            'file_user_id'      => $data['file_user_id'],
            'file_date'         => $data['file_date'],
            'file_is_deleted'   => $data['file_is_deleted']

        ];

        $sql = "INSERT INTO files(
                    file_path, 
                    file_type,
                    file_content_id, 
                    file_user_id, 
                    file_date, 
                    file_is_deleted) 
                       VALUES(
                       :file_path, 
                       :file_type,
                       :file_content_id,
                       :file_user_id,
                       :file_date, 
                       :file_is_deleted)";

        return DB::run($sql, $params);
    }

    public static function get($file_id, $user_id, $type)
    {
        $sql = "SELECT 
                    file_id, 
                    file_path, 
                    file_type,
                    file_content_id, 
                    file_user_id, 
                    file_date, 
                    file_is_deleted
                        FROM files 
                        WHERE file_id = :file_id AND 
                            file_user_id = :user_id AND file_type = :type";

        return  DB::run($sql, ['file_id' => $file_id, 'user_id' => $user_id, 'type' => $type])->fetch(PDO::FETCH_ASSOC);
    }

    public static function removal($file_path, $user_id)
    {
        $sql = "UPDATE files SET file_is_deleted = 1 WHERE file_path = :file_path AND file_user_id = :user_id";

        return DB::run($sql, ['file_path' => $file_path, 'user_id' => $user_id]);
    }
}
