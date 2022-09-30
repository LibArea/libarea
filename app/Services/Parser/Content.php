<?php

declare(strict_types=1);

namespace App\Services\Parser;

use App\Services\Parser\Convert;
use App\Models\AuditModel;
use UserData;

class Content
{
    // Content management (Parsedown, Typograf)
    public static function text(string $content, string $type)
    {
        $text = self::parse($content, $type);
        $text = self::details($text);
        $text = self::emoji($text);
        $text = self::red($text);

        return self::parseUser($text);
    }

    public static function parse(string $content, string $type)
    {
        $content = str_replace('{cut}', '', $content);

        $Parsedown = new Convert();

        // !!! Enable by default
        $Parsedown->setSafeMode(true);

        // New line
        $Parsedown->setBreaksEnabled(true);

        // Configure
        $Parsedown->voidElementSuffix = '>'; // HTML5

        $Parsedown->linkAttributes = function ($Text, $Attributes, &$Element, $Internal) {
            if (!$Internal) {
                return [
                    'rel' => 'noopener nofollow ugc',
                    'target' => '_blank',
                ];
            }
            return [];
        };

        $Parsedown->abbreviationData = [
            'CSS' => 'Cascading Style Sheet',
            'HTML' => 'Hyper Text Markup Language',
            'JS' => 'JavaScript',
            'HLEB' => 'PHP Framework',
        ];

        // Use
        $text = $Parsedown->text($content);

        $t = new \Akh\Typograf\Typograf();

        return $t->apply($text);
    }

    public static function red($content)
    {
        $regexpRed = '/\{red(?!.*\{red)(\s?)(?(1)(.*?))\}(.*?)\{\/red\}/is';
        if (preg_match($regexpRed, $content, $matches)) {
            $content = preg_replace($regexpRed, "<span class=\"red\">$2$3</span>", $content);
        }

        return  $content;
    }

    public static function emoji($content)
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

    public static function details($content)
    {
        $regexpSp = '/\{details(?!.*\{details)(\s?)(?(1)(.*?))\}(.*?)\{\/details\}/is';
        while (preg_match($regexpSp, $content)) {
            $content = preg_replace($regexpSp, "<details><summary>" . __('app.see_more') . "</summary>$2$3</details>", $content);
        }

        $regexpAu = '/\{auth(?!.*\{auth)(\s?)(?(1)(.*?))\}(.*?)\{\/auth\}/is';
        while (preg_match($regexpAu, $content)) {
            if (UserData::checkActiveUser()) {
                $content = preg_replace($regexpAu, "<dev class=\"txt-closed\">$2$3</dev>", $content);
            } else {
                $content = preg_replace($regexpAu, "<dev class=\"txt-closed gray-400\">" . __('app.text_closed') . "...</dev>", $content);
            }
        }

        return $content;
    }

    // TODO: Let's check the simple version for now.
    public static function cut($text, $length = 800)
    {
        $charset = 'UTF-8';
        $beforeCut = $text;
        $afterCut = false;

        if (preg_match("#^(.*){cut([^}]*+)}(.*)$#Usi", $text, $match)) {
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
    public static function fragment($text, $lenght = 100, $strip = false)
    {
        $charset = 'UTF-8';
        $token = '~';
        $end = '...';

        if ($strip) {
            $text = str_replace('&gt;', '', strip_tags($text));
        }

        if (mb_strlen($text, $charset) >= $lenght) {
            $wrap = wordwrap($text, $lenght, $token);
            $str_cut = mb_substr($wrap, 0, mb_strpos($wrap, $token, 0, $charset), $charset);
            return $str_cut .= $end;
        }

        return $text;
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
