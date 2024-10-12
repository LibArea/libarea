<?php

declare(strict_types=1);

namespace App\Validate;

class RulesMessage extends Validator
{
    public static function rules($content, $redirect)
    {
        self::length($content, 6, 10000, 'content', $redirect);

        return true;
    }
}
