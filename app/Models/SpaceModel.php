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
        $result = $q->leftJoin(['space_signed'])->on(['signed_space_id'], '=', ['space_id'])
                ->and(['signed_user_id'], '=', $user_id)
                ->where(['space_is_delete'], '!=', 1)->getSelect();
        
        return $result;
    } 

    // Для форм добавления и изменения
    // $id
    // $trust_level
    public static function getSpaceSelect($uid)
    {
        if ($uid['trust_level'] == 5) {
            $sql = "SELECT * FROM space";
        } else {
            $sql = "SELECT * FROM space WHERE space_permit_users = 2 ";
        }

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC); 
    } 

   // По id
    public static function getSpaceId($space_id)
    {
        return XD::select('*')->from(['space'])->where(['space_id'], '=', $space_id)->getSelectOne();
    }

   // Пространства участника
    public static function getSpaceUserId($user_id)
    {
        return XD::select('*')->from(['space'])->where(['space_user_id'], '=', $user_id)->getSelect();
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
    
    // Информация пространства по slug
    public static function getSpaceInfo($slug)
    {
        return  XD::select('*')->from(['space'])->where(['space_slug'], '=', $slug)->getSelectOne();
    }
    
    // Все пространства на которые подписан пользователь
    public static function getSpaceUser($user_id) 
    {
        $q = XD::select('*')->from(['space_signed']);
        $result = $q->leftJoin(['space'])->on(['signed_space_id'], '=', ['space_id'])->where(['signed_user_id'], '=', $user_id)->getSelect();

        return $result;
    }
    
    // Подписан пользователь на пространство или нет
    public static function getMySpaceHide($space_id, $user_id) 
    {
        $result = XD::select('*')->from(['space_signed'])->where(['signed_space_id'], '=', $space_id)->and(['signed_user_id'], '=', $user_id)->getSelect();

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
           
            XD::insertInto(['space_signed'], '(', ['signed_space_id'], ',', ['signed_user_id'], ')')->values( '(', XD::setList([$space_id, $user_id]), ')' )->run();             
            
        } else {
            
           XD::deleteFrom(['space_signed'])->where(['signed_space_id'], '=', $space_id)->and(['signed_user_id'], '=', $user_id)->run(); 

        }
        
        return true;
    }
    
    // Изменение пространства
    public static function setSpaceEdit($data)  
    {
        XD::update(['space'])->set(['space_slug'], '=', $data['space_slug'], ',', ['space_name'], '=', $data['space_name'], ',', ['space_description'], '=', $data['space_description'], ',', ['space_color'], '=', $data['space_color'], ',', ['space_img'], '=', $data['space_img'], ',', ['space_text'], '=', $data['space_text'], ',', ['space_permit_users'], '=', $data['space_permit_users'])->where(['space_id'], '=', $data['space_id'])->run();
        
        return true;
    }

    // Возвращает теги пространства
    public static function getSpaceTags($space_id)
    {
        $q = XD::select('*')->from(['space_tags']);
        $result = $q->leftJoin(['space'])->on(['space_id'], '=', ['st_space_id'])->where(['space_id'], '=', $space_id)->getSelect();

        return $result;
    } 
    
    // Информация по тэгу
    public static function getTagInfo($tag_id)
    {
        return XD::select('*')->from(['space_tags'])->where(['st_id'], '=', $tag_id)->getSelectOne();
    } 
    
    // Изменить тэг
    public static function tagEdit($tag_id, $st_title, $st_desc)
    {
        XD::update(['space_tags'])->set(['st_title'], '=', $st_title, ',', ['st_description'], '=', $st_desc)->where(['st_id'], '=', $tag_id)->run();
        
        return true;
    } 
    
    // Добавляем тэг
    public static function tagAdd($space_id, $st_title, $st_desc)
    {
        XD::insertInto(['space_tags'], '(', 
            ['st_space_id'], ',',
            ['st_title'], ',', 
            ['st_description'], ')')->values( '(', 
        
        XD::setList([
            $space_id,
            $st_title, 
            $st_desc]), ')' )->run();

        return true;
    } 
    
    // Удалено пространство или нет
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
    
    // Добавляем пространства
    public static function AddSpace($data) 
    {

        XD::insertInto(['space'], '(', 
            ['space_name'], ',', 
            ['space_slug'], ',', 
            ['space_description'], ',', 
            ['space_color'], ',',  
            ['space_img'], ',',
            ['space_text'], ',',  
            ['space_date'], ',', 
            ['space_user_id'], ',', 
            ['space_type'], ',', 
            ['space_permit_users'], ')')->values( '(', 
        
        XD::setList([
            $data['space_name'], 
            $data['space_slug'], 
            $data['space_description'], 
            $data['space_color'],
            $data['space_img'],            
            $data['space_text'], 
            $data['space_date'], 
            $data['space_user_id'], 
            $data['space_type'], 
            $data['space_permit_users']]), ')' )->run();
        
        // id добавленного пространства
        $space_id = XD::select()->last_insert_id('()')->getSelectValue();

        // Подписываем на созданное пространство   
        XD::insertInto(['space_signed'], '(', ['signed_space_id'], ',', ['signed_user_id'], ')')->values( '(', XD::setList([$space_id, $data['space_user_id']]), ')' )->run(); 

        return true; 
    }
}
