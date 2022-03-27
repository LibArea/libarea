<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\{WebModel, ReplyModel};
use App\Models\PostModel;
use Content, Translate, UserData, Meta, Html;

class Detailed
{
    private $user;

    protected $limit = 25;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Detailed site page
    // Детальная страница сайта
    public function index($sheet)
    {
        $slug   = Request::get('slug');

        $item = WebModel::getItemOne($slug, $this->user['id']);
        Html::pageError404($item);

        if ($item['item_published'] == 0) {
            Html::pageError404([]);
        }

        if ($item['item_content_soft']) {
            $item['item_content_soft'] = Content::text($item['item_content_soft'], 'text');
        }

        Request::getResources()->addBottomScript('/assets/js/web.js');

        $content_img = PATH_THUMBS . 'default.png';
        if (file_exists(HLEB_PUBLIC_DIR . PATH_THUMBS . $item['item_domain'] . '.png')) {
            $content_img =  PATH_THUMBS . $item['item_domain'] . '.png';
        }

        $m = [
            'og'         => true,
            'imgurl'     => $content_img,
            'url'        => getUrlByName('web.website', ['slug' => $item['item_domain']]),
        ];
        $desc       = $item['item_title'] . '. ' . $item['item_content'];

        if ($item['item_post_related']) {
            $related_posts = PostModel::postRelated($item['item_post_related']);
        }

        $flat = ReplyModel::get($item['item_id'], $this->user);

        $tree = !empty($flat) ? self::buildTree($item['item_id'], $flat) : false;

        return view(
            '/view/default/website',
            [
                'meta'  => Meta::get($m, Translate::get('website') . ': ' . $item['item_title'], $desc),
                'user' => $this->user,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => 'web',
                    'item'          => $item,
                    'tree'          => $tree,
                    'similar'       => WebModel::itemSimilar($item['item_id'], 3),
                    'related_posts' => $related_posts ?? [],
                ]
            ]
        );
    }

    // https://stackoverflow.com/questions/4196157/create-array-tree-from-array-list/4196879#4196879
    public static function buildTree($group, array $flatList)
    {
        $grouped = [];
        foreach ($flatList as $node) {
            $grouped[$node['reply_parent_id']][] = $node;
        }

        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped) {
            foreach ($siblings as $k => $sibling) {
                $id = $sibling['reply_id'];
                if (isset($grouped[$id])) {
                    $sibling['children'] = $fnBuilder($grouped[$id]);
                }
                $siblings[$k] = $sibling;
            }
            return $siblings;
        };

        return $fnBuilder($grouped[$group]);
    }
}