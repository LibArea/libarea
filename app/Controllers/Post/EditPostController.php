<?php

namespace App\Controllers\Post;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{FacetModel, PostModel};
use Content, UploadImage, Validation, Translate, Tpl, UserData;

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
        if (!accessСheck($post, 'post', $this->user, 0, 0)) {
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

        $puth = '/post/edit';
        if ($post['post_type'] == 'page') $puth = '/page/edit';

        return Tpl::agRender(
            $puth,
            [
                'meta'  => meta([], sprintf(Translate::get('edit.option'), Translate::get('post'))),
                'data'  => [
                    'sheet'         => 'edit-post',
                    'type'          => 'edit',
                    'post'          => $post,
                    'user'          => UserModel::getUser($post['post_user_id'], 'id'),
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
        if (!accessСheck($post, 'post', $this->user, 0, 0)) {
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
            $redirect   = getUrlByName('content.edit', ['type' => 'post', 'id' => $post_id]);
        } else {
            $redirect   = getUrlByName('content.edit', ['type' => 'page', 'id' => $post_id]);
        }

        // Если есть смена post_user_id и это TL5
        $user_new  = Request::getPost('user_id');
        $post_user_new = json_decode($user_new, true);
        $post_user_id = $post['post_user_id'];
        if ($post['post_user_id'] != $post_user_new[0]['id']) {
            if (UserData::checkAdmin()) {
                $post_user_id = $post_user_new[0]['id'];
            }
        }

        Validation::Length($post_title, Translate::get('title'), '6', '250', $redirect);

        if ($content == '') {
            $content = $post['post_content'];
        }
        Validation::Length($content, Translate::get('the post'), '6', '25000', $redirect);

        // Проверим хакинг формы
        if ($post['post_draft'] == 0) {
            $draft = 0;
        }

        $post_date = $post['post_date'];
        if ($draft == 1 && $post_draft == 0) {
            $post_date = date("Y-m-d H:i:s");
        }

        // Обложка поста
        $cover = $_FILES['images'] ?? false;
        if (!empty($_FILES['images']['name'])) {
            $post_img = UploadImage::cover_post($cover, $post, $redirect, $this->user['id']);
        }
        $post_img = $post_img ?? $post['post_content_img'];

        PostModel::editPost(
            [
                'post_id'               => $post_id,
                'post_title'            => $post_title,
                'post_slug'             => $post['post_slug'],
                'post_feature'          => $post_feature,
                'post_type'             => $post['post_type'],
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

        self::addFacetsPost($fields, $post_id, $redirect);

        redirect('/post/' . $post_id);
    }

    // Add fastes (blogs, topics) to the post 
    public static function addFacetsPost($fields, $content_id, $redirect)
    {
        $facets = $fields['facet_select'] ?? [];
        $topics = json_decode($facets, true);

        $blog_post  = $fields['blog_select'] ?? false;
        $blog       = json_decode($blog_post, true);

        $all_topics = array_merge($blog ?? [], $topics ?? []);
        if (!$all_topics) {
            addMsg('select.topic', 'error');
            redirect($redirect);
        }

        return FacetModel::addPostFacets($all_topics, $content_id);
    }

    // Удаление обложки
    function imgPostRemove()
    {
        $post_id    = Request::getInt('id');
        $post = PostModel::getPost($post_id, 'id', $this->user);
        if (!accessСheck($post, 'post', $this->user, 0, 0)) {
            redirect('/');
        }

        PostModel::setPostImgRemove($post['post_id']);
        UploadImage::cover_post_remove($post['post_content_img'], $this->user['id']);

        addMsg('cover removed', 'success');
        redirect(getUrlByName('post.edit', ['id' => $post['post_id']]));
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
