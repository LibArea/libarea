<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use Hleb\Scheme\App\Controllers\MainController;
use UserData;

class Controller extends MainController
{
    protected $user;

    protected $pageNumber;

    public function __construct()
    {
        $this->user = UserData::get();
        $this->pageNumber = self::pageNumber();
    }

    public static  function theme($file)
    {
        $tpl_puth = UserData::getUserTheme() . DIRECTORY_SEPARATOR . $file;

        if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $tpl_puth . '.php')) {
            $tpl_puth = 'default/' . $file;
        }

        return $tpl_puth;
    }

    public static function render($name, $data = [])
    {
        if (config('general.site_disabled')  && !UserData::checkAdmin()) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/site_off.php';
            hl_preliminary_exit();
        }

        return render(
            [
                self::theme('/header'),
                self::theme('/content' . $name),
                self::theme('/footer')
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
}
