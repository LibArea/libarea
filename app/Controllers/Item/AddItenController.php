<?php

namespace App\Controllers\Item;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\Item\{WebModel, UserAreaModel};
use App\Models\{SubscriptionModel, ActionModel, FacetModel, NotificationModel};
use Utopia\Domains\Domain;
use UserData, Meta, Validation, Access;

class AddItemController extends Controller
{
    // Add Domain Form
    // Форма добавление домена
    public function index()
    {
        if (Access::trustLevels(config('trust-levels.tl_add_item')) == false) {
            redirect('/web');
        }

        $count_site = UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        return $this->render(
            '/item/add',
            'item',
            [
                'meta'  => Meta::get(__('web.add_website')),
                'user'  => $this->user,
                'data'  => [
                    'sheet'      => 'add',
                    'type'       => 'web',
                    'user_count_site'   => $count_site,
                ]
            ]
        );
    }

    // Checks and directly adding 
    public function create()
    {
        $url = Request::getPost('url');
        $redirect = url('content.add', ['type' => 'item']);

        Validation::url($url, $redirect);

        // Check if the domain exists in the system  
        // Проверим наличие домена в системе
        if ($domain = self::getDomain(Request::getPost('url'))) {
            is_return(__('web.site_replay'), 'error', $redirect);
        }

        // Get a first level domain       
        // Получим данные домена первого уровня
        $basic_host =  self::domain(Request::getPost('url'));

        // Check the length of the site name
        // Проверим длину названия сайта
        Validation::length(Request::getPost('title'), 14, 250, 'title', $redirect);

        // Make the description optional for publication (it will still be rewritten) 
        // Сделать описание необязательным для публикации (оно все равно будет переписано) 
        $content = Request::getPost('content') ?? __('web.desc_formed');

        // Instant accommodation for staff only
        // Мгновенное размещение только для персонала
        $published = Request::getPost('published') == 'on' ? 1 : 0;
        $published = UserData::checkAdmin() ? $published : 0;

        $item_last = WebModel::add(
            [
                'item_url'              => $url,
                'item_domain'           => $basic_host,
                'item_title'            => Request::getPost('title'),
                'item_content'          => $content,
                'item_published'        => $published,
                'item_user_id'          => $this->user['id'],
                'item_close_replies'    => Request::getPost('close_replies') == 'on' ? 1 : null,
            ]
        );

        // Facets (categories are called here) for the site 
        // Фасеты (тут называются категории) для сайта
        $post_fields    = Request::getPost() ?? [];
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

        // Notification to staff
        // Оповещение персоналу
        if ($this->user['trust_level'] != UserData::REGISTERED_ADMIN) {
            NotificationModel::send(
                [
                    'sender_id'    => $this->user['id'],
                    'recipient_id' => 1,  // admin
                    'action_type'  => NotificationModel::TYPE_ADD_WEBSITE,
                    'url'          => url('web.audits'),
                ]
            );
        }

        SubscriptionModel::focus($item_last['item_id'], $this->user['id'], 'item');

        is_return(__('web.site_added'), 'success', url('web'));
    }

    public static function getDomain($url)
    {
        $basic_host = self::domain($url);

        return WebModel::getItemOne($basic_host, 1);
    }

    public static function domain($url)
    {
        $parse  = parse_url($url);
        $domain = new Domain($parse['host']);

        return $domain->getRegisterable();
    }
}
