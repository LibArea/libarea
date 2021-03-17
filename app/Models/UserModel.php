<?php

namespace App\Models;
use XdORM\XD;
use DB;

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
    public static function getUser($login)
    {

        $query = XD::select(['id', 'login', 'name', 'email', 'avatar', 'about', 'created_at'])
                ->from(['users'])
                ->where(['login'], '=', $login);

        $result = $query->getSelectOne();

        return $result;

    }

    // Создание участника
    public static function createUser($login,$email,$password)
    {
        
        $params = [
           'login'    => $login,
           'email'    => $email,
           'password' => password_hash($password, PASSWORD_BCRYPT),
           'activated'=>'1',
           'role'=>'1'
        ];

        $sql = "INSERT INTO users(login, email, password, activated, role) VALUES(:login, :email,:password,:activated,:role)";
        DB::run($sql,$params);
        return true;
        
    }

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
    
    
}
