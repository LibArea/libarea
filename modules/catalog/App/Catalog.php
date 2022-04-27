<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\{WebModel, FacetModel, UserAreaModel};
use UserData, Meta, Html, Tpl;

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
        $pageNumber = Tpl::pageNumber();

        $os = ['all', 'github', 'wap'];
        if (!in_array($screening = Request::get('grouping'), $os)) {
            Html::pageError404([]);
        }

        $category  = FacetModel::get(Request::get('slug'), 'slug', $this->user['trust_level']);
        Html::pageError404($category);

        // We will get children
        $childrens =  FacetModel::getChildrens($category['facet_id'], $screening);

        $items      = WebModel::feedItem($pageNumber, $this->limit, $childrens, $this->user, $category['facet_id'], $sheet, $screening);
        $pagesCount = WebModel::feedItemCount($childrens,  $category['facet_id'], $screening);

        $m = [
            'og'    => false,
            'url'   => url('web.dir', ['grouping' => 'all', 'slug' => $category['facet_slug']]),
        ];

        $title = __($sheet . '.cat.title', ['name' => $category['facet_title']]);
        $description  = __($sheet . '.cat.desc', ['name' => $category['facet_title'], 'desc' => $category['facet_description']]);

        $count_site = UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        $tree = FacetModel::breadcrumb($category['facet_id']);

        return view(
            '/view/default/sites',
            [
                'meta'  => Meta::get($title, $description, $m),
                'data'  => [
                    'screening'         => $screening,
                    'sheet'             => $sheet,
                    'type'              => $type,
                    'count'             => $pagesCount,
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $pageNumber,
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
            ['name' => __('catalog'), 'link' => url('web')]
        ];

        $result = [];
        foreach ($tree as $row) {
            $result[] = ["name" => $row['name'], "link" => url('web.dir', ['grouping' => $screening, 'slug' => $row['link']])];
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
