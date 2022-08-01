<?php

namespace Modules\Admin\App;

use MatthiasMullie\Minify;

class Sass
{
    public static function collect()
    {
        foreach (config('css.path_css') as $key => $putch) {
            self::build($putch, $key);
        }
    }

    public static function build($putch, $key)
    {
        $minifier = new Minify\CSS($putch . 'build.css');
        $minifier->minify(HLEB_GLOBAL_DIRECTORY . '/public/assets/css/' . $key . '.css');

        return true;
    }
}
