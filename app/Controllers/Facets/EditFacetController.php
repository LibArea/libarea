<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{FacetModel, PostModel};
use Validation, UploadImage, Tpl, Meta, Html, UserData;

class EditFacetController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Форма редактирования Topic or Blog
    public function index($type)
    {
        $facet_id   = Request::getInt('id');
        $facet      = FacetModel::getFacet($facet_id, 'id', $type);
        Html::pageError404($facet);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->user['id'] && !UserData::checkAdmin()) {
            redirect('/');
        }

        Request::getResources()->addBottomScript('/assets/js/uploads.js');
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        return Tpl::agRender(
            '/facets/edit',
            [
                'meta'  => Meta::get(__('edit') . ' | ' . $facet['facet_title']),
                'data'  => [
                    'facet'             => $facet,
                    'low_matching'      => FacetModel::getLowMatching($facet['facet_id']),
                    'high_matching'     => FacetModel::getHighMatching($facet['facet_id']),
                    'post_arr'          => PostModel::postRelated($facet['facet_post_related']),
                    'high_arr'          => FacetModel::getHighLevelList($facet['facet_id']),
                    'low_arr'           => FacetModel::getLowLevelList($facet['facet_id']),
                    'user'              => UserModel::getUser($facet['facet_user_id'], 'id'),
                    'sheet'             => $facet['facet_type'] . 's',
                    'type'              => 'edit',
                ]
            ]
        );
    }

    public function edit()
    {
        $facet_id                   = Request::getPostInt('facet_id');
        $facet_title                = Request::getPost('facet_title');
        $facet_description          = Request::getPost('facet_description');
        $facet_short_description    = Request::getPost('facet_short_description');
        $facet_info                 = Request::getPost('facet_info');
        $facet_slug                 = Request::getPost('facet_slug');
        $facet_seo_title            = Request::getPost('facet_seo_title');
        $facet_top_level            = Request::getPostInt('facet_top_level');
        $facet_tl                   = Request::getPostInt('content_tl');
        $facet_type                 = Request::getPost('facet_type');

        $facet = FacetModel::uniqueById($facet_id);
        Html::pageError404($facet);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->user['id'] && !UserData::checkAdmin()) {
            redirect('/');
        }

        // Изменять тип темы может только персонал
        $new_type = $facet['facet_type'];
        if ($facet_type != $facet['facet_type']) {
            if (UserData::checkAdmin()) $new_type = $facet_type;
        }

        $redirect = getUrlByName('content.edit', ['type' => $new_type, 'id' => $facet['facet_id']]);

        Validation::Slug($facet_slug, 'Slug (url)', $redirect);
        Validation::Length($facet_title, 'title', '3', '64', $redirect);
        Validation::Length($facet_slug, 'slug', '3', '43', $redirect);
        Validation::Length($facet_seo_title, 'name SEO', '4', '225', $redirect);
        Validation::Length($facet_description, 'meta.description', '44', '225', $redirect);
        Validation::Length($facet_short_description, 'short.description', '11', '160', $redirect);
        Validation::Length($facet_info, 'info', '14', '5000', $redirect);

        // Запишем img
        $check_img  = $_FILES['images']['name'] ?? null;
        if ($check_img) {
            $img = $_FILES['images'];
            UploadImage::img($img, $facet['facet_id'], 'topic');
        }

        // Баннер
        $check_cover    = $_FILES['cover']['name'] ?? null;
        if ($check_cover) {
            $cover      = $_FILES['cover'];
            UploadImage::cover($cover, $facet['facet_id'], 'blog');
        }

        // Если есть смена post_user_id и это TL5
        $user_new  = Request::getPost('user_id');
        $facet_user_new = json_decode($user_new, true);
        $facet_user_id = $facet['facet_user_id'];
        if ($facet['facet_user_id'] != $facet_user_new[0]['id']) {
            if (UserData::checkAdmin()) {
                $facet_user_id = $facet_user_new[0]['id'];
            }
        }

        // Проверим повтор URL                       
        if ($facet_slug != $facet['facet_slug']) {
            if (FacetModel::uniqueSlug($facet_slug, $new_type)) {
                Validation::ComeBack('repeat.url', 'error', getUrlByName($new_type  . '.edit', ['id' => $facet['facet_id']]));
            }
        }

        $fields = Request::getPost() ?? [];

        // Связанные посты
        $arr_post  = $fields['post_select'] ?? [];
        if (!empty($arr_post)) {
            $arr_post   = json_decode($arr_post, true);
            foreach ($arr_post as $value) {
                $id[]   = $value['id'];
            }
        }
        $post_related = implode(',', $id ?? []);
        $facet_slug = strtolower($facet_slug);

        FacetModel::edit(
            [
                'facet_id'                  => $facet_id,
                'facet_title'               => $facet_title,
                'facet_description'         => $facet_description,
                'facet_short_description'   => $facet_short_description,
                'facet_info'                => $facet_info,
                'facet_slug'                => $facet_slug,
                'facet_seo_title'           => $facet_seo_title,
                'facet_user_id'             => $facet_user_id ?? 1,
                'facet_tl'                  => $facet_tl,
                'facet_top_level'           => $facet_top_level,
                'facet_post_related'        => $post_related,
                'facet_type'                => $new_type,
            ]
        );

        // Тема, выбор детей в дереве
        $highs  = $fields['high_facet_id'] ?? [];
        if ($highs) {
            $high_facet = json_decode($highs, true);
            $high_arr   = $high_facet ?? [];

            FacetModel::addLowFacetRelation($high_arr, $facet_id);
        }

        // Связанные темы, дети 
        $matching   = $fields['facet_matching'] ?? [];
        if ($matching) {
            $match_facet    = json_decode($matching, true);
            $match_arr      = $match_facet ?? [];

            FacetModel::addLowFacetMatching($match_arr, $facet_id);
        }

        Validation::ComeBack('change.saved', 'success', getUrlByName($new_type, ['slug' => $facet_slug]));
    }

    public function pages()
    {
        $facet_id   = Request::getInt('id');

        $facet      = FacetModel::getFacet($facet_id, 'id', 'blog');
        Html::pageError404($facet);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->user['id'] && !UserData::checkAdmin()) {
            redirect('/');
        }

        return Tpl::agRender(
            '/facets/edit-pages',
            [
                'meta'  => Meta::get(__('edit') . ' | ' . $facet['facet_title']),
                'data'  => [
                    'facet' => $facet,
                    'pages' => (new \App\Controllers\Post\PostController())->last($facet['facet_id']),
                    'sheet' => $facet['facet_type'] . 's',
                    'type'  => 'pages',
                ]
            ]
        );
    }
}
