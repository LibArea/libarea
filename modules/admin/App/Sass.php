<?php

namespace Modules\Admin\App;

use MatthiasMullie\Minify;

class Sass
{
    public static function collect()
    {
        foreach (config('assembly-js-css.path_css') as $key => $putch) {
            self::buildCss(HLEB_GLOBAL_DIRECTORY . $putch, $key);
        }
        
        foreach (config('assembly-js-css.path_js') as $key => $putch) {
            self::buildJs(HLEB_GLOBAL_DIRECTORY . $putch, $key);
        }
        
    }

    public static function buildCss($putch, $key)
    {
        $minifier = new Minify\CSS($putch);
        $minifier->minify(HLEB_PUBLIC_DIR . '/assets/css/' . $key . '.css');

        return true;
    }
    
    public static function buildJs($putch, $key)
    {
        $minifier = new Minify\JS($putch . $key . '.js');
        $minifier->minify(HLEB_PUBLIC_DIR . '/assets/js/' . $key . '.js');

        return true;
    }
}
