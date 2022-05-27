<?php

namespace App\Controllers\Item;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\Item\{WebModel, ReplyModel};
use App\Models\{PostModel, SubscriptionModel};
use Content, Meta;

class DetailedController extends Controller
{
    protected $limit = 25;

    // Detailed site page
    // Детальная страница сайта
    public function index()
    {
        $slug   = Request::get('slug');

        $item = WebModel::getItemOne($slug, $this->user['id']);
        self::error404($item);

        if ($item['item_published'] == 0) {
            self::error404([]);
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
            'url'        => url('website', ['slug' => $item['item_domain']]),
        ];
        $title = __('web.website') . ': ' . $item['item_title'];
        $description  = $item['item_title'] . '. ' . $item['item_content'];

        if ($item['item_post_related']) {
            $related_posts = PostModel::postRelated($item['item_post_related']);
        }

        $flat = ReplyModel::get($item['item_id'], $this->user);
        $tree = !empty($flat) ? self::buildTree(0, $flat) : false;

        return $this->render(
            '/item/website',
            'item',
            [
                'meta'  => Meta::get($title, $description, $m),
                'data'  => [
                    'type'          => 'web',
                    'item'          => $item,
                    'tree'          => $tree,
                    'item_signed'   => SubscriptionModel::getFocus($item['item_id'], $this->user['id'], 'item'),
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
