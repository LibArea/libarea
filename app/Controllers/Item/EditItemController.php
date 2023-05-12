<?php

namespace App\Controllers\Item;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\ItemPresence;
use App\Models\Item\WebModel;
use App\Models\{FacetModel, PostModel, NotificationModel, PollModel};
use App\Models\User\UserModel;
use UserData, Meta, Access;

use App\Traits\Poll;
use App\Traits\Slug;
use App\Traits\Author;
use App\Traits\Related;

use App\Validate\RulesItem;

class EditItemController extends Controller
{
    use Poll;
    use Slug;
    use Author;
    use Related;

    // Форма редактирование домена
    public function index()
    {
        $domain = ItemPresence::index(Request::getInt('id'));

        // Only the site author and staff can edit
        // Редактировать может только автор сайта и персонал
        if (Access::author('item', $domain) === false) {
            redirect(url('web'));
        }

        $item_post_related = [];
        if ($domain['item_post_related']) {
            $item_post_related = PostModel::postRelated($domain['item_post_related']);
        }

        if (UserData::checkAdmin()) {
            Request::getResources()->addBottomScript('/assets/js/catalog.js');
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
                    'poll'          => PollModel::getQuestion($domain['item_poll']),
                    'subsections'   => RulesItem::getDomains($domain['item_domain']),
                ]
            ],
            'item',
        );
    }

    public function change()
    {
        $data = Request::getPost();

        $item = RulesItem::rulesEdit($data);

        // Only the site author and staff can edit
        // Редактировать может только автор сайта и персонал
        if (Access::author('item', $item) === false) {
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

        if (UserData::checkAdmin()) {
            $item_slug = Request::getPost('item_slug');
            if ($item_slug != $item['item_slug']) {
                if (WebModel::getSlug($slug = $this->getSlug($item_slug))) {
                    $slug = $slug . "-";
                }
            }
        }

        WebModel::edit(
            [
                'item_id'               => $item['item_id'],
                'item_url'              => $data['url'],
                'item_title'            => $data['title'],
                'item_content'          => $data['content'],
                'item_slug'             => $slug ?? $item['item_slug'],
                'item_domain'           => RulesItem::getRegisterable($data['url']),
                'item_title_soft'       => $data['title_soft'] ?? '',
                'item_content_soft'     => $data['content_soft'] ?? '',
                'item_published'        => $published,
                'item_user_id'          => $new_user_id,
                'item_close_replies'    => self::toggle($data['close_replies'] ?? false),
                'item_is_forum'         => self::toggle($data['forum'] ?? false),
                'item_is_portal'        => self::toggle($data['portal'] ?? false),
                'item_is_blog'          => self::toggle($data['blog'] ?? false),
                'item_is_reference'     => self::toggle($data['reference'] ?? false),
                'item_is_goods'         => self::toggle($data['goods'] ?? false),
                'item_is_soft'          => self::toggle($data['soft'] ?? false),
                'item_is_github'        => self::toggle($data['github'] ?? false),
                'item_post_related'     => $post_related ?? null,
                'item_github_url'       => $data['github_url'] ?? null,
                'item_telephone'        => $data['telephone'] ?? null,
                'item_email'            => $data['email'] ?? null,
                'item_vk'               => $data['vk'] ?? null,
                'item_poll'             => $this->selectPoll(Request::getPost('poll_id')),
                'item_telegram'         => $data['telegram'] ?? null,
            ]
        );

        // If the site has changed status (for example, after editing)
        // Если сайт сменил статус (например, после редактирования)
        if ($item['item_published'] != $published) {

            $recipient_id = UserData::checkAdmin() ? $new_user_id : 1;
            $action_type = UserData::checkAdmin() ? NotificationModel::WEBSITE_APPROVED : NotificationModel::TYPE_EDIT_WEBSITE;

            NotificationModel::send($recipient_id, $action_type, url('web'));
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
