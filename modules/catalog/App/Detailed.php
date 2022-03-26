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

        $answers = self::builderReply($item['item_id'], 0, ReplyModel::get($item['item_id'], $this->user));

        return view(
            '/view/default/website',
            [
                'meta'  => Meta::get($m, Translate::get('website') . ': ' . $item['item_title'], $desc),
                'user' => $this->user,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => 'web',
                    'item'          => $item,
                    'answers'       => $answers,
                    'similar'       => WebModel::itemSimilar($item['item_id'], 3),
                    'related_posts' => $related_posts ?? [],
                ]
            ]
        );
    }

    // Building a tree
    // Дерево
    public static function builderReply($chaid_id, $level, $data, array $tree = [])
    {
        $level++;
        foreach ($data as $part) {
            if ($part['reply_parent_id'] == $chaid_id) {
                $part['level']  = $level - 1;
                $tree[]         = $part;
                $tree           = self::builderReply($part['reply_id'], $level, $data, $tree);
            }
        }
        return $tree;
    }
}
