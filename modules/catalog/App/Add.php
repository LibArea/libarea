<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\WebModel;
use App\Models\{SubscriptionModel, ActionModel, FacetModel, NotificationModel};
use Utopia\Domains\Domain;
use UserData, Meta, Validation, Access;

class Add
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Add Domain Form
    // Форма добавление домена
    public function index()
    {
        // Access rights by the trust level of the participant
        // Права доступа по уровню доверия участника
        if (Access::limitTl(config('trust-levels.tl_add_item')) == false) {
            redirect(url('web'));
        }

        // Plugin for selecting facets
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        return view(
            '/view/default/add',
            [
                'meta'  => Meta::get(__('web.add_website')),
                'user'  => $this->user,
                'data'  => [
                    'sheet'      => 'add',
                    'type'       => 'web',
                ]
            ]
        );
    }

    // Checks and directly adding 
    public function create()
    {
        $url = Request::getPost('url');
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return json_encode(['error' => 'error', 'text' => __('web.website_correctness')]);
        }

        // Access rights by the trust level of the participant
        // Права доступа по уровню доверия участника
        if (Access::limitTl(config('trust-levels.tl_add_item')) == false) {
            return json_encode(['error' => 'redirect', 'text' => __('msg.went_wrong')]);
        }

        // Check if the domain exists in the system  
        // Проверим наличие домена в системе
        if ($domain = self::getDomain(Request::getPost('url'))) {
            return json_encode(['error' => 'error', 'text' => __('web.site_replay')]);
        }

        // Get a first level domain       
        // Получим данные домена первого уровня
        $basic_host =  self::domain(Request::getPost('url'));

        // Check the length of the site name
        // Проверим длину названия сайта
        if (!Validation::length(Request::getPost('title'), 14, 250)) {
            $msg = __('web.string_length', ['name' => '«' . __('web.title') . '»']);
            return json_encode(['error' => 'error', 'text' => $msg]);
        }

        // Make the description optional for publication (it will still be rewritten) 
        // Сделать описание необязательным для публикации (оно все равно будет переписано) 
        $content = Request::getPost('content') ?? __('web.desc_formed');

        // Instant accommodation for staff only
        // Мгновенное размещение только для персонала
        $published = $this->user['trust_level'] == UserData::REGISTERED_ADMIN ? 1 : 0;

        $item_last = WebModel::add(
            [
                'item_url'              => $url,
                'item_domain'           => $basic_host,
                'item_title'            => Request::getPost('title'),
                'item_content'          => $content,
                'item_published'        => $published,
                'item_user_id'          => $this->user['id'],
                'item_close_replies'    => Request::getPostInt('close_replies'),
                'item_type_url'         => 0,
                'item_status_url'       => 200,
                'item_is_soft'          => 0,
                'item_is_github'        => 0,
                'item_votes'            => 0,
                'item_count'            => 1,
            ]
        );

        // Facets (categories are called here) for the site 
        // Фасеты (тут называются категории) для сайта
        $post_fields    = Request::getPost() ?? [];
        $facet_post     = $post_fields['facet_select'] ?? [];
        $topics         = json_decode($facet_post, true);

        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $ket => $row) {
                $arr[] = $row;
            }
            FacetModel::addItemFacets($arr, $item_last['item_id']);
        }

        ActionModel::addLogs(
            [
                'user_id'       => $this->user['id'],
                'user_login'    => $this->user['login'],
                'id_content'    => $item_last['item_id'],
                'action_type'   => 'website',
                'action_name'   => 'content_added',
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

        return true;
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
