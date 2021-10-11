<?php

namespace App\Controllers\Post;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{PostModel, SpaceModel, TopicModel};
use Content, Base, UploadImage, Validation;

class EditPostController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Изменяем пост
    public function index()
    {
        $post_id                = Request::getPostInt('post_id');
        $post_title             = Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // для Markdown
        $post_type              = Request::getPostInt('post_type');
        $post_translation       = Request::getPostInt('translation');
        $post_draft             = Request::getPostInt('post_draft');
        $post_closed            = Request::getPostInt('closed');
        $post_top               = Request::getPostInt('top');
        $post_space_id          = Request::getPostInt('space_id');
        $draft                  = Request::getPost('draft');
        $post_user_new          = Request::getPost('user_select');
        $post_merged_id         = Request::getPostInt('post_merged_id');
        $post_tl                = Request::getPostInt('post_tl');

        // Связанные посты и темы
        $post_fields    = Request::getPost() ?? [];
        $post_related   = implode(',', $post_fields['post_select'] ?? []);
        $topics         = $post_fields['topic_select'] ?? [];

        // Проверка доступа 
        $post   = PostModel::getPostId($post_id);
        if (!accessСheck($post, 'post', $this->uid, 0, 0)) {
            redirect('/');
        }

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($this->uid['user_id'], 'id');
        Base::accountBan($user);
        Content::stopContentQuietМode($user);

        $redirect   = '/post/edit/' . $post_id;

        // Получаем информацию по пространству
        $space = SpaceModel::getSpace($post_space_id, 'id');
        if (!$space) {
            addMsg(lang('select space'), 'error');
            redirect($redirect);
        }

        // Если стоит ограничение: публиковать может только автор
        if ($space['space_permit_users'] == 1) {
            // Кроме персонала и владельца
            if ($this->uid['user_trust_level'] != 5 && $space['space_user_id'] != $this->uid['user_id']) {
                addMsg(lang('you dont have access'), 'error');
                redirect($redirect);
            }
        }

        // Если есть смена post_user_id и это TL5
        if ($post['post_user_id'] != $post_user_new) {
            if ($this->uid['user_trust_level'] != 5) {
                $post_user_id = $post['post_user_id'];
            } else {
                $post_user_id = $post_user_new;
            }
        } else {
            $post_user_id = $post['post_user_id'];
        }

        Validation::Limits($post_title, lang('title'), '6', '250', $redirect);
        Validation::Limits($post_content, lang('the post'), '6', '25000', $redirect);

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
        if ($_FILES['images']['name'][0]) {
            $post_img = UploadImage::cover_post($cover, $post, $redirect);
        }

        $post_img = $post_img ?? $post['post_content_img'];

        $data = [
            'post_id'               => $post_id,
            'post_title'            => $post_title,
            'post_type'             => $post_type,
            'post_translation'      => $post_translation,
            'post_date'             => $post_date,
            'post_user_id'          => $post_user_id,
            'post_draft'            => $post_draft,
            'post_space_id'         => $post_space_id,
            'post_content'          => Content::change($post_content),
            'post_content_img'      => $post_img ?? '',
            'post_related'          => $post_related,
            'post_merged_id'        => $post_merged_id,
            'post_tl'               => $post_tl,
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
        ];

        // Think through the method 
        // $url = Base::estimationUrl($post_content);

        // Перезапишем пост
        PostModel::editPost($data);

        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $row) {
                $arr[] = array($row, $post_id);
            }
            TopicModel::addPostTopics($arr, $post_id);
        }

        redirect(getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]));
    }

    // Покажем форму
    public function edit()
    {
        // Проверка доступа 
        $post_id    = Request::getInt('id');
        $post       = PostModel::getPostId($post_id);
        if (!accessСheck($post, 'post', $this->uid, 0, 0)) {
            redirect('/');
        }

        Request::getHead()->addStyles('/assets/css/image-uploader.css');
        Request::getResources()->addBottomStyles('/assets/css/select2.css');
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');
        Request::getResources()->addBottomStyles('/assets/editor/editormd.css');
        Request::getResources()->addBottomScript('/assets/editor/meditor.min.js');
        Request::getResources()->addBottomScript('/assets/js/select2.min.js');

        $meta = meta($m = [], lang('edit post'));
        $data = [
            'sheet'         => 'edit-post',
            'post'          => $post,
            'post_select'   => PostModel::postRelated($post['post_related']),
            'space'         => SpaceModel::getSpaceSelect($this->uid['user_id'], $this->uid['user_trust_level']),
            'user'          => UserModel::getUser($post['post_user_id'], 'id'),
            'topic_select'  => PostModel::getPostTopic($post['post_id']),
        ];

        return view('/post/edit', ['meta' => $meta, 'uid' => $this->uid, 'data' => $data]);
    }

    // Удаление обложки
    function imgPostRemove()
    {
        $post_id    = Request::getInt('id');
        $post = PostModel::getPostId($post_id);
        if (!accessСheck($post, 'post', $this->uid, 0, 0)) {
            redirect('/');
        }

        $path_img = HLEB_PUBLIC_DIR . AG_PATH_POSTS_COVER . $post['post_content_img'];

        PostModel::setPostImgRemove($post['post_id']);
        unlink($path_img);

        addMsg(lang('cover removed'), 'success');
        redirect('/post/edit/' . $post['post_id']);
    }

    public function uploadContentImage()
    {
        $user_id    = $this->uid['user_id'];
        $type       = Request::getGet('type');
        $post_id    = Request::getGet('post_id');

        // Фотографии в тело контента
        $img         = $_FILES['editormd-image-file'];
        if ($_FILES['editormd-image-file']['name']) {

            $post_img = UploadImage::post_img($img);
            $response = array(
                "url"     => $post_img,
                "message" => lang('successful download'),
                "success" => 1,
            );

            return json_encode($response);
        }

        $response = array(
            "message" => lang('error in loading'),
            "success" => 0,
        );

        return json_encode($response);
    }
}
