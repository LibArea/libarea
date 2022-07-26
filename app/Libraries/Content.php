<?php

use App\Models\AuditModel;

class Content
{
    // Работа с контентом (Parsedown and HTMLPurifier)
    public static function text($content, $type)
    {
        $text    = Parser::content($content, $type);

        return self::parseUser($text);
    }

    // TODO: Let's check the simple version for now.
    public static function cut($text, $length = 800, $charset = 'UTF-8')
    {
        $beforeCut = $text;
        $afterCut = false;
        if (preg_match("#^(.*)<cut([^>]*+)>(.*)$#Usi", $text, $match)) {
            $beforeCut  = $match[1];
            $afterCut   = $match[3];
        }

        if (!$afterCut) {
            $beforeCut = self::fragment($text, $length);
        }

        $button = false;
        if ($afterCut || mb_strlen($text, $charset) > $length) {
            $button = true;
        }

        return ['content' => $beforeCut, 'button' => $button];
    }

    // Getting a piece of text
    public static function fragment($str, $lenght = 100, $end = '...', $charset = 'UTF-8', $token = '~')
    {
        $str = str_replace('&gt;', '', strip_tags($str));
        if (mb_strlen($str, $charset) >= $lenght) {
            $wrap = wordwrap($str, $lenght, $token);
            $str_cut = mb_substr($wrap, 0, mb_strpos($wrap, $token, 0, $charset), $charset);
            return $str_cut .= $end;
        }
        
        return $str;
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
