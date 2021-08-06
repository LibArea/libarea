<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use App\Models\AuthModel;
use Lori\Config;
use Lori\Base;

class AuthController extends \MainController
{
    // Показ формы регистрации
    public function registerForm()
    {
        // Если включена инвайт система
        if (Config::get(Config::PARAM_INVITE)) {
            redirect('/invite');
        }

        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Sign up'),
            'sheet'         => 'register',
            'meta_title'    => lang('Sign up') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('info_security'),
        ];

        return view(PR_VIEW_DIR . '/auth/register', ['data' => $data, 'uid' => $uid]);
    }

    // Показ формы регистрации с инвайтом
    public function registerInviteForm()
    {
        // Код активации
        $code = \Request::get('code');

        // Проверяем код
        $invate = UserModel::InvitationAvailable($code);
        if (!$invate) {
            Base::addMsg(lang('The code is incorrect'), 'error');
            redirect('/');
        }

        // http://***/register/invite/61514d8913558958c659b713
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Registration by invite'),
            'sheet'         => 'register',
            'meta_title'    => lang('Registration by invite') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        return view(PR_VIEW_DIR . '/auth/register-invate', ['data' => $data, 'uid' => $uid, 'invate' => $invate]);
    }

    // Отправка запроса для регистрации
    public function register()
    {
        $email      = \Request::getPost('email');
        $login      = \Request::getPost('login');
        $inv_code   = \Request::getPost('invitation_code');
        $inv_uid    = \Request::getPostInt('invitation_id');
        $password   = \Request::getPost('password');
        $reg_ip     = \Request::getRemoteAddress();

        $url = $inv_code ? '/register/invite/' . $inv_code : '/register';
        
        if ($inv_uid <= 0) {
            redirect($url);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Base::addMsg(lang('Invalid') . ' email', 'error');
            redirect($url);
        }

        if (is_array(AuthModel::replayEmail($email))) {
            Base::addMsg(lang('e-mail-replay'), 'error');
            redirect($url);
        }

        if (is_array(AuthModel::repeatIpBanRegistration($reg_ip))) {
            Base::addMsg(lang('multiple-accounts'), 'error');
            redirect($url);
        }

        Base::charset_slug($login, lang('Nickname'), '/register');

        Base::Limits($login, lang('Nickname'), '3', '10', $url);
        Base::Limits($password, lang('Password'), '8', '32', $url);

        if (is_numeric(substr($login, 0, 1))) {
            Base::addMsg(lang('nickname-no-start'), 'error');
            redirect($url);
        }

        for ($i = 0, $l = Base::getStrlen($login); $i < $l; $i++) {
            if (self::textCount($login, Base::getStrlen($login, $i, 1)) > 4) {
                Base::addMsg(lang('nickname-repeats-characters'), 'error');
                redirect($url);
            }
        }

        // Запретим 
        $disabled = ['admin', 'support', 'lori', 'loriup', 'dev', 'docs', 'meta', 'email', 'mail', 'login'];
        if (in_array($login, $disabled)) {
            Base::addMsg(lang('nickname-replay'), 'error');
            redirect($url);
        }

        if (is_array(AuthModel::replayLogin($login))) {
            Base::addMsg(lang('nickname-replay'), 'error');
            redirect($url);
        }

        if (substr_count($password, ' ') > 0) {
            Base::addMsg(lang('password-spaces'), 'error');
            redirect($url);
        }

        if (!$inv_code) {
            if (Config::get(Config::PARAM_CAPTCHA)) {
                if (!Base::checkCaptchaCode()) {
                    Base::addMsg(lang('Code error'), 'error');
                    redirect('/register');
                }
            }
            // Кто пригласил (нам нужны будут данные в таблице users)
            $invitation_id = 0;
        } else {
            $invitation_id = $inv_uid;
        }

        // id участника
        $active_uid = UserModel::createUser($login, $email, $password, $reg_ip, $invitation_id);

        if ($invitation_id > 0) {
            // Если регистрация по инвайту, то записываем данные
            UserModel::sendInvitationEmail($inv_code, $invitation_id, $reg_ip, $active_uid);
            Base::addMsg(lang('Successfully, log in'), 'success');
            redirect('/login');
        } else {
            // Активация e-mail
            // Если будет раскомм. то в методе createUser изм. $activated с 1 на 0
            // $active_uid - id участника
            $email_code = Base::randomString('crypto', 20);
            UserModel::sendActivateEmail($active_uid, $email_code);

            // Добавим текс письма тут
            $newpass_link = 'https://' . HLEB_MAIN_DOMAIN . '/email/avtivate/' . $email_code;
            $mail_message = "Activate E-mail: \n" . $newpass_link . "\n\n";
            Base::mailText($email, Config::get(Config::PARAM_NAME) . ' - email', $mail_message);
        }

        Base::addMsg(lang('Check your e-mail to activate your account'), 'success');
        
        redirect('/login');
    }

    // Страница авторизации
    public function loginForm()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Sign in'),
            'sheet'         => 'login',
            'meta_title'    => lang('Sign in') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('info_login'),
        ];

        return view(PR_VIEW_DIR . '/auth/login', ['data' => $data, 'uid' => $uid]);
    }

    // Отправка запроса авторизации
    public function login()
    {
        $email      = \Request::getPost('email');
        $password   = \Request::getPost('password');
        $rememberMe = \Request::getPostInt('rememberme');

        $url = '/login';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Base::addMsg(lang('Invalid email address'), 'error');
            redirect($url);
        }

        $uInfo = UserModel::userInfo($email);

        if (empty($uInfo['id'])) {
            Base::addMsg(lang('Member does not exist'), 'error');
            redirect($url);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($uInfo['id'])) {
            Base::addMsg(lang('Your account is under review'), 'error');
            redirect($url);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($uInfo['id'])) {
            Base::addMsg(lang('Your account is not activated'), 'error');
            redirect($url);
        }

        if (!password_verify($password, $uInfo['password'])) {
            Base::addMsg(lang('E-mail or password is not correct'), 'error');
            redirect($url);
        } else {

            // Если нажал "Запомнить" 
            // Устанавливает сеанс пользователя и регистрирует его
            if ($rememberMe == 1) {
                self::rememberMe($uInfo['id']);
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

    ////// ЗАПОМНИТЬ МЕНЯ
    ////// Работа с токенами и куки 
    public static function rememberMe($user_id)
    {
        // НАСТРОЕМ НАШ СЕЛЕКТОР, ВАЛИДАТОР И СРОК ДЕЙСТВИЯ 
        // Селектор действует как уникальный идентификатор, поэтому нам не нужно 
        // сохранять идентификатор пользователя в нашем файле cookie
        // валидатор сохраняется в виде обычного текста в файле cookie, но хэшируется в бд
        // если селектор (id) найден в таблице auth_tokens, мы затем сопоставляем валидаторы

        $rememberMeExpire = 30;
        $selector = Base::randomString('crypto', 12);
        $validator = Base::randomString('crypto', 20);
        $expires = time() + 60 * 60 * 24 * $rememberMeExpire;

        // Установим токен
        $token = $selector . ':' . $validator;

        // Массив данных
        $data = [
            'user_id' => $user_id,
            'selector' => $selector,
            'hashedvalidator' => hash('sha256', $validator),
            'expires' => date('Y-m-d H:i:s', $expires),
        ];

        // ПРОВЕРИМ, ЕСТЬ ЛИ У ИДЕНТИФИКАТОРА ПОЛЬЗОВАТЕЛЯ УЖЕ НАБОР ТОКЕНОВ
        // Мы действительно не хотим иметь несколько токенов и селекторов для
        // одного и того же идентификатора пользователя. В этом нет необходимости, 
        // так как валидатор обновляется при каждом входе в систему
        // поэтому проверим, есть ли уже маркер, и перепишем, если он есть.
        // Следует немного снизить уровень обслуживания БД и устранить необходимость в спорадических чистках.
        $result = AuthModel::getAuthTokenByUserId($user_id);

        // Записываем
        if (empty($result)) {
            AuthModel::insertToken($data);
        } else {   // Если есть, то обновление
            AuthModel::updateToken($data, $user_id);
        }

        // set_Cookie
        setcookie("remember", $token, $expires);
    }

    public function logout()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy();
        // Возможно, что нужно очистить все или некоторые cookies
        setcookie("remember", "", time() - 3600, "/");
        redirect('/');
    }

    public function recoverForm()
    {
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Password Recovery'),
            'sheet'         => 'login',
            'meta_title'    => lang('Password Recovery') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        return view(PR_VIEW_DIR . '/auth/recover', ['data' => $data, 'uid' => $uid]);
    }

    public function sendRecover()
    {
        $email = \Request::getPost('email');

        if (Config::get(Config::PARAM_CAPTCHA)) {
            if (!Base::checkCaptchaCode()) {
                Base::addMsg(lang('Code error'), 'error');
                redirect('/recover');
            }
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Base::addMsg(lang('Invalid') . ' email', 'error');
            redirect('/recover');
        }

        $uInfo = UserModel::userInfo($email);

        if (empty($uInfo['email'])) {
            Base::addMsg(lang('There is no such e-mail on the site'), 'error');
            redirect('/recover');
        }

        // Проверка на заблокированный аккаунт
        if ($uInfo['ban_list'] == 1) {
            Base::addMsg(lang('Your account is under review'), 'error');
            redirect('/recover');
        }

        $code = $uInfo['id'] . '-' . Base::randomString('crypto', 25);
        UserModel::initRecover($uInfo['id'], $code);

        // Добавим текс письма тут
        $newpass_link = 'https://' . HLEB_MAIN_DOMAIN . '/recover/remind/' . $code;
        $mail_message = "Your link to change your password: \n" . $newpass_link . "\n\n";

        Base::mailText($email, Config::get(Config::PARAM_NAME) . ' - changing your password', $mail_message);

        Base::addMsg(lang('New password has been sent to e-mail'), 'success');
        redirect('/login');
    }

    // Страница установки нового пароля
    public function RemindForm()
    {
        // Код активации
        $code = \Request::get('code');

        // Проверяем код
        $user_id = UserModel::getPasswordActivate($code);
        if (!$user_id) {
            Base::addMsg(lang('code-incorrect'), 'error');
            redirect('/recover');
        }

        $user = UserModel::getUser($user_id['activate_user_id'], 'id');
        Base::PageError404($user);

        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Password Recovery'),
            'code'          => $code,
            'user_id'       => $user_id['activate_user_id'],
            'sheet'         => 'recovery',
            'meta_title'    => lang('Password Recovery') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        return view(PR_VIEW_DIR . '/auth/newrecover', ['data' => $data, 'uid' => $uid]);
    }

    // Проверка корректности E-mail
    public function AvtivateEmail()
    {
        // Код активации
        $code = \Request::get('code');

        // Проверяем код
        $avtivate_email = UserModel::getEmailActivate($code);
        if (!$avtivate_email) {
            Base::addMsg(lang('code-used'), 'error');
            redirect('/');
        }

        UserModel::EmailActivate($avtivate_email['user_id']);

        Base::addMsg(lang('yes-email-pass'), 'success');
        redirect('/login');
    }

    public function remindNew()
    {
        $password   = \Request::getPost('password');
        $code       = \Request::getPost('code');
        $user_id    = \Request::getPost('user_id');

        if (!$user_id) {
            return false;
        }

        Base::Limits($password, lang('Password'), '8', '32', '/recover/remind/' . $code);

        $newpass  = password_hash($password, PASSWORD_BCRYPT);
        $news     = UserModel::editPassword($user_id, $newpass);

        if (!$news) {
            return false;
        }

        UserModel::editRecoverFlag($user_id);

        Base::addMsg(lang('Password changed'), 'success');
        redirect('/login');
    }

    // Вхождение подстроки
    private function textCount($str, $needle)
    {
        return mb_substr_count($str, $needle, 'utf-8');
    }
}
