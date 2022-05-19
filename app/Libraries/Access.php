<?php

use Hleb\Constructor\Handlers\Request;
use App\Models\FacetModel;

class Access
{
    // Тип контента, автор контента, время добавления и сколько времени можно редактировать
    public static function author($type_content, $author_id, $adding_time, $limit_time = false)
    {
        if (UserData::checkAdmin()) {
            return true;
        }

        // Ограничение по уровню доверия на создание
        if (self::limitTl(config('trust-levels.tl_add_' . $type_content)) == false) {
            return false;
        }

        // Доступ получает только автор
        if ($author_id != UserData::getUserId()) {
            return false;
        }

        // Ограничение по времени
        self::limiTime($adding_time, $limit_time);

        return true;
    }

    // Добавление тем и блогов
    // $type_content: topic | blog 
    public static function limitFacet($type_content)
    {
        if (UserData::checkAdmin()) {
            return true;
        }

        // Ограничение по уровню доверия на создание
        if (self::limitTl(config('trust-levels.tl_add_' . $type_content)) == false) {
            return false;
        }

        // Лимиты на количество созданного контента (за все время)
        $number_created = FacetModel::countFacetsUser(UserData::getUserId(), $type_content);

        if (self::limitQuantity($number_created, config('trust-levels.count_add_' . $type_content)) == false) {
            return false;
        }

        return true;
    }

    // TL автора и разрешенный TL
    public static function limitTl($allowed_tl)
    {
        if ($allowed_tl == true) {
            return true;
        }

        if (UserData::getUserTl() < $allowed_tl) {
            return false;
        }

        return true;
    }

    // Лимиты на количество
    public static function limitQuantity($quantity, $allowed)
    {
        if ($allowed <= $quantity) {
            return false;
        }

        return true;
    }

    // Лимиты на время после публикации
    public static function limiTime($adding_time, $limit_time = false)
    {
        if ($limit_time == true) {
            $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($adding_time);
            $time = floor($diff / 60);

            if ($time > $limit_time) {
                return false;
            }
        }

        return true;
    }
}
