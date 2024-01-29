<?php

// declare(strict_types=1);

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

    public static function pageNumber()
    {
        $page = Request::getGet('page');
        $pageNumber = (int)$page ?? null;

        return $pageNumber <= 1 ? 1 : $pageNumber;
    }

	public static function render(string $name, array $data = [], string $part = 'base')
	{
		self::closing();

		$body = UserData::getUserTheme() . DIRECTORY_SEPARATOR;
		$header = $body . '/global/' . $part . '-header';
		$footer = $body . '/global/' . $part . '-footer';

		if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $body . '/content' . $name . '.php')) {
			$body = 'default';
		}

		if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $header . '.php')) {
			$header = 'default/global/' . $part . '-header';
		}

		if (!file_exists(TEMPLATES . DIRECTORY_SEPARATOR . $footer . '.php')) {
			$footer = 'default/global/' . $part . '-footer';
		}

		$data['topics_user'] = UserData::getUserFacets();

		return render(
			[
				$header,
				$body . '/content' . $name,
				$footer
			],
			$data
		);
	}

    public static function closing()
    {
        if (config('general.site_disabled')  && !UserData::checkAdmin()) {
            self::insert('/site-off');
            hl_preliminary_exit();
        }

        return true;
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
}
