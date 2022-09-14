<?php

namespace App\Controllers\Item;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\Item\WebModel;
use App\Models\{FacetModel, PostModel, NotificationModel};
use App\Models\User\UserModel;
use UserData, Meta, Access;

use App\Traits\Author;
use App\Traits\Related;

use App\Validate\RulesItem;

class EditItemController extends Controller
{
    use Author;
    use Related;

    // Форма редактирование домена
    public function index()
    {
        $domain_id  = Request::getInt('id');
        $domain     = WebModel::getItemId($domain_id);
        self::error404($domain);

        // Only the site author and staff can edit
        // Редактировать может только автор сайта и персонал
        if (Access::author('item', $domain, 0) === false) {
            redirect(url('web'));
        }

        $item_post_related = [];
        if ($domain['item_post_related']) {
            $item_post_related = PostModel::postRelated($domain['item_post_related']);
        }

        if (UserData::checkAdmin()) {
            Request::getResources()->addBottomScript('/assets/js/admin.js');
        }

        return $this->render(
            '/item/edit',
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
            ], 'item',
        );
    }

    public function change()
    {
        $data = Request::getPost();

        $item = RulesItem::rulesEdit($data);

        // Only the site author and staff can edit
        // Редактировать может только автор сайта и персонал
        if (Access::author('item', $item, 0) === false) {
            return true;
        }

        // Связанные посты
        $json_post  = $data['post_select'] ?? [];
        $arr_post   = json_decode($json_post, true);
        if ($arr_post) {
            foreach ($arr_post as $value) {
                $id[]   = $value['id'];
            }
        }
        $post_related = implode(',', $id ?? []);

        // If not staff, then we make the site inactive 
        // Если не персонал, то делаем сайт не активным
        $published = $data['published'] ?? false;
        $published = $published == 'on' ? 1 : 0;
        $published = UserData::checkAdmin() ? $published : 0;

        $new_user_id = $this->selectAuthor($item['item_user_id'], Request::getPost('user_id'));

        WebModel::edit(
            [
                'item_id'               => $item['item_id'],
                'item_url'              => $data['url'],
                'item_title'            => $data['title'],
                'item_content'          => $data['content'],
                'item_title_soft'       => $data['title_soft'] ?? '',
                'item_content_soft'     => $data['content_soft'] ?? '',
                'item_published'        => $published,
                'item_user_id'          => $new_user_id,
                'item_close_replies'    => self::toggle($data['close_replies'] ?? false),
                'item_is_forum'         => self::toggle($data['forum'] ?? false),
                'item_is_portal'        => self::toggle($data['portal'] ?? false),
                'item_is_blog'          => self::toggle($data['blog'] ?? false),
                'item_is_reference'     => self::toggle($data['reference'] ?? false),
                'item_is_soft'          => self::toggle($data['soft'] ?? false),
                'item_is_github'        => self::toggle($data['github'] ?? false),
                'item_post_related'     => $post_related ?? null,
                'item_github_url'       => $data['github_url'] ?? null,
            ]
        );

        // If the site has changed status (for example, after editing)
        // Если сайт сменил статус (например, после редактирования)
        if ($item['item_published'] != $published) {
            NotificationModel::send(
                [
                    'sender_id'    => $this->user['id'],
                    'recipient_id' => UserData::checkAdmin() ? $new_user_id : 1,
                    'action_type'  => UserData::checkAdmin() ? NotificationModel::WEBSITE_APPROVED : NotificationModel::TYPE_EDIT_WEBSITE,
                    'url'          => url('web'),
                ]
            );
        }

        $facet_post = $data['facet_select'] ?? [];
        $topics     = json_decode($facet_post, true);
        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $row) {
                $arr[] = $row;
            }

            FacetModel::addItemFacets($arr, $item['item_id']);
        }

        is_return(__('msg.change_saved'), 'success', url('web'));
    }

    public static function toggle($value)
    {
        return $value == 'on' ? 1 : null;
    }
}
