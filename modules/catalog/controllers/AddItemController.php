<?php

declare(strict_types=1);

namespace Modules\Catalog\Controllers;

use Hleb\Constructor\Data\View;
use Hleb\Static\Request;
use Hleb\Base\Module;
use App\Content\Сheck\FacetPresence;
use Modules\Catalog\Models\{WebModel, UserAreaModel};
use App\Models\{SubscriptionModel, ActionModel, FacetModel, NotificationModel};
use Meta, Msg;

use Modules\Catalog\Validate\RulesItem;
use App\Traits\Slug;
use App\Traits\Poll;

class AddItemController extends Module
{
    use Slug;
    use Poll;

    /**
     * Add Domain Form
     * Форма добавление домена
     */
    public function index(): View
    {
        if (!$this->container->access()->limitTl(config('trust-levels', 'tl_add_item'))) {
            redirect('/web');
        }

        // Adding from page topic / blog
        // Добавление со странице категории
        if ($category_id = Request::param('id')->asInt()) {
            $category  = FacetPresence::index($category_id, 'id', 'category');
        }

        $count_site = $this->container->user()->admin() ? 0 : UserAreaModel::getUserSitesCount();

        return view(
            '/item/add',
            [
                'meta'  => Meta::get(__('web.add_website')),
                'user'  => $this->container->user()->get(),
                'data'  => [
                    'sheet'             => 'add',
                    'type'              => 'web',
                    'category'          => $category ?? false,
                    'user_count_site'   => $count_site,
                ]
            ],
        );
    }

    /**
     * Checks and directly adding 
     *
     * @return void
     */
    public function add()
    {
        $data = Request::allPost();

        $basic_host = RulesItem::rulesAdd($data);

        // Instant accommodation for staff only
        // Мгновенное размещение только для персонала
        $published = Request::post('published')->value() === 'on' ? 1 : 0;
        $published = $this->container->user()->admin() ? $published : 0;

        if (WebModel::getSlug($slug = $this->getSlug($data['title']))) {
            $slug = $slug . "-";
        }

        $item_last = WebModel::add(
            [
                'item_url'              => $data['url'],
                'item_domain'           => $basic_host,
                'item_title'            => $data['title'],
                'item_content'          => $data['content'] ?? __('web.desc_formed'),
                'item_slug'             => $slug,
                'item_published'        => $published,
                'item_user_id'          => $this->container->user()->id(),
                'item_close_replies'    => Request::post('close_replies')->value() === 'on' ? 1 : null,
                'item_poll'             => $this->selectPoll(Request::post('poll_id')->value() ?? ''),
            ]
        );

        // Facets (categories are called here) for the site 
        // Фасеты (тут называются категории) для сайта
        $post_fields    = Request::allPost() ?? [];
        $facet_post     = $post_fields['facet_select'] ?? [];
        $topics         = json_decode($facet_post, true);

        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $row) {
                $arr[] = $row;
            }
            FacetModel::addItemFacets($arr, $item_last['item_id']);
        }

        ActionModel::addLogs(
            [
                'id_content'    => $item_last['item_id'],
                'action_type'   => 'item',
                'action_name'   => 'added',
                'url_content'   => url('web.audits'),
            ]
        );

        $this->notif($this->container->user()->tl());

        SubscriptionModel::focus($item_last['item_id'], 'item');

        Msg::redirect(__('web.site_added'), 'success', url('web'));
    }

    /**
     * Notification to staff
     * Оповещение персоналу
     *
     * @param int $trust_level
     */
    public function notif(int $trust_level): true
    {
        if ($trust_level != 10) {
            NotificationModel::send(1,  NotificationModel::TYPE_ADD_WEBSITE, url('web.audits'));
        }

        return true;
    }

    public function searchUrl()
    {
        $host = host(Request::post('url')->value());
        $result = WebModel::getHost($host);

        return json_encode($result, JSON_PRETTY_PRINT);
    }
}
