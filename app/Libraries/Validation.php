<?php

class Validation
{
    // Validation::email
    public static function email($email, $redirect)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            self::comingBack(__('msg.email_correctness'), 'error', $redirect);
        }
        return true;
    }

    // Validation::url
    public static function url($url, $redirect)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            self::comingBack(__('msg.url_correctness'), 'error', $redirect);
        }
        return true;
    }

    // Validation::length
    public static function length($content, $min, $max, $name, $redirect = '/')
    {
        if (self::getStrlen($content) < $min || self::getStrlen($content) > $max) {
            self::comingBack(__('msg.string_length', ['name' => '«' . __('msg.' . $name) . '»']), 'error', $redirect);
        }
        return true;
    }

    public static function getStrlen($str)
    {
        return mb_strlen($str, "utf-8");
    }

    public static function comingBack($text, $status, $redirect = '/')
    {
        Msg::add($text, $status);
        redirect($redirect);
    }
}
