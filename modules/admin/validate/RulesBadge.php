<?php

declare(strict_types=1);

namespace Modules\Admin\Validate;

use App\Validate\Validator;

class RulesBadge extends Validator
{
    public static function rules(array $data, string $icon): true
    {
        $redirect = url('admin.badges');

        self::Length($data['badge_title'], 4, 25, 'msg.title', $redirect);
        self::Length($data['badge_description'], 12, 250, 'msg.description', $redirect);
        self::Length($icon, 12, 250, 'msg.icon', $redirect);

        return true;
    }
}
