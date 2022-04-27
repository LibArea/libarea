<?php

namespace App\Controllers\Post;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{FacetModel, PostModel};
use Content, UploadImage, Validation, Tpl, Meta, Html, UserData;

class EditPostController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Форма редактирования post
    public function index()
    {
        $post_id    = Request::getInt('id');
        $post       = PostModel::getPost($post_id, 'id', $this->user);
        if (!Html::accessСheck($post, 'post', 0, 0)) {
            redirect('/');
        }

        if ($post['post_type'] == 'post') {
            Request::getResources()->addBottomScript('/assets/js/uploads.js');
        }

        Request::getResources()->addBottomStyles('/assets/js/editor/easymde.min.css');
        Request::getResources()->addBottomScript('/assets/js/editor/easymde.min.js');
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        $post_related = [];
        if ($post['post_related']) {
            $post_related = PostModel::postRelated($post['post_related']);
        }

        return Tpl::LaRender(
            '/post/edit',
            [
                'meta'  => Meta::get(__('edit.option', ['name' => __('post')])),
                'data'  => [
                    'sheet'         => 'edit-post',
                    'type'          => 'edit',
                    'post'          => $post,
                    'user'          => UserModel::getUser($post['post_user_id'], 'id'),
                    'blog'          => FacetModel::getFacetsUser($this->user['id'], 'blog'),
                    'post_arr'      => $post_related,
                    'topic_arr'     => PostModel::getPostFacet($post['post_id'], 'topic'),
                    'blog_arr'      => PostModel::getPostFacet($post['post_id'], 'blog'),
                    'section_arr'   => PostModel::getPostFacet($post['post_id'], 'section'),
                ]
            ]
        );
    }

    public function edit()
    {
        $post_id            = Request::getPostInt('post_id');
        $post_title         = Request::getPost('post_title');
        $content            = $_POST['content']; // для Markdown
        $post_feature       = Request::getPostInt('post_feature');
        $post_translation   = Request::getPostInt('translation');
        $post_draft         = Request::getPostInt('post_draft');
        $post_closed        = Request::getPostInt('closed');
        $post_top           = Request::getPostInt('top');
        $draft              = Request::getPost('draft');

        // Проверка доступа 
        $post   = PostModel::getPost($post_id, 'id', $this->user);
        if (!Html::accessСheck($post, 'post', 0, 0)) {
            redirect('/');
        }

        // Если пользователь заморожен
        (new \App\Controllers\AuditController())->stopContentQuietМode($this->user['limiting_mode']);

        // Связанные посты и темы
        $fields    = Request::getPost() ?? [];


        if ($post['post_type'] == 'post') {
            $json_post  = $fields['post_select'] ?? [];
            $arr_post   = json_decode($json_post, true);
            if ($arr_post) {
                foreach ($arr_post as $value) {
                    $id[]   = $value['id'];
                }
            }
            $post_related = implode(',', $id ?? []);
        }

        $redirect   = getUrlByName('content.edit', ['type' => $post['post_type'], 'id' => $post_id]);

        // If there is a change in post_user_id (owner) and who changes staff
        // Если есть смена post_user_id (владельца) и кто меняет персонал
        $post_user_id = $post['post_user_id'];
        if (UserData::checkAdmin()) {
            $user_new  = Request::getPost('user_id');
            if ($user_new) {
                $post_user_new = json_decode($user_new, true);
                if ($post['post_user_id'] != $post_user_new[0]['id']) {
                    $post_user_id = $post_user_new[0]['id'];
                }
            }
        }

        $post_title = str_replace("&nbsp;", '', $post_title);
        Validation::Length($post_title, 'title', '6', '250', $redirect);

        if ($content == '') {
            $content = $post['post_content'];
        }
        Validation::Length($content, 'the.post', '6', '25000', $redirect);

        // Проверим хакинг формы
        if ($post['post_draft'] == 0) {
            $draft = 0;
        }

        $post_date = $post['post_date'];
        if ($draft == 1 && $post_draft == 0) {
            $post_date = date("Y-m-d H:i:s");
        }

        // Обложка поста
        if (!empty($_FILES['images']['name'])) {
            $post_img = UploadImage::cover_post($_FILES['images'], $post, $redirect, $this->user['id']);
        }
        $post_img = $post_img ?? $post['post_content_img'];

        $new_type = self::addFacetsPost($fields, $post_id, $post['post_type'], $redirect);

        PostModel::editPost(
            [
                'post_id'               => $post_id,
                'post_title'            => $post_title,
                'post_slug'             => $post['post_slug'],
                'post_feature'          => $post_feature,
                'post_type'             => $new_type,
                'post_translation'      => $post_translation,
                'post_date'             => $post_date,
                'post_user_id'          => $post_user_id ?? 1,
                'post_draft'            => $post_draft,
                'post_content'          => Content::change($content),
                'post_content_img'      => $post_img ?? '',
                'post_related'          => $post_related ?? '',
                'post_tl'               => Request::getPostInt('content_tl'),
                'post_closed'           => $post_closed,
                'post_top'              => $post_top,
            ]
        );



        Validation::ComeBack('change.saved', 'success', '/post/' . $post_id);
    }

    // Add fastes (blogs, topics) to the post 
    public static function addFacetsPost($fields, $content_id, $redirect)
    {
        // topic
        $new_type = 'post';
        $facets = $fields['facet_select'] ?? false;
        if (!$facets) {
            Validation::ComeBack('select.topic', 'error', $redirect);
        }
        $topics = json_decode($facets, true);

        $section  = $fields['section_select'] ?? false;
        if ($section) {
            $new_type = 'page';
            $OneFacets = json_decode($section, true);
        }

        $blog_post  = $fields['blog_select'] ?? false;
        if ($blog_post) {
            $TwoFacets = json_decode($blog_post, true);
        }

        $GeneralFacets = array_merge($OneFacets ?? [], $TwoFacets ?? []);

        FacetModel::addPostFacets(array_merge($GeneralFacets ?? [], $topics), $content_id);

        return $new_type;
    }

    // Удаление обложки
    function imgPostRemove()
    {
        $post_id    = Request::getInt('id');
        $post = PostModel::getPost($post_id, 'id', $this->user);
        if (!Html::accessСheck($post, 'post', 0, 0)) {
            redirect('/');
        }

        PostModel::setPostImgRemove($post['post_id']);
        UploadImage::cover_post_remove($post['post_content_img'], $this->user['id']);

        Validation::ComeBack('cover.removed', 'success', getUrlByName('content.edit', ['type' => 'post', 'id' => $post['post_id']]));
    }

    public function uploadContentImage()
    {
        $user_id    = $this->user['id'];
        $type       = Request::get('type');
        $id         = Request::getInt('id');

        $allowed = ['post-telo', 'answer'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        $img = $_FILES['image'];
        if ($_FILES['image']['name']) {
            return json_encode(array('data' => array('filePath' => UploadImage::post_img($img, $user_id, $type, $id))));
        }

        return false;
    }
}
