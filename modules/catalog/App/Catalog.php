<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\{WebModel, FacetModel, UserAreaModel};
use Translate, UserData, Breadcrumbs, Meta, Html, Tpl;

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
            Html::pageError404([]);
        }

        $category  = FacetModel::get(\Request::get('slug'), 'slug', $this->user['trust_level']);
        Html::pageError404($category);

        // We will get children
        $childrens =  FacetModel::getChildrens($category['facet_id'], $screening);

        $items      = WebModel::feedItem($page, $this->limit, $childrens, $this->user, $category['facet_id'], $sheet, $screening);
        $pagesCount = WebModel::feedItemCount($childrens,  $category['facet_id'], $screening);

        $m = [
            'og'    => false,
            'url'   => getUrlByName('web.dir', ['cat' => 'cat', 'slug' => $category['facet_slug']]),
        ];

        $title = sprintf(Translate::get($sheet . '.cat.title'), $category['facet_title']);
        $desc  = sprintf(Translate::get($sheet . '.cat.desc'), $category['facet_title'], $category['facet_description']);

        $count_site = ($this->user['trust_level'] == UserData::REGISTERED_ADMIN) ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        $tree = FacetModel::breadcrumb($category['facet_id']);

        return view(
            '/view/default/sites',
            [
                'meta'  => Meta::get($m, $title, $desc),
                'user' => $this->user,
                'data'  => [
                    'screening'         => $screening,
                    'sheet'             => $sheet,
                    'type'              => $type,
                    'count'             => $pagesCount,
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $page,
                    'items'             => $items,
                    'category'          => $category,
                    'childrens'         => $childrens,
                    'user_count_site'   => $count_site,
                    'breadcrumb'        => self::breadcrumb($tree, $screening),
                    'low_matching'      => FacetModel::getLowMatching($category['facet_id']),
                ]
            ]
        );
    }

    // Bread crumbs
    public static function breadcrumb($tree, $screening)
    {
        $arr = [
          ['name' => Translate::get('catalog'), 'link' => getUrlByName('web')]
        ];
        
        $result = [];
        foreach ($tree as $row) {
            $result[] = [ "name" => $row['name'], "link" => getUrlByName('web.dir', ['cat' => $screening, 'slug' => $row['link']]) ]; 
        } 
  
        return array_merge($arr, $result);
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
        Html::pageError404($item);

        WebModel::setCleek($item['item_id']);

        return true;
    }
}
