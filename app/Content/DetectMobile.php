<?php

declare(strict_types=1);

class DetectMobile
{
    public static function index()
    {
        $info = parse_user_agent();

        $allowed = ['Android', 'iPhone', 'iPad', 'Windows Phone OS', 'Kindle', 'Kindle Fire', 'BlackBerry', 'Playbook'];
        if (in_array($info['platform'], $allowed)) {
            return true;
        }

        return false;
    }
}
