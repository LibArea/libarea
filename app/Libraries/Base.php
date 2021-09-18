<?php

namespace Agouti;

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use App\Models\UserModel;
use App\Models\AuthModel;
use JacksonJeans\Mail;
use JacksonJeans\MailException;
use Agouti\Config;

class Base
{
    public static function getUid()
    {
        $account = Request::getSession('account') ?? [];
        $uid = [];

        // Если сайт обновляется (выключен)
        if (Config::get(Config::PARAM_SITE_OFF) == 1) {
            if (!empty($account['user_trust_level']) != 5) {
                include HLEB_GLOBAL_DIRECTORY . '/app/Optional/site_off.php';
                hl_preliminary_exit();
            }
        }

        if (!empty($account['user_id'])) {
            $uid['user_id']              = $account['user_id'];
            $uid['user_login']           = $account['user_login'];
            $uid['user_trust_level']     = $account['user_trust_level'];
            $uid['user_avatar']          = $account['user_avatar'];
            $uid['user_ban_list']        = $account['user_ban_list'];
            $uid['notif']                = NotificationsModel::usersNotification($account['user_id']);

            Request::getResources()->addBottomScript('/assets/js/app.js');
        } else {
            self::checkCookie();
            $uid['user_id']     = 0;
            $uid['user_trust_level'] = null;

            // Если сайт полностью приватен
            if (Config::get(Config::PARAM_PRIVATE) == 1) {
                include HLEB_GLOBAL_DIRECTORY . '/app/Optional/login.php';
                hl_preliminary_exit();
            }
        }

        return $uid;
    }

    // Проверяет, устанавливался ли когда-либо файл cookie «запомнить меня»
    // Если мы найдем, проверьте его по нашей таблице users_auth_tokens и  
    // если мы найдем совпадение, и оно все ещё в силе.
    public static function checkCookie()
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
        self::setUserSession($user);
        self::rememberMeReset($token['auth_user_id'], $selector);
        redirect('/');
        return true;
    }

    public static function setUserSession($user)
    {
        $data = [
            'user_id'            => $user['user_id'],
            'user_login'         => $user['user_login'],
            'user_email'         => $user['user_email'],
            'user_avatar'        => $user['user_avatar'],
            'user_trust_level'   => $user['user_trust_level'],
            'user_ban_list'      => $user['user_ban_list'],
            'user_limiting_mode' => $user['user_limiting_mode'],
        ];

        $_SESSION['account'] = $data;

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
        $validator = self::randomString('crypto', 20);
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
        $selector           = self::randomString('crypto', 12);
        $validator          = self::randomString('crypto', 20);
        $expires            = time() + 60 * 60 * 24 * $rememberMeExpire;

        // Установим токен
        $token = $selector . ':' . $validator;

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

        // set_Cookie
        setcookie("remember", $token, $expires);
    }

    // Бан
    public static function accountBan($user)
    {
        if ($user['user_ban_list'] == 1) {
            if (!isset($_SESSION)) {
                session_start();
            }
            session_destroy();
            AuthModel::deleteTokenByUserId($user['id']);
            redirect('/info/restriction');
        }
    }

    // Создать случайную строку
    public static function randomString($type, int $len = 8)
    {
        if ($type = 'crypto') {
            return bin2hex(random_bytes($len / 2));
        } else {
            // sha1
            return sha1(uniqid((string) mt_rand(), true));
        }
    }

    public static function PageError404($variable)
    {
        if (!$variable) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        return true;
    }

    public static function PageRedirection($variable, $redirect)
    {
        if (!$variable) {
            redirect($redirect);
        }
        return true;
    }

    // Обрезка текста по словам
    public static function cutWords($content, $maxlen)
    {
        $words = preg_split('#[\s\r\n]+#um', $content);
        if ($maxlen < count($words)) {
            $words = array_slice($words, 0, $maxlen);
        }
        $code_match = array('>', '*', '!', '[ADD:');
        $words      = str_replace($code_match, '', $words);
        return join(' ', $words);
    }

    // https://github.com/JacksonJeans/php-mail
    public static function mailText($user_id, $type)
    {
        // TODO: Let's check the e-mail at the mention
        if ($type == 'appealed') {
            $setting = NotificationsModel::getUserSetting($user_id);
            if ($setting) {
                if ($setting['setting_email_appealed'] == 1) {
                    $user = UserModel::getUser($user_id, 'id');
                    $link = 'https://' . HLEB_MAIN_DOMAIN . '/u/' . $user['user_login'] . '/notifications';
                    $message = lang('You were mentioned (@), see') . ": \n" . $link . "\n\n" . HLEB_MAIN_DOMAIN;
                    self::sendMail($user['user_email'], Config::get(Config::PARAM_NAME) . ' - ' . lang('notification'), $message);
                }
            }
        }

        return true;
    }

    public static function sendMail($email, $subject = '', $message = '')
    {
        if (Config::get(Config::PARAM_SMTP) == 1) {
            $mail = new Mail('smtp', [
                'host'      => 'ssl://' . Config::get(Config::PARAM_SMTP_HOST),
                'port'      => Config::get(Config::PARAM_SMTP_POST),
                'username'  => Config::get(Config::PARAM_SMTP_USER),
                'password'  => Config::get(Config::PARAM_SMTP_PASS)
            ]);

            $mail->setFrom(Config::get(Config::PARAM_SMTP_USER))
                ->setTo($email)
                ->setSubject($subject)
                ->setText($message)
                ->send();
       } else {
            $mail = new Mail();
            $mail->setFrom(Config::get(Config::PARAM_EMAIL), Config::get(Config::PARAM_HOME_TITLE));
             
            $mail->to($email)
                ->setSubject($subject)
                ->setHTML($message, true)
                ->send();
       }
    }
}
