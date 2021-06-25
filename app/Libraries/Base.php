<?php

namespace Lori;

use App\Models\NotificationsModel;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use JacksonJeans\Mail;
use JacksonJeans\MailException;
use MyParsedown;
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
                UserModel::deleteTokenByUserId($usr['id']);
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
    public static function Markdown($content, $type)
    {
        $Parsedown = new MyParsedown();
        $Parsedown->setSafeMode(true); //безопасность
 
        if($type == 'line'){
            return  $Parsedown->line($content); 
        }
        
        return  $Parsedown->text($content); 
    }
    
    // Работа с контентом
    public static function text($content, $type)
    {
        if($type == 'md') {
            $md     = self::Markdown($content, 'text');
            $text   = self::parseUser($md);
            $text   = self::stopWords($text);
        } elseif ($type == 'line') {
            $text   = self::Markdown($content, 'line');
            $text   = self::stopWords($text);
        } else {
            $text   = self::parseUser($content);
            $text   = self::stopWords($text);
        }    

        return self::parseUrl($text);
    }
    
    
	public static function stopWords($content, $replace = '*')
	{

        $stop_words = UserModel::getStopWords();
        
		foreach($stop_words as $word)
		{
    		$word = trim($word['stop_word']);

			if (!$word) {
				continue;
			}

            // В случае появления чувствительных слов, содержимое переходит в аудит (или меняется)
            // Поддерживая как обычные строки, так и регулярные выражения.
            // Регулярное выражение { *** } должно соответствовать PCRE
            // https://www.php.net/manual/ru/pcre.pattern.php
            // *** - можно расширить для выбора условий
			if (substr($word, 0, 1) == '{' AND substr($word, -1, 1) == '}') {
				$regex[] = substr($word, 1, -1);
			} else {
				$word_length = self::rowData($word);

				$replace_str = '';
				for ($i = 0; $i < $word_length; $i++) {
					$replace_str .=  $replace;
				}

				$content = str_replace($word, $replace_str, $content);
			}
		}

		if (isset($regex)) {
			preg_replace($regex, '***', $content);
		}

		return $content;
	}
    
    // Длина строки и количество символов 
    public static function rowData($string, $charset = 'UTF-8')
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($string, $charset);
        } else {
            return iconv_strlen($string, $charset);
        }
    }

    // Для URL отслеживания
    // Пока вернем (см. метод estimationUrl) 
    public static function parseUrl($content) 
    {
        return $content;
    }
    
    // Срабатывает тригер для оценки URl (используем далее, см. метод: editPostRecording)
    // Тип публикации
    // id публикации 
    // url публикации
    // Кто добавил
    // Проверка на совпадение домена и его статус, вес домена в системе (добавить!)
    public static function estimationUrl($content) 
    {
        $regex = '/(?<!!!\[\]\(|"|\'|\=|\)|>)(https?:\/\/[-a-zA-Z0-9@:;%_\+.~#?\&\/\/=!]+)(?!"|\'|\)|>)/i'; 
        if($info = preg_match($regex, $content, $matches)) {
            return  $matches[1];
        } 
        return true;
    }
    
    // Парсинг user login / uid
    public static function parseUser($content, $with_user = false, $to_uid = false)
	{
		preg_match_all('/@([^@,:\s,]+)/i', strip_tags($content), $matchs);

		if (is_array($matchs[1]))
		{
			$match_name = array();

			foreach ($matchs[1] as $key => $login)
			{
				if (in_array($login, $match_name)) {
					continue;
				}

				$match_name[] = $login;
			}

			$match_name = array_unique($match_name);

			arsort($match_name);

			$all_users = array();

			$content_uid = $content;

			foreach ($match_name as $key => $login)	{
                
                // Добавим по id, нужна будет для notif
				if (preg_match('/^[0-9]+$/', $login)) {
					$user_info = UserModel::getUserId($login);
				}
				else {
					$user_info = UserModel::getUserlogin($login);
				}

				if ($user_info) {
					$content = str_replace('@' . $login, '<a href="/u/' . $user_info['login'] . '" class="to-user">@' . $user_info['login'] . '</a>', $content);

					if ($to_uid) {
						$content_uid = str_replace('@' . $login, '@' . $user_info['id'], $content_uid);
					}

					if ($with_user) {
						$all_users[] = $user_info['id'];
					}
				}
			}
		}

		if ($with_user) {
			return $all_users;
		}

		if ($to_uid) {
			return $content_uid;
		}

		return $content;
	}
    
    // Пределы
    public static function Limits($name, $content, $min, $max, $redirect)
    {
        if (self::getStrlen($name) < $min || self::getStrlen($name) > $max)
        {
            $text = sprintf(lang('text-string-length'), '«'. $content . '»', $min, $max);
            
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
    public static function  cutWords($content, $maxlen)
    {  
        $text       = strip_tags($content);
        $len        = (mb_strlen($text) > $maxlen)? mb_strripos(mb_substr($text, 0, $maxlen), ' ') : $maxlen;
        $cutStr     = mb_substr($text, 0, $len);
        $content    = (mb_strlen($text) > $maxlen)? $cutStr. '' : $cutStr;
        $code_match = array('>', '*', '!', '[ADD:');
        $content    = str_replace($code_match, '', $content);
        
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

    // Права для TL
    public static function validTl($trust_level, $action, $redirect) {
        
        if ($trust_level < $action)
        {
            self::addMsg(lang('tl-limitation'), 'error');
            redirect($redirect);
        }
        
        return true;
    }
    
    // Проверка доступа
    public static function accessСheck($content, $type, $uid)
    {
        
        if(!$content){
            redirect('/');
        }
 
        // Редактировать может только автор и админ
        if ($content[$type . '_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }
        
        return true;
    } 

}