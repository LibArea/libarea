<?php

namespace Lori;

use App\Models\NotificationsModel;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use JacksonJeans\Mail;
use JacksonJeans\MailException;
use SourceParser;
use Lori\Config;

class Base
{
    public static function getUid() 
    {
        $user = Request::getSession('account') ?? [];
        $uid = [];

        if (!empty($user['user_id'])) {

            $usr = UserModel::getUserId($user['user_id']);
 
            if($usr['ban_list'] == 1) {
                if(!isset($_SESSION)) { session_start(); } 
                session_destroy();
                UserModel::DeleteTokenByUserId($usr['id']);
                redirect('/info/restriction');
            }

            $uid['id']          = $usr['id'];
            $uid['login']       = $usr['login']; 
            $uid['trust_level'] = $usr['trust_level'];
            $uid['notif']       = NotificationsModel::usersNotification($usr['id']); 
            $uid['avatar']      = $usr['avatar'];
            $uid['hits_count']  = $usr['hits_count'];
             
            Request::getResources()->addBottomScript('/assets/js/app.js');
            
        } else {
            UserModel::checkCookie();
            $uid['id']          = 0;
            $uid['trust_level'] = null;
            
            // Если сайт полностью приватен
            if(Config::get(Config::PARAM_PRIVATE) == 1) { 
               include HLEB_GLOBAL_DIRECTORY . '/app/Optional/login.php';
               hl_preliminary_exit();
            }
        }

        $uid['uri']     = Request::getUri();
        $uid['msg']     = self::getMsg();
        return $uid;
    }
 
    // Возвращает массив сообщений
    public static function getMsg()
    {
        if (isset($_SESSION['msg'])){
            $msg = $_SESSION['msg'];
        } else {
            $msg = false;
        }

        self::clearMsg();
        return $msg;
    }

    // Очищает очередь сообщений
    public static function clearMsg()
    {
       unset($_SESSION['msg']);
    }

    // Добавляем сообщение
    public static function addMsg($msg, $class='info')
    {
        $_SESSION['msg'][] = array($msg, $class);
    }
  
    // Локализация даты, событий....
    public static function ru_date($string) 
    {
        $monn = array(
            '',
            lang('january'),
            lang('february'),
            lang('martha'),
            lang('april'),
            lang('may'),
            lang('june'),
            lang('july'),
            lang('august'),
            lang('september'),
            lang('october'),
            lang('november'),
            lang('december')
        );
        //Разбиваем дату в массив
        $a = preg_split('/[^\d]/',$string); 
        
        $today = date('Ymd');  //20210421
        if(($a[0].$a[1].$a[2])==$today) {
            //Если сегодня
            return(lang('Today').' '.$a[3].':'.$a[4]);

        } else {
                $b = explode('-',date("Y-m-d"));
                $tom = date('Ymd',mktime(0,0,0,$b[1],$b[2]-1,$b[0]));
                if(($a[0].$a[1].$a[2])==$tom) {
                //Если вчера
                return(lang('Yesterday').' '.$a[3].':'.$a[4]);
            } else {
                //Если позже
                $mm = intval($a[1]);
                return($a[2]." ".$monn[$mm]." ".$a[0]." ".$a[3].":".$a[4]);
            }
        }
    }
  
    // Склонения
    public static function wordform($num, $form_for_1, $form_for_2, $form_for_5){
        $num = abs($num) % 100; 
        $num_x = $num % 10; 
        if ($num > 10 && $num < 20)   // отрезок [11;19]
            return $form_for_5;
        if ($num_x > 1 && $num_x < 5) //  2,3,4
            return $form_for_2;
        if ($num_x == 1)              // оканчивается на 1
            return $form_for_1;
        return $form_for_5;
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
    
    // Markdown
    public static function  Markdown($text)
    {
        $SourceParser = new SourceParser();
        return  $SourceParser->text($text); 
    }
    
    // Пределы
    public static function Limits($name, $txt, $min, $max, $redirect)
    {
        if (self::getStrlen($name) < $min || self::getStrlen($name) > $max)
        {
            $text = sprintf(lang('text-string-length'), '«'. $txt . '»', $min, $max);
            
            self::addMsg($text, 'error');
            redirect($redirect);
        }
        return true;
    }
    
    // Ошибка 404
    public static function PageError404($variable)
    {
        if(!$variable) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        return true;
    }
    
    
    // Обрезка текста по словам
    public static function  cutWords($txt, $maxlen) {  
        $text   = strip_tags($txt);
        $len    = (mb_strlen($text) > $maxlen)? mb_strripos(mb_substr($text, 0, $maxlen), ' ') : $maxlen;
        $cutStr = mb_substr($text, 0, $len);
        $content = (mb_strlen($text) > $maxlen)? $cutStr. '' : $cutStr;
        
        $code_match = array('>', '*', '!', '[ADD:');
        $content = str_replace($code_match, '', $content);
        
        return $content;
    } 
    
    // https://github.com/JacksonJeans/php-mail
    public static function mailText($email, $subject='', $message='')
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
       
       if (!function_exists('curl_init')){
         
            $data = @file_get_contents($api_url.'?'.http_build_query($params));

        } else {

            $curl = curl_init();

            if(strpos($api_url, 'https') !== false){
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

        if(!$data){ return false; }
        $data = json_decode($data, true);

        return !empty($data['success']);
    }
    
    // Проверка в AuthControllerе
    public static function checkCaptchaCode() 
    {
        $response = Request::getPost('g-recaptcha-response');

        if(!$response){ return false; }
        
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
        $text = preg_replace(array('/(<p>)/','(<\/p>)'), array('','\n'), $text);
        
        // Проверяем имя бота и YOUR_WEBHOOK_URL
        if(!$webhookurl = Config::get(Config::PARAM_WEBHOOK_URL)) {
           return false;
        }
        if(!$usernamebot = Config::get(Config::PARAM_NAME_BOT)) {
            return false;
        } 
        
        $content    = lang('Post added');   
        $color      = hexdec( "3366ff" );

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

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

        $ch = curl_init( $webhookurl );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec( $ch );
        // echo $response;
        curl_close( $ch );
    }
   
}