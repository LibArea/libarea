<?php

declare(strict_types=1);

namespace Modules\Catalog\Controllers;

use Hleb\Constructor\Data\View;
use Hleb\Static\Request;
use Hleb\Base\Module;
use Modules\Catalog\Сheck\ItemPresence;
use Modules\Catalog\Models\{WebModel, UserAreaModel, FacetModel};
use App\Models\PostModel;
use Html, Meta;

use App\Traits\Breadcrumb;

class DirController extends Module
{
    use Breadcrumb;

    protected const LIMIT = 25;

    /**
     * List of sites by topic (sites by "category")
     * Лист сайтов по темам (сайты по "категориям")
     */
    public function index(): View
    {
        $grouping = Request::param('grouping')->value();
		
        $sort = Request::param('sort')->value();

        self::filter($grouping, $sort);

        $category  = FacetModel::get(Request::param('slug')->value(), 'slug', $this->container->user()->tl());
        notEmptyOrView404($category);

        // We will get children
        $childrens =  FacetModel::getChildrens($category['facet_id'], $grouping ?? false);

        if ($category['facet_post_related']) {
            $related_posts = PostModel::postRelated($category['facet_post_related']);
        }
 
        $items      = WebModel::feedItem($childrens, $category['facet_id'], Html::pageNumber(), $sort, $grouping);
        $pagesCount = WebModel::feedItemCount($childrens,  $category['facet_id'],  $sort, $grouping);

        $m = [
            'og'    => false,
            'url'   => url('category', ['sort' => 'all', 'slug' => $category['facet_slug']]),
        ];

        $title = __('web.' . $sort . '_title', ['name' => $category['facet_title']]);
        $description_info = $category['facet_title'] . '. ' . $category['facet_description'];
        $description  = __('web.' . $sort . '_desc', ['description_info' => $description_info]);

        $count_site = $this->container->user()->admin() ? 0 : UserAreaModel::getUserSitesCount();

        return view(
            'category',
            [
                'meta'  => Meta::get($title, $description, $m),
                'data'  => [
                    'sort'              => $sort,
                    'count'             => $pagesCount,
                    'pagesCount'        => ceil($pagesCount / self::LIMIT),
                    'pNum'              => Html::pageNumber(),
                    'related_posts'     => $related_posts ?? false,
                    'items'             => $items,
                    'category'          => $category,
                    'childrens'         => $childrens,
                    'user_count_site'   => $count_site,
                    'breadcrumb'        => $this->getBreadcrumb($category['facet_id'], $sort),
                    'characteristics'   => WebModel::getTypesWidget($category['facet_id']),
                    'low_matching'      => FacetModel::getLowMatching($category['facet_id']),
                ]
            ]
        );
    }

    /**
     * Route::get('/web/{grouping}/dir/{sort}/{slug}')
     *
     * @param null|string $grouping
     * @param string $sort
     * @return void
     */
    public static function filter(null|string $grouping, string $sort)
    {
        if ($grouping) {
            $os = config('main', 'type');
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

    /**
     * Click-throughs
     * Переходы
     *
     * @return true
     */
    public function cleek() :true
    {
        $item   = ItemPresence::index(Request::post('id')->asInt());

        WebModel::setCleek($item['item_id']);

        return true;
    }
}
