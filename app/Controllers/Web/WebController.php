<?php

namespace App\Controllers\Web;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{WebModel, FeedModel, FacetModel};
use Content, Base, Translate;

class WebController extends MainController
{
    public function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = WebModel::getLinksAllCount();
        $links      = WebModel::getLinksAll($page, $limit, $uid['user_id']);

        $result = array();
        foreach ($links as $ind => $row) {
            $text = explode("\n", $row['link_content']);
            $row['link_content']    = Content::text($text[0], 'line');
            $result[$ind]           = $row;
        }

        $num = $page > 1 ? sprintf(Translate::get('page-number'), $page) : '';

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/assets/images/agouti-web.png',
            'url'        => getUrlByName('web'),
        ];

        return view(
            '/web/links',
            [
                'meta'  => meta($m, Translate::get('domains-title'), Translate::get('domains-desc')),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => 'domains',
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'links'         => $result,
                ]
            ]
        );
    }

    // Посты по домену
    public function posts($sheet)
    {
        $domain     = Request::get('domain');
        $uid        = Base::getUid();
        $page       = Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;

        $link       = WebModel::getLinkOne($domain, $uid['user_id']);
        Base::PageError404($link);

        $link['link_content'] = Content::text($link['link_content'], 'line');

        $limit      = 25;
        $data       = ['link_url_domain' => $link['link_url_domain']];
        $posts      = FeedModel::feed($page, $limit, $uid, $sheet, 'link', $data);
        $pagesCount = FeedModel::feedCount($uid, $sheet, 'link', $data);

        $result = array();
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

        return view(
            '/web/link',
            [
                'meta'  => meta($m, Translate::get('domain') . ': ' . $domain, Translate::get('domain-desc') . ': ' . $domain),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => 'domain',
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'posts'         => $result,
                    'domains'       => WebModel::getLinksTop($domain),
                    'link'          => $link
                ]
            ]
        );
    }

    // Лист сайтов по темам (сайты по "категориям")
    public function sites($sheet)
    {
        $slug       = Request::get('slug');
        $uid        = Base::getUid();
        $page       = Request::getInt('page');
        $page       = $page == 0 ? 1 : $page;

        $topic = FacetModel::getFacet($slug, 'slug');
        Base::PageError404($topic);

        // Получим подтемы темы
        $topics =  FacetModel::getLowLevelList($topic['facet_id']);

        $limit      = 25;  
        $links      = WebModel::feedLink($page, $limit, $topics, $uid, $topic['facet_id'], $sheet);
        $pagesCount = WebModel::feedLinkCount($topics,  $topic['facet_id']);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('web.topic', ['slug' => $topic['facet_slug']]),
        ];
        $desc  = Translate::get('sites') . ' ' . Translate::get('by') . ' ' . $topic['facet_title'] . '. ' . $topic['facet_description'];

        return view(
            '/web/sites',
            [
                'meta'  => meta($m, Translate::get('sites') . ': ' . $topic['facet_title'], $desc),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => $sheet,
                    'count'         => $pagesCount,
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'links'         => $links,
                    'topic'         => $topic,
                    'high_topics'   => FacetModel::getHighLevelList($topic['facet_id']),
                    'low_topics'    => FacetModel::getLowLevelList($topic['facet_id']),
                    'topic_related' => FacetModel::facetRelated($topic['facet_related']),
                ]
            ]
        );
    }
    
    // Детальная страница сайта
    public function website($sheet)
    {
        $slug       = Request::get('slug');
        $uid        = Base::getUid();

        $link = WebModel::getLinkOne($slug, $uid['user_id']);
        Base::PageError404($link);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('web.website', ['slug' => $link['link_url_domain']]),
        ];
        $desc       = $link['link_title'] . '. ' . $link['link_content'];
        $topics     = WebModel::getLinkTopic($link['link_id']);
        $high_leve  = $topics[0]['topic_id'] ?? 0;
 
        return view(
            '/web/website',
            [
                'meta'  => meta($m, Translate::get('sites') . ': ' . $link['link_title'], $desc),
                'uid'   => $uid,
                'data'  => [
                    'sheet'     => 'sites-topic',
                    'link'      => $link,
                    'topics'    => $topics,
                    'high_leve' => FacetModel::getHighLevelList($high_leve),
                ]
            ]
        );
    }
}
