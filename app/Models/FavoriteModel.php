<?php
namespace App\Models;
use XdORM\XD;

class FavoriteModel extends \MainModel
{
    // Добавить / удалить из закладок
    public static function setFavorite($post_id, $user_id, $type)
    {
        $result = self::getUserFavorite($post_id, $user_id, $type); 

        if (!$result) {
           XD::insertInto(['favorites'], '(', ['favorite_tid'], ',', 
                    ['favorite_user_id'], ',', ['favorite_type'], ')')->values( '(', 
                    XD::setList([$post_id, $user_id, $type]), ')' )->run();
        } else {
           XD::deleteFrom(['favorites'])->where(['favorite_tid'], '=', $post_id)
                    ->and(['favorite_user_id'], '=', $user_id)->run(); 
        } 
        
        return true;
    }
  
    public static function getUserFavorite($post_id, $user_id, $type) 
    {
        return XD::select('*')->from(['favorites'])
                    ->where(['favorite_tid'], '=', $post_id)
                    ->and(['favorite_user_id'], '=', $user_id)
                    ->and(['favorite_type'], '=', $type)
                    ->getSelectOne();
    }

}