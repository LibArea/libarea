<?php

namespace App\Models;
use Hleb\Constructor\Handlers\Request;
use XdORM\XD;

class UserModel extends \MainModel
{
    
    // Страница участников
    public static function getUsersAll()
    {

       $query = XD::select(['id', 'login', 'name', 'avatar', 'deleted_at'])
              ->from(['users'])
              ->where(['deleted_at'], '=', 0);

        $result = $query->getSelect();
        return $result;

    }

    // Получение информации по логину
    public static function getUserLogin($login)
    {

        $query = XD::select(['id', 'login', 'name', 'email', 'avatar', 'about', 'created_at', 'my_post'])
                ->from(['users'])
                ->where(['login'], '=', $login);

        $result = $query->getSelectOne();

        return $result;

    }
    
    
    // Получение информации по id
    public static function getUserId($id)
    {

        $query = XD::select(['id', 'login', 'name', 'email', 'avatar', 'about', 'created_at'])
                ->from(['users'])
                ->where(['id'], '=', $id);

        $result = $query->getSelectOne();

        return $result;

    }

    // Регистрация участника
    public static function createUser($login,$email,$password)
    {

        // количество участников 
        $count = count(XD::select('*')->from(['users'])->getSelect());
      
        // Для "режима запуска" первые 50 участников получают trust_level = 1 
        if($count < 50 && $GLOBALS['conf']['bootstrap_mode'] == 1) {
            $trust_level = 1; // Режим "запуска сообщества"
        } else {
            $trust_level = 0; // 0 min, 5 TL max (5 = персонал, админ)
        }

        $password    = password_hash($password, PASSWORD_BCRYPT);
        $activated   = 1; // ввести почту и инвайт 
        $reg_ip      = Request::getRemoteAddress(); // ip при регистрации 
        
        XD::insertInto(['users'], '(', ['login'], ',', ['email'], ',', ['password'], ',', ['activated'], ',', ['reg_ip'],',', ['trust_level'], ')')->values( '(', XD::setList([$login, $email, $password, $activated, $reg_ip, $trust_level]), ')' )->run();
        
        return true;
        
    }
    
    // Изменение пароля
    public static function editPassword($login, $password)
    {
        XD::update(['users'])->set(['password'], '=', $password)->where(['login'], '=', $login)->run();
        return true;
    }

    // Изменение аватарки
    public static function setAvatar($login, $img)
    {
        XD::update(['users'])->set(['avatar'], '=', $img)->where(['login'], '=', $login)->run();
        return true;
    }

   // Получение аватарки
    public static function getAvatar($login)
    {
        $query = XD::select(['login', 'avatar'])->from(['users'])->where(['login'], '=', $login);

        $result = $query->getSelectOne();
        return $result;
    }

   // TL - название
    public static function getUserTrust($id)
    {
        $q = XD::select(['id', 'trust_level', 'trust_id', 'trust_name'])->from(['users_trust_level']);
        $query = $q->leftJoin(['users'])->on(['trust_level'], '=', ['trust_id'])
                 ->where(['id'], '=', $id);
                 
        $result = $query->getSelectOne();
        
        return $result;
    }

    // Информация участника
    public static function getUserInfo($data) 
    {

        $query = XD::select(['id', 'email', 'password', 'login', 'name', 'avatar', 'about', 'created_at'])
             ->from(['users'])
             ->where(['email'], '=', $data);

        $result = $query->getSelectOne();
        return $result;

    }
    
   // Количество постов на странице профиля
    public static function getUsersPostsNum($id)
    {
       
        $q = XD::select('*')->from(['posts']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['post_user_id'])
                 ->where(['id'], '=', $id);
       
        $result = count($query->getSelect());

        return $result;
        
    } 
    
    // Количество комментариев на странице профиля
    public static function getUsersCommentsNum($id)
    {
        
        $q = XD::select('*')->from(['comments']);
        $query = $q->leftJoin(['users'])->on(['id'], '=', ['comment_user_id'])
                 ->where(['id'], '=', $id);
       
        $result = count($query->getSelect());
        return $result;
        
    }
    
    // Проверка Логина на дубликаты
    public static function replayLogin($login)
    {

        $q = XD::select('*')->from(['users']);
        $query = $q->where(['login'], '=', $login);
        $result = $query->getSelectOne();
        
        if ($result) {
            return false;
        }
        
        return true;
        
    }
    
    // Проверка Email на дубликаты
    public static function replayEmail($email)
    {

        $q = XD::select('*')->from(['users']);
        $query = $q->where(['email'], '=', $email);
        $result = $query->getSelectOne();
        
        if ($result) {
            return false;
        }
        
        return true;
        
    }
    
    // Редактирование профиля
    public static function editProfile($login, $name, $about)
    {

        XD::update(['users'])->set(['name'], '=', $name, ',', ['about'], '=', $about)->where(['login'], '=', $login)->run();
 
        return true;
        
    }
    
    // Настройка оповещений
    public static function getNotificationSettingByUid($uid)
    {
        return true;
    }  
    
    // Прочитан или нет
    public static function updateNotificationUnread($uid)
    {
        return true;
    }
    
}
