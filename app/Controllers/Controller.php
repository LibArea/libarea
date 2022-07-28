<?php

// declare(strict_types=1);

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use Hleb\Scheme\App\Controllers\MainController;
use UserData;

class Controller extends MainController
{
    public function __construct()
    {
        $this->user = UserData::get();
        $this->pageNumber = self::pageNumber();
    }

    public static function pageNumber()
    {
        $string = Request::get();
        $arr    = $string['page'] ?? null;
        $pageNumber = 1;

        if ($arr) {
            $arr = explode('.', $string['page']);

            $attr = $arr[1] ?? null;
            if ($attr != 'html') {
                include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
                hl_preliminary_exit();
            }

            if ($arr[0] < 0) {
                include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
                hl_preliminary_exit();
            }
            $pageNumber = (int)$arr[0];
        }
        return $pageNumber <= 1 ? 1 : $pageNumber;
    }

    public static function render($name, $component, $data = [])
    {
        if (config('general.site_disabled')  && !UserData::checkAdmin()) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/site_off.php';
            hl_preliminary_exit();
        }

        $body = $header = $footer  = UserData::getUserTheme() . DIRECTORY_SEPARATOR;
        if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $body . '/content' . $name . '.php')) {
            $body =  'default';
        }

        if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $header . '/global/' . $component . '.header.php')) {
            $header =  'default';
        }

        if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $footer . '/global/' . $component . '.footer.php')) {
            $footer =  'default';
        }

        return render(
            [
                $header . '/global/' . $component . '.header',
                $body . '/content' . $name,
                $footer . '/global/' . $component . '.footer'
            ],
            $data
        );
    }

    public static function insert(string $hlTemplatePath, array $params = [])
    {
        extract($params);

        unset($params);

        $tpl_puth = UserData::getUserTheme() . DIRECTORY_SEPARATOR . trim($hlTemplatePath, '/\\');

        if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $tpl_puth . '.php')) {
            $tpl_puth = 'default' . $hlTemplatePath;
        }

        require TEMPLATES . DIRECTORY_SEPARATOR . $tpl_puth . '.php';
    }

    public static function error404($arr = [])
    {
        if (empty($arr)) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        return true;
    }

    public static function redirection($variable, $redirect = '/')
    {
        if (!$variable) {
            redirect($redirect);
        }
        return true;
    }
}
