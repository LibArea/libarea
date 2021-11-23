<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{FacetModel, PostModel};
use Base, Validation, UploadImage, Translate;

class EditFacetController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Форма редактирования Topic or Blog
    public function index()
    {
        $facet_id   = Request::getInt('id');
        $facet      = FacetModel::getFacet($facet_id, 'id');
        pageError404($facet);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->uid['user_id'] && $this->uid['user_trust_level'] != 5) {
            redirect('/');
        }

        $breadcrumb = breadcrumb(
            getUrlByName($facet['facet_type'] . 's'),
            Translate::get($facet['facet_type'] . 's'),
            getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]),
            $facet['facet_title'],
            Translate::get('edit') . ' | ' . $facet['facet_title'],
        );

        Request::getResources()->addBottomStyles('/assets/css/select2.css');
        Request::getHead()->addStyles('/assets/css/image-uploader.css');
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');
        Request::getResources()->addBottomScript('/assets/js/select2.min.js');

        return view(
            '/facets/edit',
            [
                'meta'  => meta($m = [], Translate::get('edit') . ' | ' . $facet['facet_title']),
                'uid'   => $this->uid,
                'data'  => [
                    'facet'             => $facet,
                    'breadcrumb'        => $breadcrumb,
                    'facet_related'     => FacetModel::facetRelated($facet['facet_related']),
                    'related_posts'     => PostModel::postRelated($facet['facet_post_related']),
                    'high_lists'        => FacetModel::getHighLevelList($facet['facet_id']),
                    'low_lists'         => FacetModel::getLowLevelList($facet['facet_id']),
                    'user'              => UserModel::getUser($facet['facet_user_id'], 'id'),
                    'sheet'             => 'topics',
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
        $facet_merged_id            = Request::getPostInt('facet_merged_id');
        $facet_top_level            = Request::getPostInt('facet_top_level');
        $facet_user_new             = Request::getPost('user_select');
        $facet_tl                   = Request::getPostInt('content_tl');
        $facet_is_web               = Request::getPostInt('facet_is_web');
        $facet_is_soft              = Request::getPostInt('facet_is_soft');
        $facet_type                 = Request::getPost('facet_type');

        //print_r(Request::getPost());
        //exit;
        $facet = FacetModel::getFacet($facet_id, 'id');
        pageError404($facet);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $this->uid['user_id'] && $this->uid['user_trust_level'] != 5) {
            redirect('/');
        }

        // Изменять тип темы может только персонал
        $facet_new_type = $facet['facet_type'];
        if ($this->uid['user_trust_level'] == 5) {
            $facet_new_type = $facet_type;
        }

        $redirect = getUrlByName('admin.topic.edit', ['id' => $facet['facet_id']]);
        $type = 'topic';
        if ($facet_new_type == 'blog') {
            $redirect   = getUrlByName('blog.edit', ['id' => $facet['facet_id']]);
            $type       = 'blog';
        }

        Validation::charset_slug($facet_slug, 'Slug (url)', $redirect);
        Validation::Limits($facet_title, Translate::get('title'), '3', '64', $redirect);
        Validation::Limits($facet_slug, Translate::get('slug'), '3', '43', $redirect);
        Validation::Limits($facet_seo_title, Translate::get('name SEO'), '4', '225', $redirect);
        Validation::Limits($facet_description, Translate::get('meta description'), '44', '225', $redirect);
        // Validation::Limits($facet_short_description, Translate::get('short description'), '11', '160', $redirect);
        Validation::Limits($facet_info, Translate::get('Info'), '14', '5000', $redirect);

        // Запишем img
        $img = $_FILES['images'];
        $check_img  = $_FILES['images']['name'][0];
        if ($check_img) {
            UploadImage::img($img, $facet['facet_id'], 'topic');
        }

        // Если есть смена topic_user_id и это TL5
        $facet_user_id = $facet['facet_user_id'];
        if ($facet['facet_user_id'] != $facet_user_new) {
            $facet_user_id = $facet['facet_user_id'];
            if ($this->uid['user_trust_level'] == 5) {
                $facet_user_id = $facet_user_new;
            }
        }

        // Проверим повтор URL                       
        if ($facet_slug != $facet['facet_slug']) {
            if (FacetModel::getFacet($facet_slug, 'slug')) {
                addMsg(Translate::get('url-already-exists'), 'error');
                redirect(getUrlByName($type . '.edit', ['id' => $facet['facet_id']]));
            }
        }

        $post_fields    = Request::getPost() ?? [];
        $facet_slug     = strtolower($facet_slug);
        $data = [
            'facet_id'                  => $facet_id,
            'facet_title'               => $facet_title,
            'facet_description'         => $facet_description,
            'facet_short_description'   => $facet_short_description ?? '',
            'facet_info'                => $facet_info,
            'facet_slug'                => $facet_slug,
            'facet_seo_title'           => $facet_seo_title,
            'facet_user_id'             => $facet_user_id,
            'facet_tl'                  => $facet_tl,
            'facet_top_level'           => $facet_top_level,
            'facet_post_related'        => implode(',', $post_fields['post_related'] ?? ['0']),
            'facet_related'             => implode(',', $post_fields['facet_related'] ?? ['0']),
            'facet_is_web'              => $facet_is_web,
            'facet_is_soft'             => $facet_is_soft,
            'facet_type'                => $type,
        ];

        FacetModel::edit($data);

        // Тема, выбор родителя   
        $facet_fields   = Request::getPost() ?? [];
        $facets         = $facet_fields['facet_parent_id'] ?? [];

        $arr = [];
        foreach ($facets as $row) {
            $arr[] = array($row, $facet_id);
        }

        FacetModel::addFacetRelation($arr, $facet_id);

        addMsg(Translate::get('changes saved'), 'success');

        redirect(getUrlByName($type, ['slug' => $facet_slug]));
    }

    // Выбор родительской темы
    public function selectTopicParent()
    {
        $facet_id = Request::getInt('topic_id');
        $search = Request::getPost('searchTerm');
        $search = preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $search);

        return FacetModel::getSearchParent($search, $facet_id);
    }
}
