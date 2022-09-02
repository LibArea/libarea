<?php

namespace App\Validate;

class RulesTeam extends Validator
{
    public static function rulesAdd($data, $redirect)
    {
        self::Length($data['name'], 6, 250, 'title', $redirect));
        self::Length($data['content'], 6, 5000, 'content', $redirect);
        
        return true;
    }
}
