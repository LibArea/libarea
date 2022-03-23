<?php

class Validation
{
    public static function Email($email, $redirect)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Html::addMsg('email.correctness', 'error');
            redirect($redirect);
        }
        return true;
    }

    public static function Length($name, $content, $min, $max, $redirect)
    {
        if (Html::getStrlen($name) < $min || Html::getStrlen($name) > $max) {
            $text = sprintf(Translate::get('string.length'), '«' . $content . '»', $min, $max);
            Html::addMsg($text, 'error');
            redirect($redirect);
        }
        return true;
    }

    public static function Url($url, $text, $redirect)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {

            $text = sprintf(Translate::get('url.correctness'), '«' . $url . '»');
            Html::addMsg($text, 'error');
            redirect($redirect);
        }
        return true;
    }

    public static function Slug($slug, $text, $redirect)
    {
        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $slug)) {

            $text = sprintf(Translate::get('slug.correctness'), '«' . $text . '»');
            Html::addMsg($text, 'error');
            redirect($redirect);
        }
        return true;
    }
}
