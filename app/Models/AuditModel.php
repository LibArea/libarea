<?php

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class AuditModel extends Model
{
    // Add an entry to the audit table (audit or flag)
    // Добавим запись в таблицу аудита (аудит или флаг)
    public static function add($params)
    {
        $sql = "INSERT INTO audits(action_type, type_belonging, user_id, content_id, read_flag) 
                    VALUES(:action_type, :type_belonging, :user_id, :content_id, 0)";

        return DB::run($sql, $params);
    }

    // Let's limit How many complaints are filed today (for frequency limitation)
    // Сколько жалоб подано сегодня (для ограничение частоты)
    public static function getSpeedReport($user_id)
    {
        $sql = "SELECT id FROM audits
                    WHERE user_id = :user_id AND type_belonging = 'report'
                        AND add_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['user_id' => $user_id])->rowCount();
    }

    // Get a list of forbidden stop words
    // Получим список запрещенных стоп-слов
    public static function getStopWords()
    {
        $sql = "SELECT stop_id, stop_word FROM stop_words";

        return DB::run($sql)->fetchAll();
    }
}
