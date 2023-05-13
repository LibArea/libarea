<?php

namespace App\Controllers\Item;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\ItemPresence;
use App\Models\Item\{WebModel, ReplyModel, UserAreaModel};
use App\Models\{PostModel, SubscriptionModel};
use Meta, UserData, Img;

use App\Traits\LastDataModified;
use App\Traits\Poll;

use App\Validate\RulesItem;

class DetailedController extends Controller
{
    use LastDataModified;
    use Poll;

    protected $limit = 25;

    // Detailed site page
    // Детальная страница сайта
    public function index()
    {
        $slug  = Request::get('slug');
        $id    = Request::getInt('id');
        
        $item = self::presence($id, $slug);

        if ($item['item_published'] == 0) {
            notEmptyOrView404([]);
        }

        $content_img = Img::PATH['thumbs'] . 'default.png';
        if (file_exists(HLEB_PUBLIC_DIR . Img::PATH['thumbs'] . $item['item_domain'] . '.png')) {
            $content_img =  Img::PATH['thumbs'] . $item['item_domain'] . '.png';
        }

        $m = [
            'og'         => true,
            'imgurl'     => $content_img,
            'url'        => url('website', ['id' => $item['item_id'], 'slug' => $item['item_slug']]),
        ];

        $title = __('web.website') . ': ' . $item['item_title'];
        $description  = $item['item_title'] . '. ' . $item['item_content'];

        if ($item['item_post_related']) {
            $related_posts = PostModel::postRelated($item['item_post_related']);
        }

        $flat = ReplyModel::get($item['item_id'], $this->user);
        $tree = !empty($flat) ? self::buildTree(0, $flat) : false;

        // Featured Content
        // Рекомендованный контент       
        $facets = WebModel::getItemTopic($item['item_id']);
        $similar = WebModel::itemSimilars($item['item_id'], $facets[0]['id'] ?? false, 3);

        $count_site = UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        // Отправка Last-Modified и обработка HTTP_IF_MODIFIED_SINCE
        $this->getDataModified($item['item_modified']);

        Request::getResources()->addBottomScript('/assets/js/share/goodshare.min.js');
        Request::getResources()->addBottomScript('/assets/js/dialog/dialog.js');

        return $this->render(
            '/item/website',
            [
                'meta'  => Meta::get($title, $description, $m),
                'data'  => [
                    'type'              => 'web',
                    'item'              => $item,
                    'tree'              => $tree,
                    'item_signed'       => SubscriptionModel::getFocus($item['item_id'], 'item'),
                    'similar'           => $similar ?? [],
                    'user_count_site'   => $count_site,
                    'related_posts'     => $related_posts ?? [],
                    'poll'              => $this->getPoll($item['item_poll']),
                    'subsections'       => RulesItem::getDomains($item['item_domain']),
                ]
            ],
            'item',
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
    
    public static function presence($id, $slug)
    {
        // Check id and get content data
        // Проверим id и получим данные контента
        $content = ItemPresence::index($id, 'id');

        // If the site slug is different from the data in the database
        // Если slug сайта отличается от данных в базе
        if ($slug != $content['item_slug']) {
            redirect(url('website', ['id' => $content['item_id'], 'slug' => $content['item_slug']]));
        }

        return $content;
    }
}
