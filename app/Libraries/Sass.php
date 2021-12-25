<?php

class Sass
{
    public static function collect()
    {
        require HLEB_GLOBAL_DIRECTORY . "/app/ThirdParty/scssphp/scss.inc.php";
        
        $compiler = new ScssPhp\ScssPhp\Compiler();

        $compiler->setOutputStyle(ScssPhp\ScssPhp\OutputStyle::COMPRESSED);
        
        $putch = TEMPLATES . DIRECTORY_SEPARATOR . Config::get('general.template') . '/css/scss/';
        
        $compiler->setImportPaths($putch);

        self::build($compiler, $putch);
        
        self::buildForCSSHelp($compiler, $putch);
    }
    
    public static function build($compiler, $putch)
    {
        $scssIn = file_get_contents($putch . 'build.scss');

        $file = $compiler->compileString($scssIn)->getCss();
        
        file_put_contents( HLEB_GLOBAL_DIRECTORY . '/public/assets/css/style.css', $file);
    }
    
    public static function buildForCSSHelp($compiler, $putch)
    {
        $scssIn = file_get_contents($putch . '/help/build-help.scss');

        $file = $compiler->compileString($scssIn)->getCss();
        
        file_put_contents( HLEB_GLOBAL_DIRECTORY . '/public/assets/css/color-help.css', $file);
    }
    
}

