<?php

declare(strict_types=1);

namespace App\Services\Meta;

use Meta;

class Profile
{
    public static function metadata($sheet, $user)
    {
        if ($sheet == 'profile') {
            $information = $user['about'];
        }

        $name = $user['login'];
        if ($user['name']) {
            $name = $user['name'] . ' (' . $user['login'] . ') ';
        }

        $title = __('meta.' . $sheet . '_title', ['name' => $name]);
        $description  = __('meta.' . $sheet . '_desc', ['name' => $name, 'information' => $information ?? '...']);

        switch ($sheet) {
            case 'profile_posts':
                $url    = url('profile.posts', ['login' => $user['login']]);
                break;
            case 'profile_comments':
                $url    = url('profile.comments', ['login' => $user['login']]);
                break;
            default:
                $url    = url('profile', ['login' => $user['login']]);
        }

        $meta = [
            'og'        => true,
            'imgurl'    => '/uploads/users/avatars/' . $user['avatar'],
            'url'       => $url,
        ];

        return Meta::get($title, $description, $meta);
    }
}
