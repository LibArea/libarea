<?php

declare(strict_types=1);

namespace Modules\Catalog\Controllers;

use Hleb\Constructor\Data\View;
use Hleb\Static\Request;
use Hleb\Base\Module;
use Modules\Catalog\Сheck\ItemPresence;
use Modules\Catalog\Models\{WebModel, ReplyModel, UserAreaModel};
use App\Models\{PostModel, SubscriptionModel};
use BuildTree, Meta, Img;

use App\Traits\LastDataModified;
use App\Traits\Poll;

use Modules\Catalog\Validate\RulesItem;

class DetailedController extends Module
{
    use LastDataModified;
    use Poll;

    protected $limit = 25; /** @toDo не используется? */

    /**
     * Detailed site page
     * Детальная страница сайта
     */
    public function index(): View
    {
        $slug  = Request::param('slug')->value();
        $id    = Request::param('id')->asInt();

        $item = self::presence($id, $slug);

        if ($item['item_published'] == 0) {
            notEmptyOrView404([]);
        }

        $content_img = Img::PATH['thumbs'] . 'default.png';
        $host = host($item['item_url']);

        if (file_exists(HLEB_PUBLIC_DIR . Img::PATH['thumbs'] . $host . '.png')) {
            $content_img =  Img::PATH['thumbs'] . $host . '.png';
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

        $flat = ReplyModel::get($item['item_id'], $this->container->user()->get());
        $tree = !empty($flat) ? BuildTree::index(0, $flat ?? [], 'reply') : false;

        // Featured Content
        // Рекомендованный контент       
        $facets = WebModel::getItemTopic($item['item_id']);
        $similar = WebModel::itemSimilars($item['item_id'], $facets[0]['id'] ?? false, 3);

        $count_site = $this->container->user()->admin() ? 0 : UserAreaModel::getUserSitesCount();

        // Отправка Last-Modified и обработка HTTP_IF_MODIFIED_SINCE
        $this->getDataModified($item['item_modified']);

        return view(
            'website',
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
        );
    }

    public static function presence($id, $slug)
    {
        // Check id and get content data
        // Проверим id и получим данные контента
        $content = ItemPresence::index($id, 'id');

        if (!$content['item_slug']) redirect(url('web'));

        // If the site slug is different from the data in the database
        // Если slug сайта отличается от данных в базе
        if ($slug != $content['item_slug']) {
            redirect(url('website', ['id' => $content['item_id'], 'slug' => $content['item_slug']]));
        }

        return $content;
    }
}
