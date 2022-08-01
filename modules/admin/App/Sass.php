<?php

namespace Modules\Admin\App;

use MatthiasMullie\Minify;

class Sass
{
    public static function collect()
    {
        foreach (config('css.path_css') as $key => $putch) {
            self::build($compiler, $putch, $key);
        }
    }

    public static function build($compiler, $putch, $key)
    {
        $minifier = new Minify\CSS($putch . 'build.scss');
        $minifier->minify(HLEB_GLOBAL_DIRECTORY . '/public/assets/css/' . $key . '.css', $file);

        return true;
    }
}
