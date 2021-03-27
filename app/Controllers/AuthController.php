<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use Base;


class AuthController extends \MainController
{

    // Показ формы регистрации
    public function registerPage()
    {
        
        $uid  = Base::getUid();
        $data = [
            'title'       => 'Регистрация | AreaDev',
            'description' => 'Страница регистрации на сайте AreaDev',
        ];

        return view('/auth/register', ['data' => $data, 'uid' => $uid]);    
   
    }

    // Отправка запроса для регистрации
    public function registerHandler()
    {

        $email    = Request::getPost('email');
        $login    = Request::getPost('login');
        $password = Request::getPost('password');

        if ($email == '' || $login == '' || $password == '')
        {
            if ($email == '')
            {
               Base::addMsg('Поле «Email» не может быть пустым', 'error');
               redirect('/register');
            }
            if ($login == '')
            {
               Base::addMsg('Поле «Логин» не может быть пустым', 'error');
               redirect('/register');
            }
            if ($password == '')
            {
                Base::addMsg('Поле «Пароль» не может быть пустым', 'error');
                redirect('/register');
            }
        }

        if (!$this->checkEmail($email))
        {
            Base::addMsg('Недопустимый email', 'error');
            redirect('/register');
        }
       
        if (!UserModel::replayEmail($email))
        {
              Base::addMsg('Такой e-mail уже есть на сайте', 'error');
              redirect('/register');
        }
       
        // Упростить и в метод
        if (strlen($login) < 3 || self::getStrlen($login) < 2)
        {
          Base::addMsg('Логин слишком короткий', 'error');
          redirect('/register');
        }
        if (self::getStrlen($login) > 10)
        {
          Base::addMsg('Логин слишком длинный', 'error');
          redirect('/register');
        }
        if (preg_match('/\s/', $login) || strpos($login,' '))
        {
          Base::addMsg('В логине не допускаются пробелы', 'error');
          redirect('/register');
        }
        if (is_numeric(substr($login, 0, 1)) || substr($login, 0, 1) == "_")
        {
          Base::addMsg('Логин не может начинаться с цифры и подчеркивания', 'error');
          redirect('/register');
        }
        if (substr($login, -1, 1) == "_")
        {
          Base::addMsg('Логин не может заканчиваться символом подчеркивания', 'error');
          redirect('/register');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/u', $login))
        {
          Base::addMsg('В логине можно использовать только латиницу, цифры', 'error');
          redirect('/register');
        }
        
        for ($i = 0, $l = self::getStrlen($login); $i < $l; $i++)
        {
          if (self::textCount($login, self::getSubstr($login, $i, 1)) > 4)
          {
              Base::addMsg('В логине слишком много повторяющихся символов', 'error');
              redirect('/register');
          }
        }

        if (!UserModel::replayLogin($login))
        {
              Base::addMsg('Такой логин уже есть на сайте', 'error');
              redirect('/register');
        }
         
        if (substr_count($password, ' ') > 0)
        {
            Base::addMsg('Пароль не может содержать пробелов', 'error');
            redirect('/register');
        }

        if (Base::getStrlen($password) < 8 || Base::getStrlen($password) > 24)
        {
            Base::addMsg('Длина пароля должна быть от 8 до 24 знаков', 'error');
            redirect('/register');
        }
     
        $reg_ip = Request::getRemoteAddress(); // ip при регистрации 
        $user   = UserModel::createUser($login,$email,$password,$reg_ip);

        redirect('/login');

    }

    // Страница авторизации
    public function loginPage()
    {
        
        $uid  = Base::getUid();
        $data = [
            'title' => 'Вход | Авторизация',
            'description' => 'Авторизация на сайте AreaDev',
        ];

        return view('/auth/login', ['data' => $data, 'uid' => $uid]);

    }

    // Отправка запроса авторизации
    public function loginHandler()
    {
      
        $email      = Request::getPost('email');
        $password   = Request::getPost('password');
        $rememberMe = Request::getPost('rememberme');

        if (!$this->checkEmail($email)) {
           Base::addMsg('Недопустимый email', 'error');
           redirect('/login');
        }

        $uInfo = UserModel::getUserInfo($email);

        if (empty($uInfo['id'])) {
            Base::addMsg('Пользователь не существует', 'error');
            redirect('/login');
        }
 
         // Находится ли в бан- листе
        if (UserModel::isBan($uInfo['id'])) {
            Base::addMsg('Ваш аккаунт находится на рассмотрении', 'error');
            redirect('/login');
        }
        
        if (!password_verify($password, $uInfo['password'])) {
            Base::addMsg('E-mail или пароль не верен', 'error');
            redirect('/login');
        } else {
            
            // Если нажал "Запомнить" 
            // Устанавливает сеанс пользователя и регистрирует его
            if ($rememberMe == '1') {
                UserModel::rememberMe($uInfo['id']);
            }
            
            $data = [
                'user_id'       => $uInfo['id'],
                'login'         => $uInfo['login'],
                'email'         => $uInfo['email'],
                'name'          => $uInfo['name'],
                'login'         => $uInfo['login'],
                'avatar'        => $uInfo['avatar'],
                'trust_level'   => $uInfo['trust_level'],
            ];
            
            $last_ip = Request::getRemoteAddress();  
            UserModel::setUserLastLogs($uInfo['id'], $uInfo['login'], $uInfo['trust_level'], $last_ip);
            
            $_SESSION['account'] = $data;
            redirect('/');
        }
      
    }

    public function logout() 
    { 
       if(!isset($_SESSION)) { session_start(); } 
       session_destroy();
       // Возможно, что нужно очистить все или некоторые cookies
       redirect('/');
    }

    public function recoverPage () 
    {

        $uid  = Base::getUid();
        $data = [
            'title'       => 'Восстановление пароля',
            'description' => 'Страница восстановление пароля на сайте AreaDev',
        ];

        return view('/auth/recover', ['data' => $data, 'uid' => $uid]);
 
    }

    // Длина строки
    private function getStrlen($str)
    {
        return mb_strlen($str, "utf-8");
    }

    // Вхождение подстроки
    private function textCount($str, $needle)
    {
        return mb_substr_count($str, $needle, 'utf-8');
    }

    // Разобьем строки
    private function getSubstr($str, $start, $len)
    {
        return mb_substr($str, $start, $len, 'utf-8');
    }

    // Проверка e-mail
    private function checkEmail($email)
    {
        $pattern = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";

        return preg_match($pattern, $email);
    }
}