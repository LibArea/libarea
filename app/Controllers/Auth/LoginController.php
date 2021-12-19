<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\AuthModel;
use Base, Validation, Translate;


class LoginController extends MainController
{
    // Отправка запроса авторизации
    public function index()
    {
        $email      = Request::getPost('email');
        $password   = Request::getPost('password');
        $rememberMe = Request::getPostInt('rememberme');

        $redirect   = getUrlByName('login');

        Validation::checkEmail($email, $redirect);

        $uid = UserModel::userInfo($email);

        if (empty($uid['user_id'])) {
            addMsg(Translate::get('member does not exist'), 'error');
            redirect($redirect);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($uid['user_id'])) {
            addMsg(Translate::get('your account is under review'), 'error');
            redirect($redirect);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($uid['user_id'])) {
            addMsg(Translate::get('your account is not activated'), 'error');
            redirect($redirect);
        }

        if (!password_verify($password, $uid['user_password'])) {
            addMsg(Translate::get('e-mail or password is not correct'), 'error');
            redirect($redirect);
        }

        // Если нажал "Запомнить" 
        // Устанавливает сеанс пользователя и регистрирует его
        if ($rememberMe == 1) {
            //2Cookie::rememberMe($uid['user_id']);
            self::rememberMe($uid['user_id']);
        }

        self::setUser($uid);
        $set = (new \App\Controllers\AgentController())->set();

        redirect('/');
    }

    // Страница авторизации
    public function showLoginForm()
    {
        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('login'),
        ];

        return render(
            '/auth/login',
            [
                'meta'  => meta($m, Translate::get('sign in'), Translate::get('info-login')),
                'uid'   => Base::getUid(),
                'data'  => [
                    'sheet' => 'sign in',
                ]
            ]
        );
    }

    // Проверяет, устанавливался ли когда-либо файл cookie «запомнить меня»
    // Если мы найдем, проверьте его по нашей таблице users_auth_tokens и  
    // если мы найдем совпадение, и оно все ещё в силе.
    public static function check()
    {
        // Есть "remember" куки?
        $remember = Request::getCookie('remember');

        // Нет
        if (empty($remember)) {
            return;
        }

        // Получим наш селектор | значение валидатора
        [$selector, $validator] = explode(':', $remember);
        $validator = hash('sha256', $validator);

        $token = AuthModel::getAuthTokenBySelector($selector);

        if (empty($token)) {
            return false;
        }

        // Хэш не соответствует
        if (!hash_equals($token['auth_hashedvalidator'], $validator)) {
            return false;
        }

        // Получение данных по id
        $user = UserModel::getUser($token['auth_user_id'], 'id');

        // Нет пользователя
        if (empty($user)) {
            return false;
        }

        // В бан листе
        if ($user['user_ban_list'] == 1) {
            return false;
        }

        // ПРОСТО ПЕРЕД УСТАНОВКОЙ ДАННЫХ СЕССИИ И ВХОДОМ ПОЛЬЗОВАТЕЛЯ
        // ДАВАЙТЕ ПРОВЕРИМ, НУЖЕН ЛИ ИХ ПРИНУДИТЕЛЬНЫЙ ВХОД
        // Перенесем в конфиг?
        $forceLogin = 0;
        if ($forceLogin > 1) {

            // ПОЛУЧАЕТ СЛУЧАЙНОЕ ЧИСЛО ОТ 1 до 100
            // ЕСЛИ ЭТО НОМЕР МЕНЬШЕ ЧЕМ НОМЕР В НАСТРОЙКАХ ПРИНУДИТЕЛЬНОГО ВХОДА
            // УДАЛИТЬ ТОКЕН ИЗ БД
            if (rand(1, 100) < $forceLogin) {

                AuthModel::deleteTokenByUserId($token['auth_user_id']);

                return;
            }
        }

        // Сессия участника
        self::setUser($user);
        self::rememberMeReset($token['auth_user_id'], $selector);
        redirect('/');
        return true;
    }


    // Каждый раз, когда пользователь входит в систему, используя свой файл cookie «запомнить меня»
    // Сбросить валидатор и обновить БД
    public static function rememberMeReset($user_id, $selector)
    {
        // Получаем по селектору       
        $existingToken = AuthModel::getAuthTokenBySelector($selector);

        if (empty($existingToken)) {
            return self::rememberMe($user_id);
        }

        $rememberMeExpire = 30;
        $validator = randomString('crypto', 20);
        $expires = time() + 60 * 60 * 24 * $rememberMeExpire;

        // Установить
        $token = $selector . ':' . $validator;

        // Если установлено значение true, каждый раз, когда пользователь посещает сайт 
        // и обнаруживает файл cookie новая дата истечения срока действия 
        // устанавливается с помощью параметра  $rememberMeExpire - выше 
        $rememberMeRenew = true;

        if ($rememberMeRenew) {
            // Массивы данных установим
            $data = [
                'hashedvalidator' => hash('sha256', $validator),
                'expires' => date('Y-m-d H:i:s', $expires)
            ];
        } else {
            $data = [
                'hashedvalidator' => hash('sha256', $validator),
            ];
        }

        AuthModel::UpdateSelector($data, $selector);

        setcookie("remember", $token, $expires);
    }

    // Запомнить меня
    public static function rememberMe($user_id)
    {
        $rememberMeExpire   = 30;
        $selector           = randomString('crypto', 12);
        $validator          = randomString('crypto', 20);
        $expires            = time() + 60 * 60 * 24 * $rememberMeExpire;

        $data = [
            'user_id'           => $user_id,
            'selector'          => $selector,
            'hashedvalidator'   => hash('sha256', $validator),
            'expires'           => date('Y-m-d H:i:s', $expires),
        ];

        // Проверим, есть ли у пользователя уже набор токенов
        $result = AuthModel::getAuthTokenByUserId($user_id);
        // Записываем
        if (empty($result)) {
            AuthModel::insertToken($data);
        } else {   // Если есть, то обновление
            AuthModel::updateToken($data, $user_id);
        }

        // Установим токен
        $token = $selector . ':' . $validator;
        setcookie("remember", $token, $expires);
    }

    public static function setUser($uid)
    {
        $data = [
            'user_id'                   => $uid['user_id'],
            'user_login'                => $uid['user_login'],
            'user_email'                => $uid['user_email'],
            'user_avatar'               => $uid['user_avatar'],
            'user_trust_level'          => $uid['user_trust_level'],
            'user_ban_list'             => $uid['user_ban_list'],
            'user_limiting_mode'        => $uid['user_limiting_mode'],
            'user_template'             => $uid['user_template'],
            'user_lang'                 => $uid['user_lang'],
        ];

        $_SESSION['account'] = $data;

        return true;
    }
}
