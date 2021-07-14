<?php

namespace Lori;

use App\Models\ContentModel;
use App\Models\UserModel;
use MyParsedown;

class Content
{
    // Работа с контентом (Parsedown)
    public static function text($content, $type)
    {
        $Parsedown = new MyParsedown();
        $Parsedown->setSafeMode(true); //безопасность
         
        if ($type  == 'text') {
            $text   = $Parsedown->text($content);
        } else {
            $text   = $Parsedown->line($content);
        }
        $text   = self::parseUser($text); 
        return self::parseUrl($text);
    }
  
    // Аудит
	public static function stopWordsExists($content, $replace = '*')
	{
        $stop_words = ContentModel::getStopWords();
        
		foreach($stop_words as $word) {
            
            $word = trim($word['stop_word']);
            
			if (!$word) {
				continue;
			}

			if (substr($word, 0, 1) == '{' AND substr($word, -1, 1) == '}') {
                
				if (preg_match(substr($word, 1, -1), $content)) {
					return true;
				}
                
			} else {
				if (strstr($content, $word)) {
					return true;
				}
			}
		}

		return false;
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
        if ($info = preg_match($regex, $content, $matches)) {
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
					$user_info = UserModel::getUser($login, 'id');
				}
				else {
					$user_info = UserModel::getUser($login, 'slug');
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
}
