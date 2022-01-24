<?php

namespace App\Controllers\Item;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{WebModel, FeedModel, FacetModel, PostModel};
use Content, Translate, Tpl;

class WebController extends MainController
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = WebModel::getItemsAllCount($sheet);
        $items      = WebModel::getItemsAll($page, $this->limit, $this->user, $sheet);

        $result = [];
        foreach ($items as $ind => $row) {
            $text = explode("\n", $row['item_content_url']);
            $row['item_content_url']    = Content::text($text[0], 'line');
            $result[$ind]           = $row;
        }

        $num = $page > 1 ? sprintf(Translate::get('page-number'), $page) : '';

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/assets/images/agouti-web.png',
            'url'        => getUrlByName('web'),
        ];

        return Tpl::agRender(
            '/item/home',
            [
                'meta'  => meta($m, Translate::get('domains-title'), Translate::get('domains-desc')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'items'         => $result,
                    'type'          => $type,
                    'sheet'         => $sheet,
                ]
            ]
        );
    }

    // Посты по домену
    public function posts($sheet, $type)
    {
        $domain     = Request::get('domain');
        $page       = Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;

        $item       = WebModel::getItemOne($domain, $this->user['id']);
        pageError404($item);

        $item['item_content'] = Content::text($item['item_content_url'], 'line');

        $posts      = FeedModel::feed($page, $this->limit, $this->user, $sheet, $item['item_url_domain']);
        $pagesCount = FeedModel::feedCount($this->user, $sheet, $item['item_url_domain']);

        $result = [];
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('domain', ['domain' => $domain]),
        ];

        return Tpl::agRender(
            '/item/link',
            [
                'meta'  => meta($m, Translate::get('domain') . ': ' . $domain, Translate::get('domain-desc') . ': ' . $domain),
                'data'  => [
                    'sheet'         => 'domain',
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'posts'         => $result,
                    'domains'       => WebModel::getItemsTop($domain),
                    'item'          => $item,
                    'type'          => 'web',
                ]
            ]
        );
    }

    // Лист сайтов по темам (сайты по "категориям")
    public function sites($sheet)
    {
        $slug       = Request::get('slug');
        $page       = Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;

        $topic = FacetModel::getFacet($slug, 'slug');
        pageError404($topic);

        // Получим подтемы темы
        $topics =  FacetModel::getLowLevelList($topic['facet_id']);

        $items      = WebModel::feedItem($page, $this->limit, $topics, $this->user, $topic['facet_id'], $sheet);
        $pagesCount = WebModel::feedItemCount($topics,  $topic['facet_id']);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('web.topic', ['slug' => $topic['facet_slug']]),
        ];
        $desc  = Translate::get('websites') . ' ' . Translate::get('by') . ' ' . $topic['facet_title'] . '. ' . $topic['facet_description'];

        return Tpl::agRender(
            '/item/sites',
            [
                'meta'  => meta($m, Translate::get('websites') . ': ' . $topic['facet_title'], $desc),
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => 'web',
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

    // Детальная страница сайта
    public function website($sheet)
    {
        $slug       = Request::get('slug');

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
        $topics     = WebModel::getItemTopic($item['item_id']);
        $high_leve  = $topics[0]['value'] ?? 0;

        if ($item['item_post_related']) {
            $related_posts = PostModel::postRelated($item['item_post_related']);
        }

        return Tpl::agRender(
            '/item/website',
            [
                'meta'  => meta($m, Translate::get('website') . ': ' . $item['item_title_url'], $desc),
                'data'  => [
                    'sheet'         => 'sites-topic',
                    'type'          => 'web',
                    'item'          => $item,
                    'topics'        => $topics,
                    'similar'       => WebModel::itemSimilar($item['item_id'], 3),
                    'high_leve'     => FacetModel::getHighLevelList($high_leve),
                    'related_posts' => $related_posts ?? [],
                ]
            ]
        );
    }
}
