<?php

namespace App\Models;

use DB;

class VotesModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function authorId($content_id, $type)
    {
        // $type = post / comment / answer / item
        $sql = "SELECT " . $type . "_id, " . $type . "_user_id FROM " . $type . "s WHERE " . $type . "_id = :content_id";

        $result = DB::run($sql, ['content_id' => $content_id])->fetch();

        return $result[$type . '_user_id'];
    }

    // Checking if the user has voted
    // Проверяем, голосовал ли пользователь
    public static function voteStatus($content_id, $author_id, $type)
    {
        $sql = "SELECT votes_" . $type . "_item_id,  votes_" . $type . "_user_id
                    FROM votes_" . $type . "  
                        WHERE votes_" . $type . "_item_id = :content_id AND votes_" . $type . "_user_id = :author_id";

        return  DB::run($sql, ['content_id' => $content_id, 'author_id' => $author_id])->fetch();
    }

    public static function saveVote($content_id, $ip, $user_id, $type)
    {
        $params = [
            'item_id'   => $content_id,
            'points'    => 1,
            'ip'        => $ip,
            'user_id'   => $user_id,
        ];

        $sql = "INSERT INTO votes_" . $type . "(votes_" . $type . "_item_id, 
                                                votes_" . $type . "_points, 
                                                votes_" . $type . "_ip,
                                                votes_" . $type . "_user_id) 
                                                    VALUES(:item_id, :points, :ip, :user_id)";

        return DB::run($sql, $params);
    }

    // Recording the vote in the content table
    // Записываем голосование в таблицу конктена
    public static function saveVoteContent($content_id, $type)
    {
        $sql = "UPDATE " . $type . "s SET " . $type . "_votes = (" . $type . "_votes + 1) WHERE " . $type . "_id = :content_id";

        return DB::run($sql, ['content_id' => $content_id]);
    }
}
