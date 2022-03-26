<?php

namespace Modules\Catalog\App\Models;

use DB;
use UserData;

class ReplyModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Get responses by content id
    // Получаем ответы под id контента
    public static function get($id, $user)
    {
        $sort = 'AND reply_is_deleted = 0';
        if ($user['trust_level'] == UserData::REGISTERED_ADMIN) {
            $sort = '';
        }

        $sql = "SELECT 
                    reply_id,
                    reply_user_id,                
                    reply_item_id,
                    reply_parent_id,
                    reply_content as content,
                    reply_date as date,
                    reply_votes,
                    reply_published,
                    reply_ip,
                    reply_is_deleted,
                    id, 
                    login, 
                    avatar,
                    created_at,
                    votes_reply_item_id,
                    votes_reply_user_id
                        FROM replys
                            LEFT JOIN users ON id = reply_user_id
                            LEFT JOIN votes_reply ON votes_reply_item_id = reply_id AND votes_reply_user_id = :uid
                                WHERE reply_item_id = :id $sort";

        return DB::run($sql, ['id' => $id, 'uid' => $user['id']])->fetchAll();
    }

    // Получаем ответ по id
    // Получаем ответ по id
    public static function getId($reply_id)
    {
        $sql = "SELECT 
                    reply_id,
                    reply_content as content,
                    reply_date as date,
                    reply_user_id,
                    reply_published,
                    reply_is_deleted
                        FROM replys WHERE reply_id = :reply_id";

        return DB::run($sql, ['reply_id' => $reply_id])->fetch();
    }

    // Adding a comment
    // Добавляем комментарий
    public static function add($params)
    {
        $sql = "INSERT INTO replys(reply_item_id, 
                                        reply_content,
                                        reply_parent_id,
                                        reply_type,
                                        reply_published, 
                                        reply_ip, 
                                        reply_user_id) 
        
                                VALUES(:reply_item_id, 
                                        :reply_content,
                                        :reply_parent_id,
                                        :reply_type,
                                        :reply_published, 
                                        :reply_ip, 
                                        :reply_user_id)";

        DB::run($sql, $params);

        $sql_last_id = DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch();

        return  $sql_last_id['last_id'];
    }

    // Editing a comment
    // Редактируем комментарий
    public static function edit($params)
    {
        $sql = "UPDATE replys SET 
                    reply_content     = :reply_content,
                    reply_modified    = :reply_modified
                         WHERE reply_id = :reply_id";

        return DB::run($sql, $params);
    }
}
