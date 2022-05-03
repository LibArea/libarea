<?php

use App\Models\ContentModel;

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
            $text   = self::parseSpoiler($text);
        } else {
            $text   = $Parsedown->line($content);
        }

        return self::parseUser($text);
    }

    public static function parseVideo($content)
    {
        $regex  = '/(?:(?:https?:)?(?:\/\/)?)(?:(?:www)?\.)?youtube\.(?:.+?)\/(?:(?:watch\?v=)|(?:embed\/))([a-zA-Z0-9_-]{11})/';
        $info   = preg_match($regex, $content, $matches);
        if ($info) {
            $id  = $matches[1];
            $url = "https://www.youtube.com/embed/" . basename($id);
            $bodyvideo = "<object class='video-object mb-video-object' data='$url'></object>";

            return str_replace($matches[0], $bodyvideo, $content);
        }

        return  $content;
    }

    public static function parseSpoiler($content)
    {
        $regexpSp = '/\{spoiler(?!.*\{spoiler)(\s?)(?(1)(.*?))\}(.*?)\{\/spoiler\}/is';
        while (preg_match($regexpSp, $content)) {
            $content = preg_replace($regexpSp, "<details><summary>" . __('app.see_more') . "</summary>$2$3</details>", $content);
        }

        $regexpAu = '/\{auth(?!.*\{auth)(\s?)(?(1)(.*?))\}(.*?)\{\/auth\}/is';
        while (preg_match($regexpAu, $content)) {
            if (UserData::checkActiveUser()) {
                $content = preg_replace($regexpAu, "<dev class=\"txt-closed\"><i class=\"bi-unlock gray-600 mr5\"></i> $2$3</dev>", $content);
            } else {
                $content = preg_replace($regexpAu, "<dev class=\"txt-closed gray-600\"><i class=\"bi-lock mr5 red-200\"></i>" . __('app.text_closed') . "...</dev>", $content);
            }
        }

        return $content;
    }

    // Парсинг user login / uid
    public static function parseUser($content, $with_user = false, $to_uid = false)
    {
        preg_match_all('/@([^@,:\s,]+)/i', strip_tags($content), $matchs);

        if (is_array($matchs[1])) {
            $match_name = [];
            foreach ($matchs[1] as $key => $login) {
                if (in_array($login, $match_name)) {
                    continue;
                }

                $match_name[] = $login;
            }

            $match_name = array_unique($match_name);

            arsort($match_name);

            $all_users = [];

            $content_uid = $content;

            foreach ($match_name as $key => $login) {

                if (preg_match('/^[0-9]+$/', $login)) {
                    $user_info = ContentModel::getUsers($login, 'id');
                } else {
                    $user_info = ContentModel::getUsers($login, 'slug');
                }

                if ($user_info) {
                    $content = str_replace('@' . $login, '<a href="/@' .  $user_info['login'] . '" class="to-user">@' . $user_info['login'] . '</a>', $content);

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

    // Остановим изменение (добавление) контента
    public static function stopContentQuietМode($user_limiting_mode)
    {
        if ($user_limiting_mode == 1) {
            Validation::ComeBack('msg.silent_mode', 'error', '/');
        }

        return true;
    }

    public static function change($content)
    {
        $StringArray = [
            '(c)' => '©',
            '(r)' => '®',
            ' - ' => ' — ',
            '+/-' => '±'
        ];

        foreach ($StringArray as $Key => $Val) {
            $content = str_replace($Key, $Val, $content);
        }

        return $content;
    }

    public static function noMarkdown($content)
    {
        $md = '/(?:__|[*#])|\[(.*?)\]\(.*?\)/';
        $content = preg_replace($md, "", $content);

        return $content;
    }
}
