<?php

declare(strict_types=1);

namespace App\Validate;

use Msg;

class Validator
{
    public static function email(string $email, string $redirect)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            Msg::redirect(__('msg.email_correctness'), 'error', $redirect);
        }
        return true;
    }

    public static function url(string $url, string $redirect)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            Msg::redirect(__('msg.url_correctness'), 'error', $redirect);
        }
        return true;
    }

    public static function length(string $content, int $min, int $max, string $name, string $redirect = '/')
    {
        if (self::getStrlen($content) < $min || self::getStrlen($content) > $max) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.' . $name) . '»']), 'error', $redirect);
        }
        return true;
    }

    public static function getStrlen(string $str)
    {
        return mb_strlen($str, "utf-8");
    }
}
