<?php

namespace App\Models;
use XdORM\XD;

class SpaceModel extends \MainModel
{
    
    // Все пространства сайта
    public static function getSpaceHome()
    {
        return  XD::select('*')->from(['space'])->getSelect();
    } 

    // Списки постов по пространству
    public static function getSpacePosts($space_id, $user_id)
    {
        return  XD::select('*')->from(['posts'])
                ->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                ->leftJoin(['votes_post'])->on(['votes_post_item_id'], '=', ['post_id'])
                ->and(['votes_post_user_id'], '=', $user_id)
                ->where(['post_space_id'], '=', $space_id)
                ->getSelect();
    }
    
    // Информация пространства
    public static function getSpaceInfo($slug)
    {
        return  XD::select('*')->from(['space'])->where(['space_slug'], '=', $slug)->getSelectOne();
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
        $result = XD::select('*')->from(['space_hidden'])->where(['hidden_space_id'], '=', $space_id)->and(['hidden_user_id'], '=', $user_id)->getSelect();

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
    
    // Изменение пространства
    public static function setSpaceEdit ($data)
    {
        // Временное решение для TL5
        if(!$data['space_color']) { $data['space_color'] = '#339900';}
        
        XD::update(['space'])->set(['space_slug'], '=', $data['space_slug'], ',', ['space_name'], '=', $data['space_name'], ',', ['space_description'], '=', $data['space_description'], ',', ['space_color'], '=', $data['space_color'], ',', ['space_text'], '=', $data['space_text'])->where(['space_id'], '=', $data['space_id'])->run();
        
        return true;
    }
    
}
