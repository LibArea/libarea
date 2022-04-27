<?php

class Sass
{
    public static function collect()
    {
        require HLEB_GLOBAL_DIRECTORY . "/app/ThirdParty/scssphp/scss.inc.php";

        $compiler = new ScssPhp\ScssPhp\Compiler();

        $compiler->setOutputStyle(ScssPhp\ScssPhp\OutputStyle::COMPRESSED);

        foreach (config('scss') as $key => $putch) {
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
