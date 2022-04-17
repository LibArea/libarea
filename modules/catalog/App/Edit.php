<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\WebModel;
use App\Models\{FacetModel, PostModel, NotificationModel};
use App\Models\User\UserModel;
use Validation, Translate, UserData, Meta, Html;

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
        if (!Html::accessСheck($domain, 'item', $this->user, false, false) === true) {
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
                'meta'  => Meta::get(Translate::get('site.edit')),
                'user'  => $this->user,
                'data'  => [
                    'domain'        => $domain,
                    'sheet'         => 'domains',
                    'type'          => 'web.edit',
                    'user'          => UserModel::getUser($domain['item_user_id'], 'id'),
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
        $item_title         = Request::getPost('title');
        $item_content       = Request::getPost('content');
        $item_published     = Request::getPostInt('published');
        $item_status_url    = Request::getPostInt('status');
        // soft
        $item_is_soft       = Request::getPostInt('soft');
        $item_title_soft    = Request::getPost('title_soft');
        $item_content_soft  = Request::getPost('content_soft');
        $item_is_github     = Request::getPostInt('github');
        $item_github_url    = Request::getPost('github_url');

        Validation::Length($item_title, Translate::get('title'), '14', '250', $redirect);
        Validation::Length($item_content, Translate::get('description'), '24', '1500', $redirect);

        if (filter_var($item_url, FILTER_VALIDATE_URL) === FALSE) {
            redirect($redirect);
            // return json_encode(['error' => 'error', 'text' => Translate::get('url.site.correctness')]);
        }

        // Only the site author and staff can edit
        // Редактировать может только автор сайта и персонал
        if (!Html::accessСheck($item, 'item', $this->user, false, false) === true) {
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
        $published = UserData::checkAdmin() ? $item_published : 0;

        // If the staff, then we save the author of the site 
        // Если персонал, то сохраняем автора сайта
        $owner_uid = UserData::checkAdmin() ? $item['item_user_id'] : $this->user['id'];

        // If there is a change in item_user_id (owner) and who changes staff
        // Если есть смена item_user_id (владельца) и кто меняет персонал
        if (UserData::checkAdmin()) {
            $user_new  = Request::getPost('user_id');
            $item_user_new = json_decode($user_new, true);
            if ($item['item_user_id'] != $item_user_new[0]['id']) {
                $owner_uid = $item_user_new[0]['id'];
            }    
        }

        WebModel::edit(
            [
                'item_id'               => $item['item_id'],
                'item_url'              => $item_url,
                'item_title'            => $item_title,
                'item_content'          => $item_content,
                'item_title_soft'       => $item_title_soft ?? '',
                'item_content_soft'     => $item_content_soft ?? '',
                'item_published'        => $published,
                'item_close_replies'    => Request::getPostInt('close_replies'),
                'item_user_id'          => $owner_uid ?? 1,
                'item_type_url'         => 0,
                'item_status_url'       => $item_status_url,
                'item_is_soft'          => $item_is_soft,
                'item_is_github'        => $item_is_github,
                'item_post_related'     => $post_related ?? '',
                'item_github_url'       => $item_github_url ?? '',
            ]
        );

        // If the site has changed status (for example, after editing)
        // Если сайт сменил статус (например, после редактирования)
        if ($item['item_published'] != $published) {
            NotificationModel::send(
                [
                    'sender_id'    => $this->user['id'],
                    'recipient_id' => UserData::checkAdmin() ? $owner_uid : 1,
                    'action_type'  => UserData::checkAdmin() ? NotificationModel::WEBSITE_APPROVED : NotificationModel::TYPE_EDIT_WEBSITE,
                    'url'          => getUrlByName('web'),
                ]
            );
        }

        // Фасеты для сайте
        $post_fields    = Request::getPost() ?? [];
        $facet_post     = $post_fields['facet_select'] ?? [];
        $topics         = json_decode($facet_post, true);

        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $row) {
                $arr[] = $row;
            }
            FacetModel::addItemFacets($arr, $item['item_id']);
        }

        redirect($redirect);
    }
}
