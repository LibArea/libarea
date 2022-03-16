<?php

namespace App\Models;

use DB;

class FolderModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Output of all folders depending on the type, priority
    // Вывод всех папок в зависимости от типа, пренадлежности
    public static function get($type, $uid)
    {
        $sql = "SELECT id, title as value FROM folders WHERE action_type = :type AND user_id = :uid";

        return  DB::run($sql, ['type' => $type, 'uid' => $uid])->fetchAll();
    }

    // Number of folders
    // Количество папок
    public static function getCount($type, $uid)
    {
        $sql = "SELECT id, title as value FROM folders WHERE action_type = :type AND user_id = :uid";

        return  DB::run($sql, ['type' => $type, 'uid' => $uid])->rowCount();
    }

    // To check for a match by title
    // Для проверки совпадению по title
    public static function getOneTitle($type, $uid, $title)
    {
        $sql = "SELECT title FROM folders WHERE action_type = :type AND user_id = :uid  AND title = :title";

        return  DB::run($sql, ['type' => $type, 'uid' => $uid, 'title' => $title])->fetch();
    }

    // If not in the personal list, then add
    // Если нет в личном списке, то добавляем
    public static function create($folders, $type, $uid)
    {
        foreach ($folders as $row) {
            if (!self::getOneTitle($type, $uid, $row['value'])) {
                $sql    = "INSERT INTO folders (title, action_type, user_id) VALUES (:value, :type, :uid)";
                DB::run($sql, ['value' => $row['value'], 'type' => $type, 'uid' => $uid]);
            }
        }

        return true;
    }

    // Deleting the linked content folder
    // Удаление папки привязанному контенту
    public static function deletingFolderContent($tid, $type, $uid)
    {
        $sql    = "DELETE FROM folders_relation WHERE tid = :tid AND action_type = :type AND user_id = :uid";

        return DB::run($sql, ['tid' => $tid, 'type' => $type, 'uid' => $uid]);
    }

    // Link folder to content 
    // Привязываем папку к контенту
    public static function saveFolderContent($id, $tid, $type, $uid)
    {
        $sql    = "INSERT INTO folders_relation (folder_id, tid, action_type, user_id) VALUES (:id, :tid, :type, :uid)";

        return   DB::run($sql, ['id' => $id, 'tid' => $tid, 'type' => $type, 'uid' => $uid]);
    }

    // Delete the folder itself
    // Удаляем саму папку
    public static function deletingFolder($id, $type, $uid)
    {
        $sql = "DELETE FROM folders WHERE id = :id AND action_type = :type AND user_id = :uid";

        DB::run($sql, ['id' => $id, 'type' => $type, 'uid' => $uid]);

        return self::deletingLinkedContent($id, $type, $uid);
    }

    // Removing a content link from a folder 
    // Удаляем привязку контента к папке
    public static function deletingLinkedContent($id, $type, $uid)
    {
        $sql = "DELETE FROM folders_relation WHERE folder_id = :id AND action_type = :type AND user_id = :uid";

        DB::run($sql, ['id' => $id, 'type' => $type, 'uid' => $uid]);
    }
}
