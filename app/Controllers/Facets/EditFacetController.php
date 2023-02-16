<?php

namespace App\Controllers\Facets;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\FacetPresence;
use App\Models\User\UserModel;
use App\Models\{FacetModel, PostModel};
use UploadImage, Meta, UserData;

use App\Traits\Author;
use App\Traits\Related;

use App\Validate\RulesFacet;

class EditFacetController extends Controller
{
    use Author;
    use Related;

    // Форма редактирования Topic or Blog
    public function index($type)
    {
        $facet  = FacetPresence::index(Request::getInt('id'), 'id', $type);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->user['id'] && !UserData::checkAdmin()) {
            redirect('/');
        }

        return $this->render(
            '/facets/edit',
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

        // Получим массив данных существующего фасета и проверим его наличие
        $facet = FacetModel::uniqueById((int)$data['facet_id'] ?? 0);

        $new_type = RulesFacet::rulesEdit($data, $facet, $this->user['id']);

        UploadImage::set($_FILES, $facet['facet_id'], 'facet');

        $facet_user_id = $this->selectAuthor($facet['facet_user_id'], Request::getPost('user_id'));

        $post_related = $this->relatedPost();
        $facet_top_level = $data['facet_top_level'] ?? false;

        FacetModel::edit(
            [
                'facet_id'                  => $data['facet_id'],
                'facet_title'               => $data['facet_title'],
                'facet_description'         => $data['facet_description'],
                'facet_short_description'   => $data['facet_short_description'],
                'facet_info'                => $data['facet_info'],
                'facet_slug'                => strtolower($data['facet_slug']),
                'facet_seo_title'           => $data['facet_seo_title'],
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
        } else {
            FacetModel::deleteRelation($data['facet_id'], 'topic');
        }

        // Связанные темы, дети 
        $matching = $data['facet_matching'] ?? false;
        if ($matching) {
            $match_facet    = json_decode($matching, true);
            $match_arr      = $match_facet ?? [];

            FacetModel::addLowFacetMatching($match_arr, $data['facet_id']);
        } else {
            FacetModel::deleteRelation($data['facet_id'], 'matching');
        }

        return true;
    }

    public function pages()
    {
        $facet  = FacetPresence::index(Request::getInt('id'), 'id', 'blog');

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->user['id'] && !UserData::checkAdmin()) {
            redirect('/');
        }

        return $this->render(
            '/facets/edit-pages',
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
