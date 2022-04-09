<?php

class Tpl
{
    public static function agTheme($file)
    {
        $tpl_puth = self::userTheme() . DIRECTORY_SEPARATOR . $file;

        if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $tpl_puth . '.php')) {
            $tpl_puth = 'default' . $file;
        }

        return $tpl_puth;
    }

    public static function agRender($name, $data = [])
    {
        if (Config::get('general.site_disabled')  && !UserData::checkAdmin()) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/site_off.php';
            hl_preliminary_exit();
        }

        return render(
            [
                self::agTheme('/header'),
                self::agTheme('/content' . $name),
                self::agTheme('/footer')
            ],
            array_merge($data, ['user' => UserData::get()])
        );
    }

    public static function agIncludeCachedTemplate(string $template, array $params = [])
    {
        hleb_include_cached_template(self::agTheme($template), $params);
    }

    public static function agIncludeTemplate(string $template, array $params = [])
    {
        return hleb_include_template(self::agTheme($template), $params);
    }

    public static function insert(string $hlTemplatePath, array $params = [])
    {
        extract($params);

        unset($params);

        $tpl_puth = self::userTheme() . DIRECTORY_SEPARATOR . trim($hlTemplatePath, '/\\');
 
        if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $tpl_puth . '.php')) {
            $tpl_puth = 'default' . $hlTemplatePath;
        }

        require TEMPLATES . DIRECTORY_SEPARATOR . $tpl_puth . '.php';
    }
    
    public static function userTheme()
    {
        $user  = UserData::get();
        
        return $user['template'];
    }
}
