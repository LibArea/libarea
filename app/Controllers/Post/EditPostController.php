<?php

namespace App\Controllers\Post;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{FacetModel, PostModel};
use Content, Base, UploadImage, Validation, Translate;

class EditPostController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Форма редактирования post
    public function index()
    {
        $post_id    = Request::getInt('id');
        $post       = PostModel::getPostId($post_id);
        if (!accessСheck($post, 'post', $this->uid, 0, 0)) {
            redirect('/');
        }

        if ($post['post_type'] == 'post') {
            Request::getResources()->addBottomScript('/assets/js/uploads.js');
        }
        
        Request::getResources()->addBottomStyles('/assets/js/editor/toastui-editor.min.css');
        Request::getResources()->addBottomStyles('/assets/js/editor/dark.css');
        Request::getResources()->addBottomScript('/assets/js/editor/toastui-editor-all.min.js');
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        $post_related = [];
        if ($post['post_related']) {
            $post_related = PostModel::postRelated($post['post_related']);
        }

        $puth = '/post/edit';
        if ($post['post_type'] == 'page') $puth = '/page/edit';

        return agRender(
            $puth,
            [
                'meta'  => meta($m = [], Translate::get('edit post')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => 'edit-post',
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
        $post_id                = Request::getPostInt('post_id');
        $post_title             = Request::getPost('post_title');
        $post_content           = $_POST['content']; // для Markdown
        $post_feature           = Request::getPostInt('post_feature');
        $post_translation       = Request::getPostInt('translation');
        $post_draft             = Request::getPostInt('post_draft');
        $post_closed            = Request::getPostInt('closed');
        $post_top               = Request::getPostInt('top');
        $draft                  = Request::getPost('draft');
        $post_merged_id         = Request::getPostInt('post_merged_id');

        // Связанные посты и темы
        $post_fields    = Request::getPost() ?? [];

        $json_post  = $post_fields['post_select'] ?? [];
        $arr_post   = json_decode($json_post[0], true);
        if ($arr_post) {
            foreach ($arr_post as $value) {
                $id[]   = $value['id'];
            }
        }
        $post_related = implode(',', $id ?? []);

        // Темы для поста
        $facet_post     = $post_fields['facet_select'] ?? [];
        $topics         = json_decode($facet_post[0], true);

        // Проверка доступа 
        $post   = PostModel::getPostId($post_id);
        if (!accessСheck($post, 'post', $this->uid, 0, 0)) {
            redirect('/');
        }

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($this->uid['user_id'], 'id');
        (new \App\Controllers\Auth\BanController())->getBan($user);
        Content::stopContentQuietМode($user);

        $redirect   = getUrlByName('post.edit', ['id' => $post_id]);
        if (!$topics) {
            addMsg(Translate::get('select topic'), 'error');
            redirect($redirect);
        }

        // Если есть смена post_user_id и это TL5
        $user_new  = Request::getPost('user_id');
        $post_user_new = json_decode($user_new, true);
        $post_user_id = $post['post_user_id'];
        if ($post['post_user_id'] != $post_user_new[0]['id']) {
            if ($this->uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) {
                $post_user_id = $post_user_new[0]['id'];
            }
        }

        Validation::Limits($post_title, Translate::get('title'), '6', '250', $redirect);

        if ($post_content == '') {
            $post_content = $post['post_content'];
        }
        Validation::Limits($post_content, Translate::get('the post'), '6', '25000', $redirect);

        // Проверим хакинг формы
        if ($post['post_draft'] == 0) {
            $draft = 0;
        }

        $post_date = $post['post_date'];
        if ($draft == 1 && $post_draft == 0) {
            $post_date = date("Y-m-d H:i:s");
        }

        // Обложка поста
        $cover          = $_FILES['images'];
        if ($_FILES['images']['name']) {
            $post_img = UploadImage::cover_post($cover, $post, $redirect, $this->uid['user_id']);
        }
        $post_img = $post_img ?? $post['post_content_img'];

        $data = [
            'post_id'               => $post_id,
            'post_title'            => $post_title,
            'post_slug'             => $post['post_slug'],
            'post_feature'          => $post_feature,
            'post_type'             => 'post',
            'post_translation'      => $post_translation,
            'post_date'             => $post_date,
            'post_user_id'          => $post_user_id ?? 1,
            'post_draft'            => $post_draft,
            'post_content'          => Content::change($post_content),
            'post_content_img'      => $post_img ?? '',
            'post_related'          => $post_related,
            'post_merged_id'        => $post_merged_id,
            'post_tl'               => Request::getPostInt('content_tl'),
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
        ];

        // Think through the method 
        // $url = Base::estimationUrl($post_content);

        // Перезапишем пост
        PostModel::editPost($data);

        // Получаем id существующего блога (использовать потом)
        $blog_id    = Request::getPostInt('blog_id');

        // Получим id блога с формы выбора
        $blog_post  = $post_fields['blog_select'] ?? [];
        $blog       = json_decode($blog_post, true); // <- Array ([0]=> Array ([id]=> 53 [value]=> Блог [tl]=> 0)) 
        $form_id    = $blog[0]['id'];

        if ($blog) {
            $topics = array_merge($blog, $topics);
        }

        // Запишем темы и блог
        $arr = [];
        foreach ($topics as $ket => $row) {
            $arr[] = $row;
        }
        FacetModel::addPostFacets($arr, $post_id);

        redirect(getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]));
    }

    public function editPage()
    {
        $post_id                = Request::getPostInt('post_id');
        $post_title             = Request::getPost('post_title');
        $post_slug              = Request::getPost('post_slug');
        $post_content           = $_POST['content']; // для Markdown

        // Связанные темы
        $post_fields    = Request::getPost() ?? [];
        $facet_post     = $post_fields['section_select'] ?? [];
        if ($facet_post) {
            $topics     = json_decode($facet_post, true);
        }
        
        $blog_post  = $post_fields['blog_select'] ?? false;
        if ($blog_post) {
            $blog   = json_decode($blog_post, true); 
            $topics = array_merge($blog, $topics ?? []);
        }

        // Проверка доступа 
        $post   = PostModel::getPostId($post_id);
        if (!accessСheck($post, 'post', $this->uid, 0, 0)) {
            redirect('/');
        }

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($this->uid['user_id'], 'id');
        (new \App\Controllers\Auth\BanController())->getBan($user);
        Content::stopContentQuietМode($user);

        $redirect   = getUrlByName('post.edit', ['id' => $post_id]);
        if (!$topics) {
            addMsg(Translate::get('select topic'), 'error');
            redirect($redirect);
        }

        // Если есть смена post_user_id и это TL5
        $user_new  = Request::getPost('user_id');
        $post_user_new = json_decode($user_new, true);
        $post_user_id = $post['post_user_id'];
        if ($post['post_user_id'] != $post_user_new[0]['id']) {
            if ($this->uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) {
                $post_user_id = $post_user_new[0]['id'];
            }
        }

        Validation::Limits($post_title, Translate::get('title'), '6', '250', $redirect);

        if ($post_content == '') {
            $post_content = $post['post_content'];
        }
        Validation::Limits($post_content, Translate::get('the post'), '6', '25000', $redirect);

        if ($post_slug == '') {
            $post_slug = $post['post_slug'];
        }
        Validation::Limits($post_slug, Translate::get('the post'), '6', '25000', $redirect);

        $data = [
            'post_id'               => $post['post_id'],
            'post_title'            => $post_title,
            'post_slug'             => $post_slug,
            'post_feature'          => 0,
            'post_type'             => 'page',
            'post_translation'      => 0,
            'post_date'             => $post['post_date'],
            'post_user_id'          => $post_user_id ?? 1,
            'post_draft'            => 0,
            'post_content'          => Content::change($post_content),
            'post_content_img'      => '',
            'post_related'          => '',
            'post_merged_id'        => 0,
            'post_tl'               => 0,
            'post_closed'           => 0,
            'post_top'              => 0,
        ];

        // Перезапишем пост
        PostModel::editPost($data);

        // Запишем темы
        $arr = [];
        foreach ($topics as $ket => $row) {
            $arr[] = $row;
        }
        FacetModel::addPostFacets($arr, $post_id);

        $facet = FacetModel::getFacet($topics[0]['id'], 'id');
        redirect(getUrlByName('page', ['facet' => $facet['facet_slug'], 'slug' => $post_slug]));
    }

    // Удаление обложки
    function imgPostRemove()
    {
        $post_id    = Request::getInt('id');
        $post = PostModel::getPostId($post_id);
        if (!accessСheck($post, 'post', $this->uid, 0, 0)) {
            redirect('/');
        }

        PostModel::setPostImgRemove($post['post_id']);
        UploadImage::cover_post_remove($post['post_content_img'], $this->uid['user_id']);

        addMsg(Translate::get('cover removed'), 'success');
        redirect(getUrlByName('post.edit', ['id' => $post['post_id']]));
    }

    public function uploadContentImage()
    {
        $user_id    = $this->uid['user_id'];
        $type       = Request::getGet('type');
        $post_id    = Request::getGet('post_id');

        // Фотографии в тело контента
        $img        = $_FILES['file'];
        if ($_FILES['file']['name']) {
            return UploadImage::post_img($img, $user_id);
        }

        return false;
    }
}
