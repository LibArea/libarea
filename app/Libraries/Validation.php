<?php

class Validation
{
    public static function Email($email, $redirect)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::Returns(__('email.correctness'), 'error', $redirect);
        }
        return true;
    }

    public static function Length($name, $content, $min, $max, $redirect)
    {
        $name = str_replace(" ", '', $name);
        if (Html::getStrlen($name) < $min || Html::getStrlen($name) > $max) {
            $text = __('string.length', ['name' => '«' . __($content) . '»', 'min' => $min, 'max' => $max]);
            self::Returns($text, 'error', $redirect);
        }
        return true;
    }

    public static function Slug($slug, $text, $redirect)
    {
        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $slug)) {
            $text = __('slug.correctness', ['name' => '«' . $text . '»']);
            self::Returns($text, 'error', $redirect);
        }
        return true;
    }

    public static function Returns($text, $status, $redirect = '/')
    {
        Html::addMsg($text, $status);
        redirect($redirect);
    }

    public static function ComeBack($text, $status, $redirect = '/')
    {
        Html::addMsg(__($text), $status);
        redirect($redirect);
    }
}
