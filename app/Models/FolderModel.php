<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class FolderModel extends Model
{
    // Output of all folders depending on the type, priority
    // Вывод всех папок в зависимости от типа, пренадлежности
    public static function get($type)
    {
        $sql = "SELECT id, title as value FROM folders WHERE action_type = :type AND user_id = :user_id";

        return  DB::run($sql, ['type' => $type, 'user_id' => self::container()->user()->id()])->fetchAll();
    }

    // Number of folders
    // Количество папок
    public static function getCount($type, $user_id)
    {
        $sql = "SELECT id, title as value FROM folders WHERE action_type = :type AND user_id = :user_id";

        return  DB::run($sql, ['type' => $type, 'user_id' => $user_id])->rowCount();
    }

    // To check for a match by title
    // Для проверки совпадению по title
    public static function getOneTitle($type, $user_id, $title)
    {
        $sql = "SELECT title FROM folders WHERE action_type = :type AND user_id = :user_id  AND title = :title";

        return  DB::run($sql, ['type' => $type, 'user_id' => $user_id, 'title' => $title])->fetch();
    }

    // If not in the personal list, then add
    // Если нет в личном списке, то добавляем
    public static function create($folders, $type)
    {
        foreach ($folders as $row) {
            if (!self::getOneTitle($type, self::container()->user()->id(), $row['value'])) {
                $sql    = "INSERT INTO folders (title, action_type, user_id) VALUES (:value, :type, :user_id)";
                DB::run($sql, ['value' => $row['value'], 'type' => $type, 'user_id' => self::container()->user()->id()]);
            }
        }

        return true;
    }

    // Deleting the linked content folder
    // Удаление папки привязанному контенту
    public static function deletingFolderContent($tid, $type)
    {
        $sql    = "DELETE FROM folders_relation WHERE tid = :tid AND action_type = :type AND user_id = :user_id";

        return DB::run($sql, ['tid' => $tid, 'type' => $type, 'user_id' => self::container()->user()->id()]);
    }

    // Link folder to content 
    // Привязываем папку к контенту
    public static function saveFolderContent($id, $tid, $type)
    {
        $sql    = "INSERT INTO folders_relation (folder_id, tid, action_type, user_id) VALUES (:id, :tid, :type, :user_id)";

        return   DB::run($sql, ['id' => $id, 'tid' => $tid, 'type' => $type, 'user_id' => self::container()->user()->id()]);
    }

    // Delete the folder itself
    // Удаляем саму папку
    public static function deletingFolder($id, $type)
    {
        $sql = "DELETE FROM folders WHERE id = :id AND action_type = :type AND user_id = :user_id";

        DB::run($sql, ['id' => $id, 'type' => $type, 'user_id' => self::container()->user()->id()]);

        return self::deletingLinkedContent($id, $type, self::container()->user()->id());
    }

    // Removing a content link from a folder 
    // Удаляем привязку контента к папке
    public static function deletingLinkedContent($id, $type)
    {
        $sql = "DELETE FROM folders_relation WHERE folder_id = :id AND action_type = :type AND user_id = :user_id";

        DB::run($sql, ['id' => $id, 'type' => $type, 'user_id' => self::container()->user()->id()]);
    }
}
