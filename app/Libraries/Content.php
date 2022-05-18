<?php

use App\Models\AuditModel;

class Content
{
    // Работа с контентом (Parsedown and HTMLPurifier)
    public static function text($content, $type)
    {
        $res    = Parser::content($content, $type);
        $text   = self::parseRed($res);

        return self::parseUser(self::inlineEmoji($text));
    }

    public static function inlineEmoji($content)
    {
        $pathEmoji =  '/assets/images/emoji/';

        $smiles = array(':)', ':-)');
        $content = str_replace($smiles, '<img class="emoji" src="' . $pathEmoji . 'smile.png">', $content);

        if (preg_match('/\:(\w+)\:/mUs', $content, $matches)) {
            $path =  HLEB_PUBLIC_DIR . "/assets/images/emoji/" . $matches[1];
            $file_ext = "";
            if (file_exists($path . ".png"))
                $file_ext = ".png";
            else if (file_exists($path . ".gif"))
                $file_ext = ".gif";
            if ($file_ext === "")
                return $content;

            $img = $pathEmoji . $matches[1] . $file_ext;
            return str_replace($matches[0], '<img class="emoji" src="' . $img . '">', $content);
        }

        return  $content;
    }

    public static function parseRed($content)
    {
        $regexpRed = '/\{red(?!.*\{red)(\s?)(?(1)(.*?))\}(.*?)\{\/red\}/is';
        if (preg_match($regexpRed, $content, $matches)) {
            $content = preg_replace($regexpRed, "<span class=\"red\">$2$3</span>", $content);
        }

        return  $content;
    }

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
                    $user_info = AuditModel::getUsers($login, 'id');
                } else {
                    $user_info = AuditModel::getUsers($login, 'slug');
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

}
