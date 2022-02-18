<?php

class Sass
{
    public static function collect()
    {
        require HLEB_GLOBAL_DIRECTORY . "/app/ThirdParty/scssphp/scss.inc.php";

        $compiler = new ScssPhp\ScssPhp\Compiler();

        $compiler->setOutputStyle(ScssPhp\ScssPhp\OutputStyle::COMPRESSED);

        foreach (Config::get('modules.scss') as $arr) {
            $putch_module = HLEB_GLOBAL_DIRECTORY . '/modules/' . $arr . '/view/default/css/';
            $putch_base = TEMPLATES . DIRECTORY_SEPARATOR . Config::get('general.template') . '/css/scss/';

            $putch = ($arr == 'style') ? $putch_base :  $putch_module;
            self::build($compiler, $putch, $arr);
        }
    }

    public static function build($compiler, $putch, $arr)
    {
        $compiler->setImportPaths($putch);
        $file = $compiler->compileString(file_get_contents($putch . 'build.scss'))->getCss();
        file_put_contents(HLEB_GLOBAL_DIRECTORY . '/public/assets/css/' . $arr . '.css', $file);

        return true;
    }
}
