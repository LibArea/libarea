<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Hleb\Base\Model;
use Hleb\Static\DB;

class ReplyModel extends Model
{
    /**
     * Get responses by content id
     * Получаем ответы под id контента
     *
     * @param integer $id
     * @param array $user
     */
    public static function get(int $id, array $user)
    {
        $sort = 'AND reply_is_deleted = 0';
        if ($user['trust_level'] == 10) {
            $sort = '';
        }

        $sql = "SELECT 
                    reply_id,
                    reply_user_id,                
                    reply_item_id,
                    reply_parent_id,
                    reply_content as content,
                    reply_date,
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

    /**
     * We receive a response by id
     * Получаем ответ по id
     *
     * @param integer $reply_id
     * @return mixed
     */
    public static function getId(int $reply_id)
    {
        $sql = "SELECT 
                    reply_id,
                    reply_content as content,
                    reply_date,
                    reply_user_id,
					reply_item_id,
                    reply_published,
                    reply_is_deleted
                        FROM replys WHERE reply_id = :reply_id";

        return DB::run($sql, ['reply_id' => $reply_id])->fetch();
    }

    /**
     * Adding a replica
     * Добавляем реплику
     *
     * @param array $params
     * @return mixed
     */
    public static function add(array $params)
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

    /**
     * Editing a replica
     * Редактируем реплику
     *
     * @param array $params
     * @return bool
     */
    public static function edit(array $params): bool
    {
        $sql = "UPDATE replys SET 
                    reply_content     = :reply_content,
                    reply_modified    = :reply_modified
                         WHERE reply_id = :reply_id";

        return (bool)DB::run($sql, $params);
    }
}
