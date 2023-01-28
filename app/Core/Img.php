<?php

class Img
{
    // File paths for storing avatars, banners, etc.
    // Пути к файлам для хранения аватарок, баннеров и т.д.
    const PATH =  [
        'avatars'               => '/uploads/users/avatars/',
        'avatars_small'         => '/uploads/users/avatars/small/',

        'users_cover'           => '/uploads/users/cover/',
        'users_cover_small'     => '/uploads/users/cover/small/',

        'facets_logo'           => '/uploads/facets/logos/',
        'facets_logo_small'     => '/uploads/facets/logos/small/',
        'facets_cover'          => '/uploads/facets/cover/',
        'facets_cover_small'    => '/uploads/facets/cover/small/',

        'posts_content'         => '/uploads/posts/content/',
        'posts_cover'           => '/uploads/posts/cover/',
        'posts_thumb'           => '/uploads/posts/thumbnails/',

        'favicons'              => '/uploads/favicons/',
        'thumbs'                => '/uploads/thumbs/'
    ];

    // User's Cover art or thumbnails
    public static function image($file, $alt, $style, $type, $size)
    {
        $img = ($size == 'small') ? self::PATH['facets_logo_small'] . $file : self::PATH['facets_logo'] . $file;

        if ($type == 'post') {
            $img = ($size == 'thumbnails') ? self::PATH['posts_thumb'] . $file : self::PATH['posts_cover'] . $file;
        }

        return '<img class="' . $style . '" src="' . $img . '" title="' . $alt . '" alt="' . $alt . '">';
    }

    // User avatars
    public static function avatar($file, $alt, $style, $size)
    {
        $img = ($size == 'small') ? self::PATH['avatars_small'] . $file : self::PATH['avatars'] . $file;

        return '<img class="' . $style . '" src="' . $img . '" title="' . $alt . '" alt="' . $alt . '">';
    }

    // Icons, screenshots associated with the site
    public static function website($domain, $type, $alt, $css = '')
    {
        $path = ($type == 'thumbs') ? self::PATH['thumbs'] : self::PATH['favicons'];

        $itemprop = ($type == 'thumbs') ? 'itemprop="image"' : '';

        if (file_exists(HLEB_PUBLIC_DIR . $path . $domain . '.png')) {
            return '<img ' . $itemprop . ' class="' . $css . '" src="' . $path . $domain . '.png" title="' . $alt . '" alt="' . $alt . '">';
        }

        return '<img class="mr5 ' . $css . '" src="' . $path . 'no-link.png" title="' . $alt . '" alt="' . $alt . '">';
    }

    // Cover of users, blog 
    public static function cover($file, $type)
    {
        return $type == 'blog' ? self::PATH['facets_cover'] . $file : self::PATH['users_cover'] . $file;
    }
}
