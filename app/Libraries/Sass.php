<?php

class Sass
{
    public static function collect()
    {
        require HLEB_GLOBAL_DIRECTORY . "/app/ThirdParty/scssphp/scss.inc.php";
        
        $compiler = new ScssPhp\ScssPhp\Compiler();

        $compiler->setOutputStyle(ScssPhp\ScssPhp\OutputStyle::COMPRESSED);
        
        $putch = TEMPLATES . DIRECTORY_SEPARATOR . Config::get('general.template') . '/scss/';
        
        $compiler->setImportPaths($putch);

        $scssIn = file_get_contents($putch . 'theme.scss');

        $file = $compiler->compileString($scssIn)->getCss();
  
        self::build($file);
    }
    
    public static function build($file)
    {
        file_put_contents( HLEB_GLOBAL_DIRECTORY . '/public/assets/css/style.css', $file);
    }
    
}

