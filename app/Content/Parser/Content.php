<?php

declare(strict_types=1);

namespace App\Content\Parser;

use App\Content\Parser\{Convert, Filter};
use App\Models\ParserModel;
use App\Bootstrap\Services\User\UserData;
use LitEmoji\LitEmoji;
use Img;

class Content
{
    // Content management (Parsedown, Typograf)
    public static function text(string $content, string $type)
    {
        $text = self::parseUsers($content);
        $text = self::parse($text);
        $text = self::details($text);
        $text = self::facets($text);
        $text = LitEmoji::encodeUnicode($text);

        return self::gif($text);
    }

    public static function parse(string $content)
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

        $Parsedown->abbreviationData = __('abbreviations.words');

        $text = $Parsedown->text($content);

        if (UserData::getUserLang() == 'ru') {
            return self::typograf($text);
        }

        return $text;
    }

    public static function typograf(string $text)
    {
        $t = new \Akh\Typograf\Typograf();

        /* $simpleRule = new class extends \Akh\Typograf\Rule\AbstractRule {
            public $name = 'Пример замены';
            protected $sort = 1000;
            public function handler(string $text): string
            {
                return str_replace('agouti.ru', '<a href="https://libarea.ru">libarea.ru</a>', $text);
            }
        };

        $t->addRule($simpleRule); */

        // https://github.com/akhx/typograf/blob/master/docs/RULES.md
        $t->disableRule('Nbsp\*');
        $t->disableRule('Space\*');
        $t->disableRule('Html\*');

        return $t->apply($text);
    }

    public static function gif($content)
    {
        preg_match_all('/\:(\w+)\:/mUs', strip_tags($content), $matchs);

        if (is_array($matchs[1])) {

            $match_name = [];
            foreach ($matchs[1] as $key => $name) {
                if (in_array($name, $match_name)) {
                    continue;
                }

                $match_name[] = $name;
            }

            $match_name = array_unique($match_name);

            arsort($match_name);

            foreach ($match_name as $key => $name) {

                $img = '/assets/images/gif/' . $name . '.gif';
                if (file_exists('.' . $img))
                    $content = str_replace(':' . $name . ':', '<img class="gif" alt="' . $name . '" src="' . $img . '">', $content);
                else
                    $content = $content;
            }
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
                $content = preg_replace($regexpAu, "<dev class=\"txt-closed\">" . __('app.text_closed') . "...</dev>", $content);
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
            $beforeCut = Filter::fragment($text, $length);
        }

        $button = false;
        if ($afterCut || mb_strlen($text, $charset) > $length) {
            $button = true;
        }

        return ['content' => $beforeCut, 'button' => $button];
    }

    public static function facets($content)
    {
        preg_match_all('/#([^#,:\s,]+)/i', strip_tags($content), $matchs);

        if (is_array($matchs[1])) {

            $match_name = [];
            foreach ($matchs[1] as $key => $slug) {
                if (in_array($slug, $match_name)) {
                    continue;
                }

                $match_name[] = $slug;
            }

            $match_name = array_unique($match_name);

            arsort($match_name);

            foreach ($match_name as $key => $slug) {

                if ($info = ParserModel::getFacet($slug)) {
                    $content = str_replace('#' . $slug, '<img class="img-sm emoji mr5" alt="' . $info['facet_title'] . '" src="' . Img::PATH['facets_logo_small'] . $info['facet_img'] . '"><a href="/topic/' . $info['facet_slug'] . '">' . $info['facet_title'] . '</a>', $content);
                }
            }

            return $content;
        }
    }

    public static function parseUsers($content, $with_user = false, $to_uid = false)
    {
        preg_match_all('/(?<=^|\s|>)@([a-z0-9_]+)/i', strip_tags($content), $matchs);

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
                    $user_info = ParserModel::getUser($login, 'id');
                } else {
                    $user_info = ParserModel::getUser($login, 'slug');
                }

                if ($user_info) {
                    $content = str_replace('@' . $login, '[@' . $login . '](/@' .  $login . ')', $content);

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
