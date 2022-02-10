<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\{WebModel, FacetModel};
use App\Models\PostModel;
use Content, Translate, UserData;


class Map
{
    private $user;

    protected $limit = 25;

    const MAX_LEVEL = 16;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet, $type)
    {
        $c = ['geo' => 1,'cat' => 1];
        
        $map = [];
        for ($i = 1; $i < self::MAX_LEVEL + 1; $i++) {
           if (\Request::get("slug-$i")) {
               $map[\Request::get("slug-$i")] = \Request::get("slug-$i");
           } else {
               break;
           }
        }
        $itog_map = [];
        $tmp = '';
        foreach ($map as $item){
            if (isset($c[$item])){
                $tmp = $item;
                continue;
            }
            if ($tmp !== ''){
                $itog_map[$tmp][] = $item;
            }
            
        }

        return $this->action($sheet, $type, $itog_map);
    }

    protected function action($sheet, $type, array $map)
    {
        pageError404($map);

        $cat = $map['cat'][1] ?? $map['cat'][0];
    
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $category   = FacetModel::get($cat, 'slug', $this->user['trust_level']); 
        $childrens  = FacetModel::getChildrens($category['facet_id']);

        $items      = WebModel::feedItem($page, $this->limit, $childrens, $this->user, $category['facet_id'], $sheet);
        $pagesCount = WebModel::feedItemCount($childrens,  $category['facet_id']);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('web.dir.top', ['slug' => '1111']),
        ];

        $title =  $category['facet_title'] . ': ' . mb_strtolower(Translate::get('websites'), 'UTF-8');
        $desc  = Translate::get('websites') . ', ' . $category['facet_title'] . '. ' . $category['facet_description'];
        if ($sheet == 'web.top') {
            $title = Translate::get('websites') . ' (top): ' . 'ct';
            $desc  = Translate::get('websites') . ' (top), ' . 'ct' . '. ' . 'cd';
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
                    'category'      => $category,
                    'childrens'     => $childrens,
                    'high_topics'   => FacetModel::getHighLevelList($category['facet_id']),
                 //  'low_topics'     => FacetModel::getLowLevelList($category['facet_id']),
                    'low_matching'  => FacetModel::getLowMatching($category['facet_id']),
                ]
            ]
        );
    }
}
