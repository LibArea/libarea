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
        $childrens =  FacetModel::getChildrens($category['facet_id'], $screening);

        $items      = WebModel::feedItem($page, $this->limit, $childrens, $this->user, $category['facet_id'], $sheet, $screening);
        $pagesCount = WebModel::feedItemCount($childrens,  $category['facet_id'], $screening);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('web.dir', ['cat' => 'cat', 'slug' => $category['facet_slug']]),
        ];

        $title = sprintf(Translate::get($sheet . '.cat.title'), $category['facet_title']);
        $desc  = sprintf(Translate::get($sheet . '.cat.desc'), $category['facet_title'], $category['facet_description']);

        $parent = FacetModel::breadcrumb($category['facet_id']);

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
                    'breadcrumb'    => self::breadcrumb($parent, $category, $screening),
                    // 'low_topics'    => FacetModel::getLowLevelList($category['facet_id']),
                    'low_matching'  => FacetModel::getLowMatching($category['facet_id']),
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
