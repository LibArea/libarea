<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\WebModel;
use App\Models\{SubscriptionModel, ActionModel, FacetModel, NotificationModel};
use UserData, Meta;

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
        (new \Modules\Catalog\App\Checks())->limit();

        // Plugin for selecting facets
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        return view(
            '/view/default/add',
            [
                'meta'  => Meta::get(__('site.add')),
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
            return json_encode(['error' => 'error', 'text' => __('url.site.correctness')]);
        }

        // Access rights by the trust level of the participant
        // Права доступа по уровню доверия участника
        (new \Modules\Catalog\App\Checks())->limit();

        // Check if the domain exists in the system  
        // Проверим наличие домена в системе
        if ($domain = (new \Modules\Catalog\App\Checks())->getDomain(Request::getPost('url'))) {
            return json_encode(['error' => 'error', 'text' => __('site.replay')]);
        }

        // Get a first level domain       
        // Получим данные домена первого уровня
        $basic_host =  (new \Modules\Catalog\App\Checks())->domain(Request::getPost('url'));

        // Check the length of the site name
        // Проверим длину названия сайта
        if (!$title = (new \Modules\Catalog\App\Checks())->length(Request::getPost('title'), 14, 250)) {
            $msg = __('string.length', ['name' => '«' . __('title') . '»', 'min' => 14, 'max' => 250]);
            return json_encode(['error' => 'error', 'text' => $msg]);
        }

        // Make the description optional for publication (it will still be rewritten) 
        // Сделать описание необязательным для публикации (оно все равно будет переписано) 
        $content = Request::getPost('content') ?? __('desc.formed');

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
                'action_name'   => 'content.added',
                'url_content'   => getUrlByName('web.audits'),
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
                    'url'          => getUrlByName('web.audits'),
                ]
            );
        }

        SubscriptionModel::focus($item_last['item_id'], $this->user['id'], 'item');

        return true;
    }
}
