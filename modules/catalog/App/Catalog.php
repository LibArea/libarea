<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use Modules\Catalog\App\Models\WebModel;
use App\Models\{FacetModel, PostModel};
use Content, Translate, Config;

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
        $slug   = Request::get('slug');
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $topic  = FacetModel::getFacet($slug, 'slug');
        pageError404($topic);

        // If the facet is not allowed in the site directory
        // Если фасет не разрешен в каталоге сайтов
        if ($topic['facet_is_web'] == 0) {
            pageError404([]);
        }

        // We will get children
        // Получим детей
        $topics =  FacetModel::getLowLevelList($topic['facet_id']);

        $items      = WebModel::feedItem($page, $this->limit, $topics, $this->user, $topic['facet_id'], $sheet);
        $pagesCount = WebModel::feedItemCount($topics,  $topic['facet_id']);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('web.dir.top', ['slug' => $topic['facet_slug']]),
        ];
        
        $title = Translate::get('websites') . ': ' . $topic['facet_title'] . " | " . Config::get('meta.name');
        $desc  = Translate::get('websites') . ', ' . $topic['facet_title'] . '. ' . $topic['facet_description'];
        if ($sheet == 'web.top') {
            $title = Translate::get('websites') . ' (top): ' . $topic['facet_title'] . " | " . Config::get('meta.name');
            $desc  = Translate::get('websites') . ' (top), ' . $topic['facet_title'] . '. ' . $topic['facet_description'];
        }

        return view(
            '/view/default/sites',
            [
                'meta'  => meta($m, $title, $desc),
                'user' => $this->user,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'count'         => $pagesCount,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'items'         => $items,
                    'topic'         => $topic,
                    'high_topics'   => FacetModel::getHighLevelList($topic['facet_id']),
                    'low_topics'    => FacetModel::getLowLevelList($topic['facet_id']),
                    'low_matching'  => FacetModel::getLowMatching($topic['facet_id']),
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
