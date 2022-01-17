<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{PageModel, FacetModel};
use Translate, Content, Config, Tpl;

class PageController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index()
    {
        $facet      = Request::get('facet');
        $slug       = Request::get('slug');

        $page   = PageModel::getPage($slug, $this->user['id'], 'slug');
        pageError404($page);

        $facet   = FacetModel::getFacet($facet, 'slug');
        pageError404($page);

        $page['post_content']   = Content::text($page['post_content'], 'text');
        $page['post_date_lang'] = lang_date($page['post_date']);

        $m = [
            'og'        => false,
            'twitter'   => false,
            'imgurl'    => false,
            'url'       => getUrlByName('page', ['facet' => $page['post_slug'], 'slug' => $facet['facet_slug']]),
        ];

        if ($page['post_is_deleted'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        $title = $page['post_title'] . ' - ' . Translate::get('page');
        $desc  = explode("\n", $page['post_content']);
        $desc  = strip_tags($desc[0]);
        if ($desc == '') {
            $desc = strip_tags($page['post_title']);
        }

        return Tpl::agRender(
            '/page/view',
            [
                'meta'  => meta($m, $title, $desc . ' (' . $facet['facet_title'] . ' - ' . Translate::get('page') . ')'),
                'data'  => [
                    'sheet' => 'page',
                    'type'  => 'page',
                    'page'  => $page,
                    'facet' => $facet,
                    'pages' => PageModel::recentPosts($facet['facet_id'], $page['post_id'])
                ]
            ]
        );
    }

    // Последние 5 страниц по id контенту
    public function redirectPage()
    {
        redirect('/info/' . Config::get('facets.info'));
    }

    // Последние 5 страниц по id контенту
    public function last($content_id)
    {
        return PageModel::recentPosts($content_id, null);
    }

    // Все страницы 
    public function lastAll()
    {
        return PageModel::recentPostsAll();
    }

    public function restriction()
    {
        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('info.restriction'),
        ];

        return Tpl::agRender(
            '/page/restriction',
            [
                'meta'  => meta($m, Translate::get('restriction'), Translate::get('the profile is being checked')),
                'data'  => [
                    'sheet' => 'restriction',
                    'type'  => 'page',
                ]
            ]
        );
    }
}
