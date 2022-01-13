<?php

use App\Models\{ContentModel, ActionModel};

class Validation
{
    public static function Email($email, $redirect)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            addMsg(Translate::get('email.correctness'), 'error');
            redirect($redirect);
        }
        return true;
    }

    public static function Length($name, $content, $min, $max, $redirect)
    {
        if (self::getStrlen($name) < $min || self::getStrlen($name) > $max) {

            $text = sprintf(Translate::get('string.length'), '«' . $content . '»', $min, $max);
            addMsg($text, 'error');
            redirect($redirect);
        }
        return true;
    }

    public static function Url($url, $text, $redirect)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {

            $text = sprintf(Translate::get('url.correctness'), '«' . $url . '»');
            addMsg($text, 'error');
            redirect($redirect);
        }
        return true;
    }

    public static function Slug($slug, $text, $redirect)
    {
        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $slug)) {

            $text = sprintf(Translate::get('slug.correctness'), '«' . $text . '»');
            addMsg($text, 'error');
            redirect($redirect);
        }
        return true;
    }

    // Длина строки
    public static function getStrlen($str)
    {
        return mb_strlen($str, "utf-8");
    }

    // Права для TL
    // $trust_leve - уровень доверие участника
    // $allowed_tl - с какого TL разрешено
    // $count_content - сколько уже создал
    // $count_total - сколько разрешено
    public static function validTl($trust_level, $allowed_tl, $count_content, $count_total)
    { 
        if ($trust_level < $allowed_tl) {
            redirect('/');
        }

        if ($count_content >= $count_total) {
            redirect('/');
        }

        return true;
    }

}
