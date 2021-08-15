<?php

namespace App\Models;

use DB;
use PDO;

class SpaceModel extends \MainModel
{
    // Пространства все / подписан
    public static function getSpacesAll($page, $limit, $user_id, $sort)
    {
        $signet = "";
        if ($sort == 'subscription') {
            $signet = "AND signed_user_id = :user_id";
        }

        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                space_id, 
                space_name, 
                space_description,
                space_slug, 
                space_img,
                space_cover_art,
                space_color,
                space_date,
                space_type,
                space_user_id,
                space_is_delete,
                user_id,
                user_login,
                user_avatar,
                signed_space_id, 
                signed_user_id
                    FROM spaces 
                    LEFT JOIN users ON user_id = space_user_id
                    LEFT JOIN spaces_signed ON signed_space_id = space_id AND signed_user_id = :user_id 
                    WHERE space_is_delete != 1 $signet
                    ORDER BY space_id DESC LIMIT $start, $limit";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество
    public static function getSpacesAllCount()
    {
        $sql = "SELECT space_id, space_is_delete FROM spaces WHERE space_is_delete != 1";

        return DB::run($sql)->rowCount();
    }

    // Для форм добавления и изменения поста
    public static function getSpaceSelect($user_id, $trust_level)
    {
        $spaces = self::getSubscription($user_id);

        $result = array();
        foreach ($spaces as $ind => $row) {
            $result[$ind] = $row['signed_space_id'];
        }

        $sql = "SELECT 
                    space_id,
                    space_name,
                    space_user_id,
                    space_permit_users
                        FROM spaces 
                        WHERE space_id IN(" . implode(',', $result) . ") AND
                        space_permit_users = 0 or space_user_id = :user_id AND space_is_delete != 1  
                        ORDER BY space_id DESC";

        if ($trust_level == 5) {
            $sql = "SELECT 
                    space_id,
                    space_name,
                    space_user_id
                        FROM spaces WHERE space_is_delete != 1";
        }

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Информация по пространству (id, slug)
    public static function getSpace($params, $name)
    {
        $sort = "space_id = :params";
        if ($name == 'slug') {
            $sort = "space_slug = :params";
        }

        $sql = "SELECT 
                    space_id,
                    space_name,
                    space_slug,
                    space_description,
                    space_img,
                    space_cover_art,
                    space_text,
                    space_short_text,
                    space_date,
                    space_color,
                    space_category_id,
                    space_user_id,
                    space_type,
                    space_permit_users,
                    space_feed,
                    space_tl,
                    space_is_delete,
                    user_id,
                    user_login,
                    user_avatar
                        FROM spaces 
                        LEFT JOIN users ON space_user_id = user_id
                        WHERE $sort";

        return DB::run($sql, ['params' => $params])->fetch(PDO::FETCH_ASSOC);
    }

    // Пространства, которые создал участник
    public static function getUserCreatedSpaces($user_id)
    {
        $sql = "SELECT 
                space_id, 
                space_slug, 
                space_name,
                space_img,
                space_user_id,
                space_is_delete
                    FROM spaces  
                    WHERE space_user_id = :user_id AND space_is_delete != 1";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Пространства все / подписан
    public static function getSubscription($user_id)
    {
        $sql = "SELECT 
                    space_id, 
                    space_slug, 
                    space_name,
                    space_img,
                    space_user_id,
                    space_is_delete,
                    signed_space_id, 
                    signed_user_id
                        FROM spaces 
                        LEFT JOIN spaces_signed ON signed_space_id = space_id AND signed_user_id = :user_id 
                        WHERE space_is_delete != 1 AND signed_user_id = :user_id";

        return DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Количество читающих
    public static function numSpaceSubscribers($space_id)
    {
        $sql = "SELECT signed_id, signed_space_id FROM spaces_signed WHERE signed_space_id = $space_id";

        return DB::run($sql)->rowCount();
    }

    // Изменение пространства
    public static function edit($data)
    {
        $params = [
            'space_slug'          => $data['space_slug'],
            'space_name'          => $data['space_name'],
            'space_description'   => $data['space_description'],
            'space_color'         => $data['space_color'],
            'space_text'          => $data['space_text'],
            'space_short_text'    => $data['space_short_text'],
            'space_permit_users'  => $data['space_permit_users'],
            'space_feed'          => $data['space_feed'],
            'space_tl'            => $data['space_tl'],
            'space_id'            => $data['space_id'],
        ];

        $sql = "UPDATE spaces SET
                    space_slug          = :space_slug,
                    space_name          = :space_name,
                    space_description   = :space_description,
                    space_color        = :space_color,
                    space_text          = :space_text,
                    space_short_text    = :space_short_text,
                    space_permit_users  = :space_permit_users,
                    space_feed          = :space_feed,
                    space_tl            = :space_tl
                        WHERE space_id  = :space_id";

        return DB::run($sql, $params);
    }

    // Изменение фото / обложки
    public static function setImg($space_id, $img)
    {
        $sql = "UPDATE spaces SET space_img = :img WHERE space_id = :space_id";

        return DB::run($sql, ['space_id' => $space_id, 'img' => $img]);
    }

    public static function setCover($space_id, $cover)
    {
        $sql = "UPDATE spaces SET space_cover_art = :cover WHERE space_id = :space_id";

        return DB::run($sql, ['space_id' => $space_id, 'cover' => $cover]);
    }

    // Удалим обложку для пространства
    public static function CoverRemove($space_id)
    {
        $sql = "UPDATE spaces SET space_cover_art = 'space_cover_no.jpeg' WHERE space_id = :space_id";

        return DB::run($sql, ['space_id' => $space_id]);
    }

    // Добавляем пространства
    public static function AddSpace($data)
    {
        $params = [
            'space_slug'        => $data['space_slug'],
            'space_name'        => $data['space_name'],
            'space_description' => $data['space_description'],
            'space_color'       => $data['space_color'],
            'space_img'         => $data['space_img'],
            'space_date'        => $data['space_date'],
            'space_category_id' => $data['space_category_id'],
            'space_user_id'     => $data['space_user_id'],
            'space_type'        => $data['space_type'],
            'space_text'        => $data['space_text'],
            'space_short_text'  => $data['space_short_text'],
            'space_permit_users' => $data['space_permit_users'],
            'space_feed'        => $data['space_feed'],
            'space_tl'          => $data['space_tl'],
            'space_is_delete'   => $data['space_is_delete'],
        ];

        $sql = "INSERT INTO spaces(space_slug, 
                                    space_name,
                                    space_description,
                                    space_color,
                                    space_img,
                                    space_date,
                                    space_category_id,
                                    space_user_id,
                                    space_type,
                                    space_text,
                                    space_short_text,
                                    space_permit_users,
                                    space_feed,
                                    space_tl,
                                    space_is_delete) 
                       VALUES(:space_slug, 
                                    :space_name,
                                    :space_description,
                                    :space_color,
                                    :space_img,
                                    :space_date,
                                    :space_category_id,
                                    :space_user_id,
                                    :space_type,
                                    :space_text,
                                    :space_short_text,
                                    :space_permit_users,
                                    :space_feed,
                                    :space_tl,
                                    :space_is_delete)";

        DB::run($sql, $params);

        $sql_last_id    =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch(PDO::FETCH_ASSOC);
        $space_id       = $sql_last_id['last_id'];

        $params = [
            'signed_space_id'   => $space_id,
            'signed_user_id'    => $data['space_user_id'],
        ];

        $sql = "INSERT INTO spaces_signed(signed_space_id, signed_user_id) 
                       VALUES(:signed_space_id, :signed_user_id)";

        return DB::run($sql, $params);
    }
}
