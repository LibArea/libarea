<?php

namespace App\Controllers\Item;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{WebModel, FacetModel, PostModel};
use Validation, Translate;

class EditWebController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    // Форма редактирование домена
    public function index()
    {  
        Validation::validTl($this->uid['user_trust_level'], UserData::REGISTERED_ADMIN, 0, 1);

        $domain_id  = Request::getInt('id');
        $domain     = WebModel::getItemId($domain_id);

        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');
        Request::getResources()->addBottomScript('/assets/js/admin.js');

        $item_post_related = [];
        if ($domain['item_post_related']) {
            $item_post_related = PostModel::postRelated($domain['item_post_related']);
        }

        return agRender(
            '/item/edit',
            [
                'meta'  => meta($m = [], Translate::get('change the site') . ' | ' . $domain['item_url_domain']),
                'uid'   => $this->uid,
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
        Validation::validTl($this->uid['user_trust_level'], UserData::REGISTERED_ADMIN, 0, 1);

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

        $data = [
            'item_id'           => $item['item_id'],
            'item_url'          => $item_url,
            'item_title_url'    => $item_title_url,
            'item_content_url'  => $item_content_url,
            'item_title_soft'   => $item_title_soft ?? '',
            'item_content_soft' => $item_content_soft ?? '',
            'item_published'    => $item_published,
            'item_user_id'      => $this->uid['user_id'],
            'item_type_url'     => 0,
            'item_status_url'   => $item_status_url,
            'item_is_soft'      => $item_is_soft,
            'item_is_github'    => $item_is_github,
            'item_post_related' => $post_related,
            'item_github_url'   => $item_github_url ?? '',
        ];

        WebModel::edit($data);

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
