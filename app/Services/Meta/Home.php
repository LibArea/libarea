<?php

declare(strict_types=1);

namespace App\Services\Meta;

use Meta;

class Home
{
    public static function metadata($sheet)
    {
        switch ($sheet) {
            case 'questions':
                $url    = '/questions';
                break;
            case 'posts':
                $url    = '/posts';
                break;
            case 'top':
                $url    = '/top';
                break;
            default:
                $url    = '/';
        }

        $meta = [
            'main'      => 'main',
            'og'        => true,
            'imgurl'    => config('meta.img_path'),
            'url'       => $url,
        ];

        return Meta::get(config('meta.' . $sheet . '_title'), config('meta.' . $sheet . '_desc'), $meta);
    }
}
