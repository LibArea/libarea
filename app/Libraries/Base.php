<?php

use App\Models\NotificationsModel;

class Base
{

    public static function getUid() {
        $user = Request::getSession('account') ?? [];
        $uid = [];
        if (!empty($user['user_id'])) {
            $uid['id']          = $user['user_id'];
            $uid['login']       = $user['login'] ?? 'undefined'; 
            $uid['trust_level'] = $user['trust_level'];
            $uid['notif']       = NotificationsModel::usersNotification($uid['id']);
        } else {
            $uid['id']           = null;
            $user['trust_level'] = null;
        }
        $uid['msg']     = self::getMsg();
        
        return $uid;
    }
 

    // Возвращает массив сообщений
    public static function getMsg(){

        if (isset($_SESSION['msg'])){
            $msg = $_SESSION['msg'];
        } else {
            $msg = false;
        }

        self::clearMsg();
        return $msg;

    }

    //  Очищает очередь сообщений
    public static function clearMsg(){
       unset($_SESSION['msg']);
    }

    // Добавляем сообщение
    public static function addMsg($msg, $class='info'){
        $_SESSION['msg'][] = '<div class="msg_'.$class.'">✔ '.$msg.'</div>';
    }
  
  
    public static function seo($str, $options = array('transliterate' => true))
    {
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

        $defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => false,
        );

        $options = array_merge($defaults, $options);

        $chars = [

            //Georgian
            'ა' => 'a', 'ბ' => 'b', 'გ' => 'g', 'დ' => 'd', 'ე' => 'e', 'ვ' => 'v', 'ზ' => 'z', 'თ' => 't', 'ი' => 'i',
            'კ' => 'k', 'ლ' => 'l', 'მ' => 'm', 'ნ' => 'n', 'ო' => 'o', 'პ' => 'p', 'ჟ' => 'j', 'რ' => 'r', 'ს' => 's',
            'ტ' => 't', 'უ' => 'u', 'ფ' => 'f', 'ქ' => 'q', 'ღ' => 'g', 'ყ' => 'y', 'ჩ' => 'ch', 'ც' => 'ts', 'ძ' => 'dz',
            'წ' => 'w', 'ჭ' => 'ch', 'ხ' => 'kh', 'ჯ' => 'j', 'ჰ' => 'h',
            ' ' => '-',
            '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '0' => '0',

            //English
            'a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd', 'e' => 'e', 'f' => 'f', 'g' => 'g', 'h' => 'h', 'i' => 'i', 'j' => 'j',
            'k' => 'k', 'l' => 'l', 'm' => 'm', 'n' => 'n', 'o' => 'o', 'p' => 'p', 'q' => 'q', 'r' => 'r', 's' => 's', 't' => 't',
            'u' => 'u', 'v' => 'v', 'w' => 'w', 'x' => 'x', 'y' => 'y', 'z' => 'z',

            //Symbols
            '&' => '&', '!' => '!', '@' => '@', '#' => '#', '$' => '$', '(' => '(', ')' => ')', '%' => '%', '^' => '^', '*' => '*', '©' => '(c)',

            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',

            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',

            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',

            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
            'Ž' => 'Z',
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z',

            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
            'Ż' => 'Z',
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',

            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z'
        ];

        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

        if ($options['transliterate']) {
            $str = str_replace(array_keys($chars), $chars, $str);
        }

        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

        $str = trim($str, $options['delimiter']);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
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
    
    public static function ru_num($type,$num){
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
                $gram_num_record = 'комментарев';
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
        
            return $gram_num_record;
    }
    
    // Длина строки
    public static function getStrlen($str)
    {
        return mb_strlen($str, "utf-8");
    }
    
}