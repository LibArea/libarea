<?php

namespace Modules\Admin\App;

use MatthiasMullie\Minify;

class Sass
{
    public static function collect()
    {
        foreach (config('assembly-js-css.path_css') as $key => $putch) {
            self::buildCss($putch, $key);
        }
        
        foreach (config('assembly-js-css.path_js') as $key => $putch) {
            self::buildJs($putch, $key);
        }
        
    }

    public static function buildCss($putch, $key)
    {
        $minifier = new Minify\CSS($putch . 'build.css');
        $minifier->minify(HLEB_GLOBAL_DIRECTORY . '/public/assets/css/' . $key . '.css');

        return true;
    }
    
    public static function buildJs($putch, $key)
    {
        $minifier = new Minify\JS($putch . $key . '.js');
        $minifier->minify(HLEB_GLOBAL_DIRECTORY . '/public/assets/js/' . $key . '.js');

        return true;
    }
}
