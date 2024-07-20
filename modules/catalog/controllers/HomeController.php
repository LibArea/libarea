<?php

declare(strict_types=1);

namespace Modules\Catalog\Controllers;

use Hleb\Base\Module;
use Modules\Catalog\Models\{WebModel, UserAreaModel};
use Html, Meta;

class HomeController extends Module
{
    public function index()
    {
		$this->container->user()->id();
		
        return view(
            'home',
            [
                'meta'  => self::metadata(),
                'data'  => [
                    'sheet'             => 'main',
                    'items'             => WebModel::feedItem(false, false, Html::pageNumber(), 'main', false),
                    'user_count_site'   => $this->container->user()->admin() ? 0 : UserAreaModel::getUserSitesCount(),
                    'audit_count'       => UserAreaModel::auditCount(),
					'categories' 		=> config('main', 'categories'),
                ]
            ]
        );
    }

    public static function metadata()
    {
        return Meta::get(
            __('web.main_title'),
            __('web.main_desc'),
            [
                'og'         => true,
                'imgurl'     => config('meta', 'img_path_web'),
                'url'        => config('meta', 'url'),
            ]
        );
    }
}
