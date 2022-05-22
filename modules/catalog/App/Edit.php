<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\WebModel;
use App\Models\{FacetModel, PostModel, NotificationModel};
use App\Models\User\UserModel;
use Validation, UserData, Meta, Access;

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
        if (Access::author('item', $domain['item_user_id'], $domain['item_date'], 0) === false) {
            redirect(url('web'));
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
                'meta'  => Meta::get(__('web.edit_website')),
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

    public function change()
    {
        $data = Request::getPost();
        $item = WebModel::getItemId($data['item_id']);
        if (!$item) {
            return true;
        }

        // Only the site author and staff can edit
        // Редактировать может только автор сайта и персонал
        if (Access::author('item', $item['item_user_id'], $item['item_date'], 0) === false) {
            return true;
        }

        // Check the length
        // Проверим длину
        if (!Validation::length($data['title'], 14, 250)) {
            return json_encode(['error' => 'error', 'text' => __('web.string_length', ['name' => '«' . __('web.title') . '»'])]);
        }

        if (!Validation::length($data['content'], 24, 1500)) {
            return json_encode(['error' => 'error', 'text' => __('web.string_length', ['name' => '«' . __('web.description') . '»'])]);
        }

        if (filter_var($data['url'], FILTER_VALIDATE_URL) === false) {
            return true;
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
        $published = UserData::checkAdmin() ? $data['published'] : 0;

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
                'item_url'              => $data['url'],
                'item_title'            => $data['title'],
                'item_content'          => $data['content'],
                'item_title_soft'       => $data['title_soft'] ?? '',
                'item_content_soft'     => $data['content_soft'] ?? '',
                'item_published'        => $published,
                'item_close_replies'    => (int)($data['close_replies'] ?? 0),
                'item_user_id'          => $owner_uid ?? 1,
                'item_type_url'         => 0,
                'item_status_url'       => $data['status'] ?? 404,
                'item_is_soft'          => (int)($data['soft'] ?? 0),
                'item_is_github'        => (int)($data['github'] ?? 0),
                'item_post_related'     => $post_related ?? '',
                'item_github_url'       => $data['item_github_url'] ?? '',
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
                    'url'          => url('web'),
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

        return true;
    }
}
