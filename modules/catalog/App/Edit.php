<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use Modules\Catalog\App\Models\WebModel;
use App\Models\{FacetModel, PostModel};
use Validation, Translate;

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
        Validation::validTl($this->user['trust_level'], UserData::REGISTERED_ADMIN, 0, 1);

        $domain_id  = Request::getInt('id');
        $domain     = WebModel::getItemId($domain_id);

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
                'meta'  => meta($m = [], sprintf(Translate::get('edit.option'), Translate::get('website'))),
                'user'  => $this->user,
                'data'  => [
                    'domain'    => $domain,
                    'sheet'     => 'domains',
                    'type'      => 'web',
                    'topic_arr' => WebModel::getItemTopic($domain['item_id']),
                    'post_arr'  => $item_post_related,
                ]
            ]
        );
    }

    public function edit()
    {
        Validation::validTl($this->user['trust_level'], UserData::REGISTERED_ADMIN, 0, 1);

        $redirect   = getUrlByName('web');
        $item_id    = Request::getPostInt('item_id');
        if (!$item  = WebModel::getItemId($item_id)) {
            redirect($redirect);
        }

        $item_url           = Request::getPost('item_url');
        $item_title_url     = Request::getPost('item_title_url');
        $item_content_url   = Request::getPost('item_content_url');
        $item_title_soft    = Request::getPost('item_title_soft');
        $item_content_soft  = Request::getPost('item_content_soft');
        $item_published     = Request::getPostInt('item_published');
        $item_status_url    = Request::getPostInt('item_status_url');
        $item_is_soft       = Request::getPostInt('item_is_soft');
        $item_is_github     = Request::getPostInt('item_is_github');
        $item_github_url    = Request::getPost('item_github_url');

        Validation::Length($item_title_url, Translate::get('title'), '14', '250', $redirect);
        Validation::Length($item_content_url, Translate::get('description'), '24', '1500', $redirect);

        // Связанные посты
        $post_fields    = Request::getPost() ?? [];
        $json_post  = $post_fields['post_select'] ?? [];
        $arr_post   = json_decode($json_post[0], true);
        if ($arr_post) {
            foreach ($arr_post as $value) {
                $id[]   = $value['id'];
            }
        }
        $post_related = implode(',', $id ?? []);

        WebModel::edit(
            [
                'item_id'           => $item['item_id'],
                'item_url'          => $item_url,
                'item_title_url'    => $item_title_url,
                'item_content_url'  => $item_content_url,
                'item_title_soft'   => $item_title_soft ?? '',
                'item_content_soft' => $item_content_soft ?? '',
                'item_published'    => $item_published,
                'item_user_id'      => $this->user['id'],
                'item_type_url'     => 0,
                'item_status_url'   => $item_status_url,
                'item_is_soft'      => $item_is_soft,
                'item_is_github'    => $item_is_github,
                'item_post_related' => $post_related ?? '',
                'item_github_url'   => $item_github_url ?? '',
            ]
        );


        // Фасеты для сайте
        $post_fields    = Request::getPost() ?? [];
        $facet_post     = $post_fields['facet_select'] ?? [];
        $topics         = json_decode($facet_post[0], true);

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
