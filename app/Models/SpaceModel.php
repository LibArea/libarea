<?php

namespace App\Models;
use XdORM\XD;

class SpaceModel extends \MainModel
{
    
    // Все пространства сайта
    public static function getSpaceHome()
    {

        $query = XD::select('*')->from(['space']);

        $result = $query->getSelect();
 
        return $result;
 
    } 

    // Списки постов по тегу
    public static function getSpacePosts($space)
    {
     
        $q = XD::select('*')->from(['space']);
        $query = $q->leftJoin(['posts'])->on(['post_space_id'], '=', ['space_id'])
                ->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                ->where(['space_slug'], '=', $space);
                 
        $result = $query->getSelect();

        return $result;
           
    }
    
    // Все теги на которые отписан пользователь
    public static function getSpaceUser($user_id) 
    {
        $q = XD::select('*')->from(['space_hidden']);
        $result = $q->leftJoin(['space'])->on(['hidden_space_id'], '=', ['space_id'])->where(['hidden_user_id'], '=', $user_id)->getSelect();

        return $result;

    }
    
    
    // Подписан пользователь на тег или нет
    public static function getMySpaceHide($space_id, $user_id) 
    {
        $q = XD::select('*')->from(['space_hidden'])->where(['hidden_space_id'], '=', $space_id)->and(['hidden_user_id'], '=', $user_id);
        $result = $q->getSelect();
        
        if($result) {
            return 1;
        } else {
            return false;
        }
        
    }
    
    // Подписка / отписка от пространства
    public static function SpaceHide($space_id, $user_id)
    {
        $result  = self::getMySpaceHide($space_id, $user_id);
          
        if(!$result){
           
            XD::insertInto(['space_hidden'], '(', ['hidden_space_id'], ',', ['hidden_user_id'], ')')->values( '(', XD::setList([$space_id, $user_id]), ')' )->run();             
            
        } else {
            
           XD::deleteFrom(['space_hidden'])->where(['hidden_space_id'], '=', $space_id)->and(['hidden_user_id'], '=', $user_id)->run(); 

        }
        
        return true;
        
    }
}
