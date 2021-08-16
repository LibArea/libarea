<?php

namespace Lori;

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use App\Models\UserModel;
use App\Models\AuthModel;
use JacksonJeans\Mail;
use JacksonJeans\MailException;
use Lori\Config;

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

        $uid['uri']     = Request::getUri();
        $uid['msg']     = self::getMsg();
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

    // Возвращает массив сообщений
    public static function getMsg()
    {
        if (isset($_SESSION['msg'])) {
            $msg = $_SESSION['msg'];
        } else {
            $msg = false;
        }

        self::clearMsg();
        return $msg;
    }

    public static function clearMsg()
    {
        unset($_SESSION['msg']);
    }

    public static function addMsg($msg, $class)
    {
        $class = ($class == 'error') ? 2 : 1;
        $_SESSION['msg'][] = array($msg, $class);
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

    // Вхождение подстроки
    public static function textCount($str, $needle)
    {
        return mb_substr_count($str, $needle, 'utf-8');
    }

    // Длина строки
    public static function getStrlen($str)
    {
        return mb_strlen($str, "utf-8");
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

    public static function Limits($name, $content, $min, $max, $redirect)
    {
        if (self::getStrlen($name) < $min || self::getStrlen($name) > $max) {

            $text = sprintf(lang('text-string-length'), '«' . $content . '»', $min, $max);
            self::addMsg($text, 'error');
            redirect($redirect);
        }
        return true;
    }

    public static function charset_slug($slug, $text, $redirect)
    {
        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $slug)) {

            $text = sprintf(lang('text-charset-slug'), '«' . $text . '»');
            Base::addMsg($text, 'error');
            redirect($redirect);
        }
        return true;
    }

    public static function PageError404($variable)
    {
        if (!$variable) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        return true;
    }

    public static function PageRedirection($variable)
    {
        if (!$variable) {
            redirect('/');
        }
        return true;
    }

    // Обрезка текста по словам
    public static function  cutWords($content, $maxlen)
    {
        $text       = strip_tags($content);
        $len        = (mb_strlen($text) > $maxlen) ? mb_strripos(mb_substr($text, 0, $maxlen), ' ') : $maxlen;
        $cutStr     = mb_substr($text, 0, $len);
        $content    = (mb_strlen($text) > $maxlen) ? $cutStr . '' : $cutStr;
        $code_match = array('>', '*', '!', '[ADD:');
        $content    = str_replace($code_match, '', $content);

        return $content;
    }

    // https://github.com/JacksonJeans/php-mail
    public static function mailText($email, $subject = '', $message = '')
    {
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
    }

    // Работа с Captcha v2
    private static function callApi($params)
    {
        $api_url = 'https://www.google.com/recaptcha/api/siteverify';

        if (!function_exists('curl_init')) {

            $data = @file_get_contents($api_url . '?' . http_build_query($params));
        } else {

            $curl = curl_init();

            if (strpos($api_url, 'https') !== false) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            }
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

            $data = curl_exec($curl);

            curl_close($curl);
        }

        if (!$data) {
            return false;
        }
        $data = json_decode($data, true);

        return !empty($data['success']);
    }

    // Проверка в AuthControllerе
    public static function checkCaptchaCode()
    {
        $response = Request::getPost('g-recaptcha-response');

        if (!$response) {
            return false;
        }

        $private_key = Config::get(Config::PARAM_PRICATE_KEY);

        return self::callApi(array(
            'secret'   => $private_key,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ));
    }

    // Discord
    public static function AddWebhook($text, $title, $url)
    {
        $text = strip_tags($text, '<p>');
        $text = preg_replace(array('/(<p>)/', '(<\/p>)'), array('', '\n'), $text);

        // Проверяем имя бота и YOUR_WEBHOOK_URL
        if (!$webhookurl = Config::get(Config::PARAM_WEBHOOK_URL)) {
            return false;
        }
        if (!$usernamebot = Config::get(Config::PARAM_NAME_BOT)) {
            return false;
        }

        $content    = lang('Post added');
        $color      = hexdec("3366ff");

        // Формируем даты
        $timestamp  = date("c", strtotime("now"));

        $json_data  = json_encode([

            // Сообщение над телом
            "content" => $content,

            // Ник бота который отправляет сообщение
            "username" => $usernamebot,

            // URL Аватара.
            // Можно использовать аватар загруженный при создании бота
            "avatar_url" => Config::get(Config::PARAM_ICON_URL),

            // Преобразование текста в речь
            "tts" => false,

            // Загрузка файла
            // "file" => "",

            // Массив Embeds
            "embeds" => [
                [
                    // Заголовок
                    "title" => $title,

                    // Тип Embed Type, не меняем
                    "type" => "rich",

                    // Описание
                    "description" => $text,

                    // Ссылка в заголовке url
                    "url" => Config::get(Config::PARAM_URL) . $url,

                    // Таймштамп, обязательно в формате ISO8601
                    "timestamp" => $timestamp,

                    // Цвет границы слева, в HEX
                    "color" => $color,

                    // Подпись и аватар в подвале sitename
                    "footer" => [
                        "text" => Config::get(Config::PARAM_NAME_BOT),
                        "icon_url" => Config::get(Config::PARAM_ICON_URL),
                    ],
                ]
            ]

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init($webhookurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        // echo $response;
        curl_close($ch);
    }
}
