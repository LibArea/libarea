<?php

declare(strict_types=1);

namespace App\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;
use Hleb\Static\Request;

class VotesModel extends Model
{
    public static function authorId(int $content_id, string $type)
    {
        // $type = post / comment / item
        $sql = "SELECT " . $type . "_id, " . $type . "_user_id FROM " . $type . "s WHERE " . $type . "_id = :content_id";

        $result = DB::run($sql, ['content_id' => $content_id])->fetch();

        return $result[$type . '_user_id'];
    }

    /**
     * Checking if the user has voted
     * Проверяем, голосовал ли пользователь
     *
     * @param integer $content_id
     * @param string $type
     */
    public static function status(int $content_id, string $type)
    {
        $sql = "SELECT votes_" . $type . "_item_id,  votes_" . $type . "_user_id, votes_" . $type . "_date
                    FROM votes_" . $type . "  
                        WHERE votes_" . $type . "_item_id = :content_id AND votes_" . $type . "_user_id = :author_id";

        return  DB::run($sql, ['content_id' => $content_id, 'author_id' => self::container()->user()->id()])->fetch();
    }

    /**
     * Like frequency per day 
     * Частота размещения лайков в день 
     *
     * @param string $type
     */
    public static function getSpeedVotesDay(string $type)
    {
        $sql = "SELECT votes_" . $type . "_item_id FROM votes_" . $type . "
                    WHERE votes_" . $type . "_user_id = :user_id AND votes_" . $type . "_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['user_id' => self::container()->user()->id()])->rowCount();
    }

    public static function save(int $content_id, string $type)
    {
        $params = [
            'item_id'   => $content_id,
            'points'    => 1,
            'ip'        => Request::getUri()->getIp(),
            'user_id'   => self::container()->user()->id(),
        ];

        $sql = "INSERT INTO votes_" . $type . "(votes_" . $type . "_item_id, 
                                                votes_" . $type . "_points, 
                                                votes_" . $type . "_ip,
                                                votes_" . $type . "_user_id) 
                                                    VALUES(:item_id, :points, :ip, :user_id)";

        return DB::run($sql, $params);
    }


    public static function remove(int $content_id, string $type)
    {
        $sql = "DELETE FROM votes_" . $type . " WHERE votes_" . $type . "_item_id = :item_id AND votes_" . $type . "_user_id = :user_id";

        DB::run($sql, ['item_id'   => $content_id, 'user_id' => self::container()->user()->id()]);
    }

    /**
     * Recording the vote in the content table
     * Записываем голосование в таблицу конктена
     *
     * @param integer $content_id
     * @param string $type
     * @param string $action
     */
    public static function saveContent(int $content_id, string $type, string $action = '+ 1')
    {
        $sql = "UPDATE " . $type . "s SET " . $type . "_votes = (" . $type . "_votes " . $action . ") WHERE " . $type . "_id = :content_id";

        return DB::run($sql, ['content_id' => $content_id]);
    }
}
