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
        
        // Если авторизирован, то возвращаемся на главную
        if(Request::getSession('account')) {
           redirect('/');
        } 
    
        $data = [
          'title' => 'Регистрация',
          'msg'   => Base::getMsg(),
        ];

        return view('/auth/register', ['data' => $data]);    
   
    }

    // Отправка запроса для регистрации
    public function registerHandler()
    {

        // Если авторизирован, то возвращаемся на главную
        if(Request::getSession('account')) {
           redirect('/');
        } 

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

        if (strlen($password) < 8 || strlen($password) > 124)
        {
            Base::addMsg('Длина пароля должна быть от 8 до 24 знаков', 'error');
            redirect('/register');
        }

        $user = UserModel::createUser($login,$email,$password);

        redirect('/login');

    }

    // Страница авторизации
    public function loginPage()
    {
 
        if(Request::getSession('account')) {
          redirect('/');
        }

         $data = [
          'title' => 'Вход',
          'msg'   => Base::getMsg(),
        ];

        return view('/auth/login', ['data' => $data]);

    }

    // Отправка запроса авторизации
    public function loginHandler()
    {
      
        $email    = Request::getPost('email');
        $password = Request::getPost('password');

        if (!$this->checkEmail($email)) {
           Base::addMsg('Недопустимый email', 'error');
           redirect('/login');
        }

        if (strlen($password) < 8 || strlen($password) > 24) {
            Base::addMsg('Длина пароля должна быть от 8 до 24 знаков', 'error');
            redirect('/login');
        }

        $userInfo = UserModel::getUserInfo($email);

        if (empty($userInfo['id'])) {
            Base::addMsg('Пользователь не существует', 'error');
            redirect('/login');
        }

        if (!password_verify($password, $userInfo['password'])) {
            Base::addMsg('E-mail или пароль не верен', 'error');
            redirect('/login');
        } else {
            
            $user = [
                'user_id'   => $userInfo['id'],
                'login'     => $userInfo['login'],
                'email'     => $userInfo['email'],
                'name'      => $userInfo['name'],
                'login'     => $userInfo['login'],
                'avatar'    => $userInfo['avatar'],
            ];
            
            $_SESSION['account'] = $user;
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
