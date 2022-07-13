<?php

namespace Modules\Admin\App;

use \ScssPhp\ScssPhp\Compiler;

class Sass
{
    public static function collect()
    {
        $compiler = new Compiler();

        $compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);

        foreach (config('css.path_css') as $key => $putch) {
            self::build($compiler, $putch, $key);
        }
    }

    public static function build($compiler, $putch, $key)
    {
        $compiler->setImportPaths($putch);
        $file = $compiler->compileString(file_get_contents($putch . 'build.scss'))->getCss();
        file_put_contents(HLEB_GLOBAL_DIRECTORY . '/public/assets/css/' . $key . '.css', $file);

        return true;
    }
}
