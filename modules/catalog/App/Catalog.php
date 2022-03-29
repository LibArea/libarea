<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\{WebModel, FacetModel, UserAreaModel};
use Translate, UserData, Breadcrumbs, Meta, Html;

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

        $parent = FacetModel::breadcrumb($category['facet_id']);

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
                    'breadcrumb'        => self::breadcrumb($parent, $category, $screening),
                    'low_matching'      => FacetModel::getLowMatching($category['facet_id']),
                ]
            ]
        );
    }

    // Bread crumbs
    public static function breadcrumb($parent, $category, $screening)
    {
        $breadcrumb = (new Breadcrumbs())->base(getUrlByName('web'), Translate::get('websites'));
        $facet_id = $parent['facet_id'] ?? 0;
        if ($parent_two = FacetModel::breadcrumb($facet_id)) {
            $breadcrumb->addCrumb($parent_two['facet_title'], $screening . DIRECTORY_SEPARATOR . $parent_two['facet_slug'])
                ->addCrumb($parent['facet_title'], $screening . DIRECTORY_SEPARATOR . $parent['facet_slug']);
        } elseif ($parent) {
            $breadcrumb->addCrumb($parent['facet_title'], $screening . DIRECTORY_SEPARATOR . $parent['facet_slug']);
        }

        $breadcrumb->addCrumb($category['facet_title'], $screening . DIRECTORY_SEPARATOR . $category['facet_slug']);

        return $breadcrumb->render('breadcrumbs');
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
