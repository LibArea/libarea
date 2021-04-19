<?php

namespace App\Models;
use XdORM\XD;
use DB;
use PDO;

class SpaceModel extends \MainModel
{
    
    // Все пространства сайта
    public static function getSpaceAll($user_id)
    {
        $q = XD::select('*')->from(['space']);
        $result = $q->leftJoin(['space_hidden'])->on(['hidden_space_id'], '=', ['space_id'])
                ->and(['hidden_user_id'], '=', $user_id)->getSelect();
        
        return $result;
    } 

    // Для форм добалвения и изменения, для массовой отписки и подписки (В планах!)
    // Пока запрос одинаков с ALL, но необходимо учитывать TL и права Space
    public static function getSpaceSelect()
    {
        return  XD::select('*')->from(['space'])->getSelect();
    } 

   // Получение аватарки
    public static function getSpaceImg($space_id)
    {
        return XD::select(['space_id', 'space_img'])->from(['space'])->where(['space_id'], '=', $space_id)->getSelectOne();
    }

    // Списки постов по пространству
    public static function getSpacePosts($space_id, $user_id, $space_tags_id)
    {
        $q = XD::select('*')->from(['posts'])
            ->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
            ->leftJoin(['space_tags'])->on(['post_tag_id'], '=', ['st_id'])
            ->leftJoin(['votes_post'])->on(['votes_post_item_id'], '=', ['post_id'])
            ->and(['votes_post_user_id'], '=', $user_id)
            ->where(['post_space_id'], '=', $space_id);

        if($space_tags_id) {
            $result = $q->and(['post_tag_id'], '=', $space_tags_id)->orderBy(['post_id'])->desc()->getSelect();
        } else { 
            $result = $q->orderBy(['post_id'])->desc()->getSelect();
        }
 
        return $result;
    }
    
    // Информация пространства
    public static function getSpaceInfo($slug)
    {
        return  XD::select('*')->from(['space'])->where(['space_slug'], '=', $slug)->getSelectOne();
    }
    
    // Все пространства на которые отписан пользователь
    public static function getSpaceUser($user_id) 
    {
        $q = XD::select('*')->from(['space_hidden']);
        $result = $q->leftJoin(['space'])->on(['hidden_space_id'], '=', ['space_id'])->where(['hidden_user_id'], '=', $user_id)->getSelect();

        return $result;
    }
    
    // Подписан пользователь на пространство или нет
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
        XD::update(['space'])->set(['space_slug'], '=', $data['space_slug'], ',', ['space_name'], '=', $data['space_name'], ',', ['space_description'], '=', $data['space_description'], ',', ['space_color'], '=', $data['space_color'], ',', ['space_img'], '=', $data['space_img'], ',', ['space_text'], '=', $data['space_text'])->where(['space_id'], '=', $data['space_id'])->run();
        
        return true;
    }

    // Возвращает теги пространства
    public static  function getSpaceTags($space_id){

        $q = XD::select('*')->from(['space_tags']);
        $result = $q->leftJoin(['space'])->on(['space_id'], '=', ['st_space_id'])->where(['space_id'], '=', $space_id)->getSelect();

        return $result;
    } 
    
    
    // Удален пространств или нет
    public static function isTheSpaceDeleted($space_id) 
    {
        $result = XD::select('*')->from(['space'])->where(['space_id'], '=', $space_id)->getSelectOne();
        
        return $result['space_is_delete'];
    }
    
    // Удаление, восстановление пространства
    public static  function SpaceDelete($space_id)
    {
        if(self::isTheSpaceDeleted($space_id) == 1) {
            XD::update(['space'])->set(['space_is_delete'], '=', 0)->where(['space_id'], '=', $space_id)->run();
        } else {
            XD::update(['space'])->set(['space_is_delete'], '=', 1)->where(['space_id'], '=', $space_id)->run();
        }
        return true;
    } 
    
}
