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
        // Если включена инвайт система
        if($GLOBALS['conf']['invite'] == 1) {
            redirect('/invite');
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Sign up'),
            'title'         => lang('Sign up'). ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Страница регистрации на сайте ' . $GLOBALS['conf']['sitename'],
        ];

        return view('/auth/register', ['data' => $data, 'uid' => $uid]);    
    }
    
    // Показ формы регистрации
    public function invitePage()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Invite'),
            'title'         => lang('Invite') . 'Инвайт | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Страница инвайтов на сайте ' . $GLOBALS['conf']['sitename'],
        ];

        return view('/auth/invite', ['data' => $data, 'uid' => $uid]);    
    }
   
    // Отправка запроса инвайта
    public function inviteHandler() 
    {
        $invite = \Request::getPost('invite');
        print_r($invite);
        exit;
    }
   
    // Отправка запроса для регистрации
    public function registerHandler()
    {
        $email    = \Request::getPost('email');
        $login    = \Request::getPost('login');
        $password = \Request::getPost('password');

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
     
        if ($GLOBALS['conf']['captcha']) {
            if (!Base::checkCaptchaCode()) {
                Base::addMsg('Введеный код не верен', 'error');
                redirect('/register');
            }
        }
     
        $reg_ip = Request::getRemoteAddress(); // ip при регистрации 
        $user   = UserModel::createUser($login,$email,$password,$reg_ip);

        Base::addMsg('Регистрация прошла успешно. Введите e-mail и пароль.', 'error');
        redirect('/login');
    }

    // Страница авторизации
    public function loginPage()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Sign in'),
            'title'         => lang('Sign in') . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Авторизация на сайте  ' . $GLOBALS['conf']['sitename'],
        ];

        return view('/auth/login', ['data' => $data, 'uid' => $uid]);
    }

    // Отправка запроса авторизации
    public function loginHandler()
    {
        $email      = \Request::getPost('email');
        $password   = \Request::getPost('password');
        $rememberMe = \Request::getPost('rememberme');

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
            'h1'          => lang('Password Recovery'),
            'title'       => lang('Password Recovery') . ' | '  . $GLOBALS['conf']['sitename'],
            'description' => 'Страница восстановление пароля на сайте ' . $GLOBALS['conf']['sitename'],
        ];

        return view('/auth/recover', ['data' => $data, 'uid' => $uid]);
    }

    public function sendRecover () 
    {
        $email = \Request::getPost('email');
        
        if ($GLOBALS['conf']['captcha']) {
            if (!Base::checkCaptchaCode()) {
                Base::addMsg('Введеный код не верен', 'error');
                redirect('/recover');
            }
        }
        
        if (!$this->checkEmail($email)) {
           Base::addMsg('Недопустимый email', 'error');
           redirect('/recover');
        }
        
        $uInfo = UserModel::getUserInfo($email);

        if (empty($uInfo['email'])) {
            Base::addMsg('Такого e-mail нет на сайте', 'error');
            redirect('/recover');
        }
        
        // Проверка на заблокированный аккаунт
        if ($uInfo['ban_list'] == 1) {
            Base::addMsg('Вы не можете восстановить пароль', 'error');
            redirect('/recover');
        }
        
        $code = $uInfo['id'] . '-' . Base::randomString('crypto', 25);
        UserModel::initRecover($uInfo['id'], $code);

        // Добавим текс письма тут
        $newpass_link = $GLOBALS['conf']['url'] . '/recover/remind/' . $code;
        $mail_message = "Your link to change your password: \n" .$newpass_link . "\n\n";

        Base::mailText($email, $GLOBALS['conf']['sitename'].' - changing your password', $mail_message);

        Base::addMsg('Новый пароль отправлен на E-mail', 'error');
        redirect('/login');      
    }
    
    // Страница установки нового пароля
    public function RemindPage()
    {
        // Код активации
        $code = \Request::get('code');
 
        // проверяем код
        $user_id = UserModel::getPasswordActivate($code);
        if(!$user_id) 
        {
            Base::addMsg('Код неверен, или он уже использовался. Пройдите процедуру восстановления заново.', 'error');
            redirect('/recover');   
        }

        //получаем пользователя
        if(!$user = UserModel::getUserId($user_id['activate_user_id'])) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
     
        $uid  = Base::getUid();
        $data = [
            'title'         => 'Восстановление пароля',
            'description'   => 'Страница восстановление пароля на сайте',
            'code'          => $code,
            'user_id'       => $user_id['activate_user_id'],
        ];
        
        return view('/auth/newrecover', ['data' => $data, 'uid' => $uid]);
    }
    
    public function RemindPageNew()
    {
        $password   = \Request::getPost('password');
        $code       = \Request::getPost('code');
        $user_id    = \Request::getPost('user_id');
        
        if(!$user_id) {
            return false;
        }

        if (Base::getStrlen($password) < 8 || Base::getStrlen($password) > 24)
        {
            Base::addMsg('Длина пароля должна быть от 8 до 24 знаков', 'error');
            redirect('/recover/remind/' . $code );
        }
 
        $newpass  = password_hash($password, PASSWORD_BCRYPT);
        $news     = UserModel::editPassword($user_id, $newpass);
         
        if(!$news) {
            return false;
        }
        
        UserModel::editRecoverFlag($user_id);
 
        Base::addMsg('Пароль успешно изменен', 'error');
        redirect('/login');
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
