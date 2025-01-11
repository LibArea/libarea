<?php

declare(strict_types=1);

namespace App\Controllers\Facet;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\FacetPresence;
use App\Models\User\UserModel;
use App\Models\{FacetModel, PostModel};
use UploadImage, Meta, Msg;

use App\Traits\Author;
use App\Traits\Related;

use App\Validate\RulesFacet;

class EditFacetController extends Controller
{
    use Author;
    use Related;

    /**
     * Topic or Blog editing form
     * Форма редактирования Topic or Blog
     *
     * @return void
     */
    public function index()
    {
        $type = Request::param('type')->asString();
        $facet  = FacetPresence::index(Request::param('id')->asInt(), 'id', $type);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->container->user()->id() && !$this->container->user()->admin()) {
            redirect('/');
        }

        render(
            '/facets/edit',
            [
                'meta'  => Meta::get(__('app.edit') . ' | ' . $facet['facet_title']),
                'data'  => [
                    'low_matching'      => FacetModel::getLowMatching($facet['facet_id']),
                    'high_matching'     => FacetModel::getHighMatching($facet['facet_id']),
                    'post_arr'          => PostModel::postRelated($facet['facet_post_related']),
                    'high_arr'          => FacetModel::getHighLevelList($facet['facet_id']),
                    'low_arr'           => FacetModel::getLowLevelList($facet['facet_id']),
                    'user'              => UserModel::get($facet['facet_user_id'], 'id'),
                    'sheet'             => $facet['facet_type'] . 's',
                    'type'              => $type,
                    'facet_inf'			=> $facet,
                ]
            ]
        );
    }

    public function edit()
    {
        $data = Request::allPost();

        // Получим массив данных существующего фасета и проверим его наличие
        $facet = FacetModel::uniqueById((int)$data['facet_id'] ?? 0);

        $new_type = RulesFacet::rulesEdit($data, $facet);

        UploadImage::set($_FILES, $facet['facet_id'], 'facet');
		
		
        $facet_user_id = $this->selectAuthor($facet['facet_user_id'], Request::post('user_id')->value());

        $post_related = $this->relatedPost();

        $facet_top_level = $data['facet_top_level'] ?? false;
        $facet_is_comments = $data['facet_is_comments'] ?? false;

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
                'facet_is_comments'			=> $facet_is_comments == 'on' ? 1 : 0,
            ]
        );

        self::setModification($data);

        Msg::redirect(__('msg.change_saved'), 'success', url('redirect.facet', ['id' => $data['facet_id']]));
    }
	
    /**
     * Avatar and cover upload form
     * Форма загрузки аватарки и обложики
     *
     * @return void
     */
    function logoForm()
    {
        $type = Request::param('type')->asString();
        $facet  = FacetPresence::index(Request::param('id')->asInt(), 'id', $type);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->container->user()->id() && !$this->container->user()->admin()) {
            redirect('/');
        }
		
        render(
            '/facets/edit-logo',
            [
                'meta'  => Meta::get(__('app.logo')),
                'data'  => [
                    'type'		=> $type,
                    'facet_inf'	=> $facet,
                ]
            ]
        );
    }

    function logoEdit()
    {
        $type = Request::param('type')->asString();
        $facet  = FacetPresence::index(Request::param('facet_id')->asInt(), 'id', $type);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->container->user()->id() && !$this->container->user()->admin()) {
            redirect('/');
        }

        UploadImage::set($_FILES, $facet['facet_id'], 'facet');
    }

    public static function setModification($data)
    {
        // Выбор детей в дереве
        $lows  = $data['low_facet_id'] ?? false;
        if ($lows) {
            $low_facet = json_decode($lows, true);
            $low_arr = $low_facet ?? [];

            FacetModel::addLowFacetRelation($low_arr, (int)$data['facet_id']);
        } else {
            FacetModel::deleteRelation((int)$data['facet_id'], 'topic');
        }

        // Связанные темы, дети 
        $matching = $data['facet_matching'] ?? false;
        if ($matching) {
            $match_facet    = json_decode($matching, true);
            $match_arr      = $match_facet ?? [];

            FacetModel::addLowFacetMatching($match_arr, (int)$data['facet_id']);
        } else {
            FacetModel::deleteRelation((int)$data['facet_id'], 'matching');
        }

        return true;
    }

    public function pages()
    {
        $facet  = FacetPresence::index(Request::get('id')->asInt(), 'id', 'blog');

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->container->user()->id() && !$this->container->user()->admin()) {
            redirect('/');
        }

        return render(
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
