<?php

declare(strict_types=1);

namespace App\Validate;

class RulesPost extends Validator
{
    public static function rules($title, $content, $redirect)
    {
        $title = str_replace("&nbsp;", '', $title);

        self::length($title, 6, 250, 'title', $redirect);
        self::length($content, 6, 25000, 'content', $redirect);

        return true;
    }
}
