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
                'meta'  => meta($m = [], sprintf(Translate::get('edit.option'), Translate::get('post'))),
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
        $post_id                = Request::getPostInt('post_id');
        $post_title             = Request::getPost('post_title');
        $content                = $_POST['content']; // для Markdown
        $post_feature           = Request::getPostInt('post_feature');
        $post_translation       = Request::getPostInt('translation');
        $post_draft             = Request::getPostInt('post_draft');
        $post_closed            = Request::getPostInt('closed');
        $post_top               = Request::getPostInt('top');
        $draft                  = Request::getPost('draft');
        $post_merged_id         = Request::getPostInt('post_merged_id');

        // Проверка доступа 
        $post   = PostModel::getPost($post_id, 'id', $this->user);
        if (!accessСheck($post, 'post', $this->user, 0, 0)) {
            redirect('/');
        }
        
        // Если пользователь заморожен
        (new \App\Controllers\AuditController())->stopContentQuietМode($this->user['limiting_mode']);

        // Связанные посты и темы
        $post_fields    = Request::getPost() ?? [];
        if ($post['post_type'] == 'post') {
            $json_post  = $post_fields['post_select'] ?? [];
            $arr_post   = json_decode($json_post[0], true);
            if ($arr_post) {
                foreach ($arr_post as $value) {
                    $id[]   = $value['id'];
                }
            }
            $post_related = implode(',', $id ?? []);
            $facet_post     = $post_fields['facet_select'] ?? [];
            $topics         = json_decode($facet_post[0], true);
            $redirect   = getUrlByName('post.edit', ['id' => $post_id]);
        } else {
            $facet_post     = $post_fields['section_select'] ?? [];
            $topics         = json_decode($facet_post, true);
            $redirect   = getUrlByName('page.edit', ['id' => $post_id]);
        }
        
        if (!$topics) {
            addMsg('select topic', 'error');
            redirect($redirect);
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
        $cover          = $_FILES['images'];
        if ($_FILES['images']['name']) {
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
                'post_merged_id'        => $post_merged_id,
                'post_tl'               => Request::getPostInt('content_tl'),
                'post_closed'           => $post_closed,
                'post_top'              => $post_top,
            ]
        );

        // Получаем id существующего блога (использовать потом)
        $blog_id    = Request::getPostInt('blog_id');

        // Получим id блога с формы выбора
        $blog_post  = $post_fields['blog_select'] ?? [];
        $blog       = json_decode($blog_post, true);
        // $form_id    = $blog[0]['id'] ?? 0;

        if ($blog) {
            $topics = array_merge($blog, $topics);
        }

        // Запишем темы и блог
        $arr = [];
        foreach ($topics as $ket => $row) {
            $arr[] = $row;
        }
        FacetModel::addPostFacets($arr, $post_id);

        $url = $post['post_type'] == 'post' ? 'post' : 'page';
        redirect(getUrlByName($url, ['id' => $post['post_id'], 'slug' => $post['post_slug']]));
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
           return json_encode(array('data'=> array('filePath'=>UploadImage::post_img($img, $user_id, $type, $id))));
        }

        return false;
    }
}
