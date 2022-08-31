<?php

namespace App\Validation;

class RulesEditSetting extends Validation
{
    public static function rules($data)
    {
        $redirect = url('setting');
        
        self::length($data['name'], 5, 11, 'name', $redirect);
        self::length($data['about'], 5, 255, 'about', $redirect);

        if ($data['public_email']) {
            self::email($data['public_email'], $redirect);
        }

        return true;
    }
}
