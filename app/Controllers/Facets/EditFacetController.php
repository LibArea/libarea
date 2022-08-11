<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\UserModel;
use App\Models\{FacetModel, PostModel};
use Validation, UploadImage, Meta, UserData;

use App\Traits\Author;
use App\Traits\Related;

class EditFacetController extends Controller
{
    use Author;
    use Related;

    // Форма редактирования Topic or Blog
    public function index($type)
    {
        $facet_id   = Request::getInt('id');
        $facet      = FacetModel::getFacet($facet_id, 'id', $type);
        self::error404($facet);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->user['id'] && !UserData::checkAdmin()) {
            redirect('/');
        }

        return $this->render(
            '/facets/edit',
            'base',
            [
                'meta'  => Meta::get(__('app.edit') . ' | ' . $facet['facet_title']),
                'data'  => [
                    'facet'             => $facet,
                    'low_matching'      => FacetModel::getLowMatching($facet['facet_id']),
                    'high_matching'     => FacetModel::getHighMatching($facet['facet_id']),
                    'post_arr'          => PostModel::postRelated($facet['facet_post_related']),
                    'high_arr'          => FacetModel::getHighLevelList($facet['facet_id']),
                    'low_arr'           => FacetModel::getLowLevelList($facet['facet_id']),
                    'user'              => UserModel::getUser($facet['facet_user_id'], 'id'),
                    'sheet'             => $facet['facet_type'] . 's',
                    'type'              => $type,
                ]
            ]
        );
    }

    public function change()
    {
        $data = Request::getPost();

        // Хакинг формы (тип фасета)
        // ['topic', 'blog', 'category', 'section']
        if (!in_array($data['facet_type'], config('facets.permitted'))) {
            is_return(__('msg.went_wrong'), 'error');
        }

        // Получим массив данных существующего фасета и проверим его наличие
        $facet = FacetModel::uniqueById((int)$data['facet_id'] ?? 0);
        if ($facet == false) {
            is_return(__('msg.went_wrong'), 'error');
        }

        $redirect = url('content.edit', ['type' => $facet['facet_type'], 'id' => $facet['facet_id']]);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->user['id'] && !UserData::checkAdmin()) {
            is_return(__('msg.went_wrong'), 'error', $redirect);
        }

        // Изменять тип темы может только персонал
        $new_type = $facet['facet_type'];
        if ($data['facet_type'] != $facet['facet_type']) {
            if (UserData::checkAdmin()) $new_type = $data['facet_type'];
        }

        // Проверка длины
        Validation::Length($data['facet_title'], 3, 64, 'title', $redirect);
        Validation::Length($data['facet_description'], 34, 225, 'meta_description', $redirect);
        Validation::Length($data['facet_short_description'], 9, 160, 'short_description', $redirect);
        Validation::Length($data['facet_seo_title'], 4, 225, 'slug', $redirect);
        Validation::Length($data['facet_seo_title'], 0, 225, 'info', $redirect);

        // Slug
        Validation::Length($data['facet_slug'], 3, 43, 'slug', $redirect);

        if (!preg_match('/^[a-zA-Z0-9-]+$/u', $data['facet_slug'])) {
            is_return(__('msg.slug_correctness', ['name' => '«' . __('msg.slug') . '»']), 'error', $redirect);
        }

        if (preg_match('/\s/', $data['facet_slug']) || strpos($data['facet_slug'], ' ')) {
            is_return(__('msg.url_gaps'), 'error', $redirect);
        }

        UploadImage::set($_FILES, $facet['facet_id'], 'facet');

        $facet_user_id = $this->editAuthor($facet['facet_user_id'], Request::getPost('user_id'));

        // Проверим повтор URL                       
        if ($data['facet_slug'] != $facet['facet_slug']) {
            if (FacetModel::uniqueSlug($data['facet_slug'], $new_type)) {
                is_return(__('msg.repeat_url'), 'error', $redirect);
            }
        }

        $post_related = $this->relatedPost();
        $facet_slug = strtolower($data['facet_slug']);

        $facet_top_level = $data['facet_top_level'] ?? false;
        $facet_view_policy = $data['facet_view_policy'] ?? false;

        FacetModel::edit(
            [
                'facet_id'                  => $data['facet_id'],
                'facet_title'               => $data['facet_title'],
                'facet_description'         => $data['facet_description'],
                'facet_short_description'   => $data['facet_short_description'],
                'facet_info'                => $data['facet_info'],
                'facet_slug'                => $facet_slug,
                'facet_seo_title'           => $data['facet_seo_title'],
                'facet_view_policy'         => $facet_view_policy == 'on' ? 1 : 0,
                'facet_user_id'             => $facet_user_id,
                'facet_top_level'           => $facet_top_level == 'on' ? 1 : 0,
                'facet_post_related'        => $post_related,
                'facet_type'                => $new_type,
            ]
        );

        self::setModification($data);

        is_return(__('msg.change_saved'), 'success', url('redirect.facet', ['id' => $data['facet_id']]));
    }

    public static function setModification($data)
    {
        // Выбор детей в дереве
        $lows  = $data['low_facet_id'] ?? false;
        if ($lows) {
            $low_facet = json_decode($lows, true);
            $low_arr = $low_facet ?? [];

            FacetModel::addLowFacetRelation($low_arr, $data['facet_id']);
        }

        // Связанные темы, дети 
        $matching = $data['facet_matching'] ?? false;
        if ($matching) {
            $match_facet    = json_decode($matching, true);
            $match_arr      = $match_facet ?? [];

            FacetModel::addLowFacetMatching($match_arr, $data['facet_id']);
        }

        return true;
    }

    public function pages()
    {
        $facet_id   = Request::getInt('id');

        $facet  = FacetModel::getFacet($facet_id, 'id', 'blog');
        self::error404($facet);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->user['id'] && !UserData::checkAdmin()) {
            redirect('/');
        }

        return $this->render(
            '/facets/edit-pages',
            'base',
            [
                'meta'  => Meta::get(__('app.edit') . ' | ' . $facet['facet_title']),
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
