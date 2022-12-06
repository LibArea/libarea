<?php

namespace App\Controllers\Item;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\ItemPresence;
use App\Models\PostModel;
use App\Models\Item\{WebModel, UserAreaModel, FacetModel};
use UserData, Meta;

class DirController extends Controller
{
    protected $limit = 25;

    // List of sites by topic (sites by "category")
    // Лист сайтов по темам (сайты по "категориям")
    public function index()
    {
        self::filter($grouping = Request::get('grouping'), $sort = Request::get('sort'));

        $category  = FacetModel::get(Request::get('slug'), 'slug', $this->user['trust_level']);
        notEmptyOrView404($category);

        // We will get children
        $childrens =  FacetModel::getChildrens($category['facet_id'], $grouping);

        if ($category['facet_post_related']) {
            $related_posts = PostModel::postRelated($category['facet_post_related']);
        }

        $items      = WebModel::feedItem($this->pageNumber, $this->limit, $childrens, $this->user, $category['facet_id'], $sort, $grouping);
        $pagesCount = WebModel::feedItemCount($childrens,  $category['facet_id'],  $sort, $grouping);

        $m = [
            'og'    => false,
            'url'   => url('category', ['sort' => 'all', 'slug' => $category['facet_slug']]),
        ];

        $title = __('web.' . $sort . '_title', ['name' => $category['facet_title']]);
        $description_info = $category['facet_title'] . '. ' . $category['facet_description'];
        $description  = __('web.' . $sort . '_desc', ['description_info' => $description_info]);

        $count_site = UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        $tree = FacetModel::breadcrumb($category['facet_id']);

        return $this->render(
            '/item/sites',
            [
                'meta'  => Meta::get($title, $description, $m),
                'data'  => [
                    'sort'              => $sort,
                    'count'             => $pagesCount,
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $this->pageNumber,
                    'related_posts'     => $related_posts ?? false,
                    'items'             => $items,
                    'category'          => $category,
                    'childrens'         => $childrens,
                    'user_count_site'   => $count_site,
                    'breadcrumb'        => self::breadcrumb($tree, $sort),
                    'characteristics'   => WebModel::getTypesWidget($category['facet_id']),
                    'low_matching'      => FacetModel::getLowMatching($category['facet_id']),
                ]
            ], 'item',
        );
    }

    // Route::get('/web/{grouping}/dir/{sort}/{slug}')
    public static function filter($grouping, $sort)
    {
        if ($grouping) {
            $os = config('catalog/items.type');
            if (!in_array($grouping, $os)) {
                notEmptyOrView404([]);
            }
        }

        if ($sort) {
            $os = ['top', 'all'];
            if (!in_array($sort, $os)) {
                notEmptyOrView404([]);
            }
        }
    }

    // Bread crumbs
    public static function breadcrumb($tree, $sort)
    {
        $arr = [
            ['name' => __('web.catalog'), 'link' => url('web')]
        ];

        $result = [];
        foreach ($tree as $row) {
            $result[] = ["name" => $row['name'], "link" => url('category', ['sort' => $sort, 'slug' => $row['link']])];
        }

        return array_merge($arr, $result);
    }

    // Click-throughs
    // Переходы
    public function cleek()
    {
        $item   = ItemPresence::index(Request::getPostInt('id'));

        WebModel::setCleek($item['item_id']);

        return true;
    }
}
