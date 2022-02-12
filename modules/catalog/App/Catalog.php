<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\{WebModel, FacetModel};
use App\Models\PostModel;
use Content, Translate, UserData, Breadcrumbs;

class Catalog
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // List of sites by topic (sites by "category")
    // Лист сайтов по темам (сайты по "категориям")
    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $os = ['cat', 'github', 'wap'];
        if (!in_array($screening = \Request::get('cat'), $os)) {
           pageError404([]);
        }

        $category  = FacetModel::get(\Request::get('slug'), 'slug', $this->user['trust_level']);
        pageError404($category);

        // We will get children
        // Получим детей
        $childrens =  FacetModel::getChildrens($category['facet_id'], $screening);
        
        $items      = WebModel::feedItem($page, $this->limit, $childrens, $this->user, $category['facet_id'], $sheet, $screening );
        $pagesCount = WebModel::feedItemCount($childrens,  $category['facet_id'], $screening);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('web.dir.top', ['slug' => $category['facet_slug']]),
        ];

        $title = $category['facet_title']. ' - ' . mb_strtolower(Translate::get('websites'), 'UTF-8');
        $desc  = Translate::get('websites') . ', ' . $category['facet_title'] . '. ' . $category['facet_description'];
        if ($sheet == 'web.top') {
            $title = $category['facet_title']. ' - ' . mb_strtolower(Translate::get('websites') . ' (top)', 'UTF-8');
            $desc  = Translate::get('websites') . ' (top), ' . $category['facet_title'] . '. ' . $category['facet_description'];
        }

        // TODO: https://dev.mysql.com/doc/refman/8.0/en/with.html
        // Now we will do this to bring styles and templates to a single view (we need an example)
        $parent = FacetModel::breadcrumb($category['facet_id']);
        if ($parent_two = FacetModel::breadcrumb($parent['facet_id'])) {
             $breadcrumb = (new Breadcrumbs('<span>/</span>'))
                ->base(getUrlByName('web'), Translate::get('websites'))
                ->addCrumb($parent_two['facet_title'], $screening . '/'. $parent_two['facet_slug'])
                ->addCrumb($parent['facet_title'], $screening . '/'. $parent['facet_slug']) 
                ->addCrumb($category['facet_title'], $screening . '/'. $category['facet_slug']);
        } else {
            if ($parent) {
                $breadcrumb = (new Breadcrumbs('<span>/</span>'))
                    ->base(getUrlByName('web'), Translate::get('websites'))
                    ->addCrumb($parent['facet_title'], $screening . '/'. $parent['facet_slug']) 
                    ->addCrumb($category['facet_title'], $screening . '/'. $category['facet_slug']);
            } else {
                $breadcrumb = (new Breadcrumbs('<span>/</span>'))
                    ->base(getUrlByName('web'), Translate::get('websites'))
                    ->addCrumb($category['facet_title'], $screening . '/'. $category['facet_slug']); 
            }
        }
        
        return view(
            '/view/default/sites',
            [
                'meta'  => meta($m, $title, $desc),
                'user' => $this->user,
                'data'  => [
                    'screening'     => $screening,
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'count'         => $pagesCount,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'items'         => $items,
                    'category'      => $category,
                    'childrens'     => $childrens,
                    'breadcrumb'    => $breadcrumb->render('bread_crumbs'),
                 // 'low_topics'    => FacetModel::getLowLevelList($category['facet_id']),
                    'low_matching'  => FacetModel::getLowMatching($category['facet_id']),
                ]
            ]
        );
    }

    // Detailed site page
    // Детальная страница сайта
    public function website($sheet)
    {
        $slug   = Request::get('slug');

        $item = WebModel::getItemOne($slug, $this->user['id']);
        pageError404($item);

        if ($item['item_published'] == 0) {
            pageError404([]);
        }

        if ($item['item_content_soft']) {
            $item['item_content_soft'] = Content::text($item['item_content_soft'], 'text');
        }

        $content_img = AG_PATH_THUMBS . 'default.png';
        if (file_exists(HLEB_PUBLIC_DIR . AG_PATH_THUMBS . $item['item_url_domain'] . '.png')) {
            $content_img =  AG_PATH_THUMBS . $item['item_url_domain'] . '.png';
        }

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => $content_img,
            'url'        => getUrlByName('web.website', ['slug' => $item['item_url_domain']]),
        ];
        $desc       = $item['item_title_url'] . '. ' . $item['item_content_url'];

        if ($item['item_post_related']) {
            $related_posts = PostModel::postRelated($item['item_post_related']);
        }

        return view(
            '/view/default/website',
            [
                'meta'  => meta($m, Translate::get('website') . ': ' . $item['item_title_url'], $desc),
                'user' => $this->user,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => 'web',
                    'item'          => $item,
                    'similar'       => WebModel::itemSimilar($item['item_id'], 3),
                    'related_posts' => $related_posts ?? [],
                ]
            ]
        );
    }

    // Bookmarks by sites
    // Закладки по сайтам
    public function bookmarks($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $items      = WebModel::bookmarks($page, $this->limit, $this->user['id']);
        $pagesCount = WebModel::bookmarksCount($this->user['id']);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('web.bookmarks'),
        ];
        $desc  = Translate::get('favorites');

        return view(
            '/view/default/bookmarks',
            [
                'meta'  => meta($m, Translate::get('favorites'), Translate::get('favorites')),
                'user' => $this->user,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'count'         => $pagesCount,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'items'         => $items,
                ]
            ]
        );
    }



    public function getItemId($id)
    {
        return  WebModel::getItemId($id);
    }

    // Click-throughs
    // Переходы
    public function cleek()
    {
        $id     = Request::getPostInt('id');
        $item   = WebModel::getItemId($id);
        pageError404($item);

        WebModel::setCleek($item['item_id']);

        return true;
    }
}
