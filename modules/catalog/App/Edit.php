<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\WebModel;
use App\Models\{FacetModel, PostModel, NotificationModel};
use Validation, Translate, UserData;

class Edit
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Форма редактирование домена
    public function index()
    {
        $domain_id  = Request::getInt('id');
        $domain     = WebModel::getItemId($domain_id);

        // Only the site author and staff can edit
        // Редактировать может только автор сайта и персонал
        if (!accessСheck($domain, 'item', $this->user, false, false) === true) {
            redirect(getUrlByName('web'));
        }

        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');
        Request::getResources()->addBottomScript('/assets/js/admin.js');

        $item_post_related = [];
        if ($domain['item_post_related']) {
            $item_post_related = PostModel::postRelated($domain['item_post_related']);
        }

        return view(
            '/view/default/edit',
            [
                'meta'  => meta([], Translate::get('site.edit')),
                'user'  => $this->user,
                'data'  => [
                    'domain'        => $domain,
                    'sheet'         => 'domains',
                    'type'          => 'web',
                    'category_arr'  => WebModel::getItemTopic($domain['item_id']),
                    'post_arr'      => $item_post_related,
                ]
            ]
        );
    }

    public function edit()
    {
        $redirect   = getUrlByName('web');
        $item_id    = Request::getPostInt('item_id');
        if (!$item  = WebModel::getItemId($item_id)) {
            redirect($redirect);
        }

        $item_url           = Request::getPost('url');
        $item_title_url     = Request::getPost('title');
        $item_content_url   = Request::getPost('content');
        $item_published     = Request::getPostInt('published');
        $item_status_url    = Request::getPostInt('status');
        // soft
        $item_is_soft       = Request::getPostInt('soft');
        $item_title_soft    = Request::getPost('title_soft');
        $item_content_soft  = Request::getPost('content_soft');
        $item_is_github     = Request::getPostInt('github');
        $item_github_url    = Request::getPost('github_url');

        Validation::Length($item_title_url, Translate::get('title'), '14', '250', $redirect);
        Validation::Length($item_content_url, Translate::get('description'), '24', '1500', $redirect);

        if (filter_var($item_url, FILTER_VALIDATE_URL) === FALSE) {
            redirect($redirect);
            // return json_encode(['error' => 'error', 'text' => Translate::get('url.site.correctness')]);
        }

        // Only the site author and staff can edit
        // Редактировать может только автор сайта и персонал
        if (!accessСheck($item, 'item', $this->user, false, false) === true) {
            redirect(getUrlByName('web'));
        }

        // Связанные посты
        $post_fields = Request::getPost() ?? [];
        $json_post  = $post_fields['post_select'] ?? [];
        $arr_post   = json_decode($json_post, true);
        if ($arr_post) {
            foreach ($arr_post as $value) {
                $id[]   = $value['id'];
            }
        }
        $post_related = implode(',', $id ?? []);

        // If not staff, then we make the site inactive 
        // Если не персонал, то делаем сайт не активным
        $published = $this->user['trust_level'] == UserData::REGISTERED_ADMIN ? $item_published : 0;

        // If the staff, then we save the author of the site 
        // Если персонал, то сохраняем автора сайта
        $uid = $this->user['trust_level'] == UserData::REGISTERED_ADMIN ? $item['item_user_id'] : $this->user['id'];

        WebModel::edit(
            [
                'item_id'           => $item['item_id'],
                'item_url'          => $item_url,
                'item_title_url'    => $item_title_url,
                'item_content_url'  => $item_content_url,
                'item_title_soft'   => $item_title_soft ?? '',
                'item_content_soft' => $item_content_soft ?? '',
                'item_published'    => $published,
                'item_user_id'      => $uid,
                'item_type_url'     => 0,
                'item_status_url'   => $item_status_url,
                'item_is_soft'      => $item_is_soft,
                'item_is_github'    => $item_is_github,
                'item_post_related' => $post_related ?? '',
                'item_github_url'   => $item_github_url ?? '',
            ]
        );

        // If the site was approved earlier, but was edited and changed the status, then:
        // Если сайт был утвержден ранее, но был отредактирован и поменял статус, то:
        if ($item_published == 1 || $published == 0) {
            // Notification to staff
            // Оповещение персоналу
            if ($this->user['trust_level'] != UserData::REGISTERED_ADMIN) {
                NotificationModel::send(
                    [
                        'sender_id'    => $this->user['id'],
                        'recipient_id' => 1,  // admin
                        'action_type'  => NotificationModel::TYPE_EDIT_WEBSITE,
                        'url'          => getUrlByName('web.audits'),
                    ]
                );
            }
        }

        // If the site has been approved:
        // Если сайт был утвержден:
        if ($item_published == 1 && $published == 1) {
            // Notification to the author of the site
            // Оповещение автору сайта
            if ($this->user['trust_level'] == UserData::REGISTERED_ADMIN) {
                NotificationModel::send(
                    [
                        'sender_id'    => $this->user['id'],
                        'recipient_id' => $uid,  // автор сайта
                        'action_type'  => NotificationModel::WEBSITE_APPROVED,
                        'url'          => getUrlByName('web'),
                    ]
                );
            }
        }

        // Фасеты для сайте
        $post_fields    = Request::getPost() ?? [];
        $facet_post     = $post_fields['facet_select'] ?? [];
        $topics         = json_decode($facet_post, true);

        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $ket => $row) {
                $arr[] = $row;
            }
            FacetModel::addItemFacets($arr, $item['item_id']);
        }

        redirect($redirect);
    }
}
