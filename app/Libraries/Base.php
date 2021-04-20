<?php

use App\Models\NotificationsModel;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use JacksonJeans\Mail;
use JacksonJeans\MailException;

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

            if(!$usr['avatar'] ) {
                $usr['avatar'] = 'noavatar.png';
            } 
            
            $uid['id']          = $usr['id'];
            $uid['login']       = $usr['login']; 
            $uid['trust_level'] = $usr['trust_level'];
            $uid['my_space_id'] = $usr['my_space_id'];
            $uid['notif']       = NotificationsModel::usersNotification($usr['id']); 
            $uid['avatar']      = $usr['avatar'];
        } else {
            UserModel::checkCookie();
            
            $uid['id']          = null;
            $uid['trust_level'] = null;
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
        $_SESSION['msg'][] = '<div class="msg_'.$class.'">✔ '.$msg.'</div>';
    }
  
    // Возвращает дату, переформатированную в строку типа "2 дня назад"
    // Временная, заменить
    public static function ru_date($time, $time_limit = 604800, $out_format = 'Y-m-d H:i', $formats = null, $time_now = null)
    {
        $timestamp = strtotime($time);
         
        if (!$timestamp)
        {
            return false;
        }

        if ($formats == null)
        {
            $formats = array(
                'YEAR' => '%s лет назад',
                'MONTH' => '%s месяцев назад',
                'DAY' => '%s дней назад',
                'HOUR' => '%s час. назад',
                'MINUTE' => '%s минут назад',
                'SECOND' => '%s секунд назад',
                'YEARS' => '%ss лет назад',
                'MONTHS' => '%s месяцев назад',
                'DAYS' => '%ss дней назад',
                'HOURS' => '%ss час. назад',
                'MINUTES' => '%s минут назад',
                'SECONDS' => '%ss секунд назад'
            );
        }

        $time_now = $time_now == null ? time() : $time_now;
        $seconds = $time_now - $timestamp;

        if ($seconds == 0)
        {
            $seconds = 1;
        }

        if (!$time_limit OR $seconds > $time_limit)
        {
            return date($out_format, $timestamp);
        }

        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $days = floor($hours / 24);
        $months = floor($days / 30);
        $years = floor($months / 12);

        if ($years > 0)
        {
            $diffFormat = 'YEAR';
            if($years > 1){
                $flag = 's';
            }
        }
        else
        {
            if ($months > 0)
            {
                $diffFormat = 'MONTH';
                if($months > 1){
                    $flag = 's';
                }
            }
            else
            {
                if ($days > 0)
                {
                    $diffFormat = 'DAY';
                    if($days > 1){
                        $flag = 's';
                    }
                }
                else
                {
                    if ($hours > 0)
                    {
                        $diffFormat = 'HOUR';
                        if($hours > 1){
                            $flag = 's';
                        }
                    }
                    else
                    {
                        if($minutes > 0){
                            $diffFormat = 'MINUTE';
                            if($minutes > 1){
                                $flag = 's';
                            }
                        }else{
                            $diffFormat = 'SECOND';
                            if($seconds > 1){
                                $flag = 's';
                            }
                        }
                    }
                }
            }
        }

        $flag = [];
        $dateDiff = null;
        switch ($diffFormat)
        {
            case 'YEAR' :
                $dateDiff = sprintf($formats[$diffFormat], $years, $flag);
                break;
            case 'MONTH' :
                $dateDiff = sprintf($formats[$diffFormat], $months, $flag);
                break;
            case 'DAY' :
                $dateDiff = sprintf($formats[$diffFormat], $days, $flag);
                break;
            case 'HOUR' :
                $dateDiff = sprintf($formats[$diffFormat], $hours, $flag);
                break;
            case 'MINUTE' :
                $dateDiff = sprintf($formats[$diffFormat], $minutes, $flag);
                break;
            case 'SECOND' :
                $dateDiff = sprintf($formats[$diffFormat], $seconds, $flag);
                break;
        }

        return $dateDiff;
    }
    
    public static function ru_num($type,$num)
    {
        $strlen_num = strlen($num);
        
        if($num <= 21){
            $numres = $num;
        } elseif($strlen_num == 2){
            $parsnum = substr($num,1,2);
            $numres = str_replace('0','10',$parsnum);
        } elseif($strlen_num == 3){
            $parsnum = substr($num,2,3);
            $numres = str_replace('0','10',$parsnum);
        } elseif($strlen_num == 4){
            $parsnum = substr($num,3,4);
            $numres = str_replace('0','10',$parsnum);
        } elseif($strlen_num == 5){
            $parsnum = substr($num,4,5);
            $numres = str_replace('0','10',$parsnum);
        }

        if($type == 'comm'){
            if($numres == 1){
                $gram_num_record = 'комментарий';
            } elseif($numres == 0){
                $gram_num_record = 'комментариев';
            } elseif($numres < 5){
                $gram_num_record = 'комментария';
            } elseif($numres < 21){
                $gram_num_record = 'комментариев';
            }  elseif($numres == 21){
                $gram_num_record = 'комментарий';
            }
        }
        
        if($type == 'post'){
            if($numres == 1){
                $gram_num_record = 'пост';
            } elseif($numres == 0){
                $gram_num_record = 'постов';
            } elseif($numres < 5){
                $gram_num_record = 'поста';
            } elseif($numres < 21){
                $gram_num_record = 'постов';
            }  elseif($numres == 21){
                $gram_num_record = 'пост';
            }
        }
        
        if($type == 'views'){
            if($numres == 1){
                $gram_num_record = 'просмотр';
            } elseif($numres == 0){
                $gram_num_record = 'просмотров';
            } elseif($numres < 5){
                $gram_num_record = 'просмотра';
            } elseif($numres < 21){
                $gram_num_record = 'просмотр';
            }  elseif($numres == 21){
                $gram_num_record = 'просмотр';
            }
        }

        if($type == 'pm'){
            if($numres == 1){
                $gram_num_record = 'сообщение';
            } elseif($numres == 0){
                $gram_num_record = 'сообщений';
            } elseif($numres < 5){
                $gram_num_record = 'сообщения';
            } elseif($numres < 21){
                $gram_num_record = 'сообщений';
            }  elseif($numres == 21){
                $gram_num_record = 'сообщение';
            }
        }
        
        return $gram_num_record;
    }
    
    // Длина строки
    public static function getStrlen($str)
    {
        return mb_strlen($str, "utf-8");
    }
    
    // Создать случайную строку
	public static function randomString(string $type = 'alnum', int $len = 8): string
	{
		switch ($type)
		{
			case 'alnum':
			case 'numeric':
			case 'nozero':
			case 'alpha':
				switch ($type)
				{
					case 'alpha':
						$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'alnum':
						$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'numeric':
						$pool = '0123456789';
						break;
					case 'nozero':
						$pool = '123456789';
						break;
				}

				// @phpstan-ignore-next-line
				return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
			case 'md5':
				return md5(uniqid((string) mt_rand(), true));
			case 'sha1':
				return sha1(uniqid((string) mt_rand(), true));
			case 'crypto':
				return bin2hex(random_bytes($len / 2));
		}
		// 'basic' type treated as default
		return (string) mt_rand();
	}
    
    // https://github.com/JacksonJeans/php-mail
    public static function mailText($email, $subject='', $message='')
    {

        $mail = new Mail('smtp', [
            'host'      => 'ssl://' . $GLOBALS['conf']['smtphost'],
            'port'      => $GLOBALS['conf']['smtpport'],
            'username'  => $GLOBALS['conf']['smtpuser'],
            'password'  => $GLOBALS['conf']['smtppass']
        ]);

        $mail->setFrom($GLOBALS['conf']['smtpuser'])
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
        
        $private_key = $GLOBALS['conf']['private_key']; 
        
        return self::callApi(array(
            'secret'   => $private_key,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ));
    } 

}