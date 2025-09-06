<?php

declare(strict_types=1);

namespace Modules\Admin\Validate;

use Msg;
use Respect\Validation\Validator as v;

class RulesBadge
{
    public static function rules(array $data, string $icon): true
    {
        $redirect = url('admin.badges');

        if (v::stringType()->length(4, 25)->validate($data['badge_title']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.title') . '»']), 'error', $redirect);
        }
		
        if (v::stringType()->length(12, 250)->validate($data['badge_description']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.description') . '»']), 'error', $redirect);
        }
		
        if (v::stringType()->length(12, 250)->validate($icon) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.icon') . '»']), 'error', $redirect);
        }

        return true;
    }
}
