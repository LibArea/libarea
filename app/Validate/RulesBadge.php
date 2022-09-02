<?php

namespace App\Validate;

class RulesBadge extends Validator
{
    public static function rules($data, $icon)
    {
        $redirect = url('admin.badges');
        
        self::Length($data['badge_title'], '4', '25', 'msg.title', $redirect);
        self::Length($data['badge_description'], '12', '250', 'msg.description', $redirect);
        self::Length($icon, '12', '250', 'msg.icon', $redirect);
        
        return true;
    }
}
