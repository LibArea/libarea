<?php

namespace Lori;

use App\Models\ContentModel;
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
            $text   = self::parseVideo($text);
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

        foreach ($stop_words as $word) {

            $word = trim($word['stop_word']);

            if (!$word) {
                continue;
            }

            if (substr($word, 0, 1) == '{' and substr($word, -1, 1) == '}') {

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

    public static function parseVideo($content)
    {
        $regex  = '/https:\/\/(www\.|m\.){0,1}(youtube\.com\/watch\?v=|youtu\.be)([^< \n]+)/mi';
        $info   = preg_match($regex, $content, $matches);
        if ($info) {
            $id  = $matches[3];
            $url = "https://www.youtube.com/embed/" . basename($id);
            $bodyvideo =
                "<div class='video'>" .
                "<object class='video-object' data='$url'></object>" .
                "</div>";

            return str_replace($matches[0], $bodyvideo, $content);
        }

        return  $content;
    }

    // Парсинг user login / uid
    public static function parseUser($content, $with_user = false, $to_uid = false)
    {
        preg_match_all('/@([^@,:\s,]+)/i', strip_tags($content), $matchs);

        if (is_array($matchs[1])) {
            $match_name = array();

            foreach ($matchs[1] as $key => $login) {
                if (in_array($login, $match_name)) {
                    continue;
                }

                $match_name[] = $login;
            }

            $match_name = array_unique($match_name);

            arsort($match_name);

            $all_users = array();

            $content_uid = $content;

            foreach ($match_name as $key => $login) {

                // Добавим по id, нужна будет для notif
                if (preg_match('/^[0-9]+$/', $login)) {
                    $user_info = ContentModel::getUsers($login, 'id');
                } else {
                    $user_info = ContentModel::getUsers($login, 'slug');
                }

                if ($user_info) {
                    $content = str_replace('@' . $login, '<a href="/u/' . $user_info['user_login'] . '" class="to-user">@' . $user_info['user_login'] . '</a>', $content);

                    if ($to_uid) {
                        $content_uid = str_replace('@' . $login, '@' . $user_info['user_id'], $content_uid);
                    }

                    if ($with_user) {
                        $all_users[] = $user_info['user_id'];
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

    // Остановим изменение (добавление) контента
    public static function stopContentQuietМode($uid)
    {
        if ($uid['user_limiting_mode'] == 1) {
            addMsg(lang('limiting-mode-1'), 'error');
            redirect('/');
        }

        return true;
    }

    public static function change($content)
    {
        $StringArray = [
            '(c)' => '©',
            '(r)' => '®',
            '+/-' => '±'
        ];

        foreach ($StringArray as $Key => $Val) {
            $content = str_replace($Key, $Val, $content);
        }

        return $content;
    }
}
