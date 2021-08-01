<?php

namespace App\Controllers\Post;

use Hleb\Constructor\Handlers\Request;
use App\Models\PostModel;
use App\Models\UserModel;
use App\Models\SpaceModel;
use App\Models\TopicModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;
use Lori\UploadImage;

class EditPostController extends \MainController
{
    // Изменяем пост
    public function index()
    {
        $post_id                = \Request::getPostInt('post_id');
        $post_title             = \Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // не фильтруем 
        $post_type              = \Request::getPostInt('post_type');
        $post_translation       = \Request::getPostInt('translation');
        $post_draft             = \Request::getPostInt('post_draft');
        $post_closed            = \Request::getPostInt('closed');
        $post_top               = \Request::getPostInt('top');
        $post_space_id          = \Request::getPostInt('space_id');
        $draft                  = \Request::getPost('draft');
        $post_user_new          = \Request::getPost('post_user_new');
        $post_merged_id         = \Request::getPostInt('post_merged_id');
        $post_tl                = \Request::getPostInt('post_tl');

        $uid    = Base::getUid();

        // Связанные посты и темы
        $related        = empty($_POST['post_related']) ? '' : $_POST['post_related'];
        $post_related   = empty($related) ? '' : implode(',', $related);
        $topics         = empty($_POST['post_topics']) ? '' : $_POST['post_topics'];

        $post = PostModel::getPostId($post_id);

        $redirect = '/post/edit/' . $post_id;

        // Проверка доступа 
        if (!accessСheck($post, 'post', $uid, 0, 0)) {
            redirect('/');
        }

        Content::stopContentQuietМode($uid);

        // Получаем информацию по пространству
        $space = SpaceModel::getSpace($post_space_id, 'id');
        if (!$space) {
            Base::addMsg(lang('Select space'), 'error');
            redirect($redirect);
        }

        // Если стоит ограничение: публиковать может только автор
        if ($space['space_permit_users'] == 1) {
            // Кроме персонала и владельца
            if ($uid['trust_level'] != 5 && $space['space_user_id'] != $uid['id']) {
                Base::addMsg(lang('You dont have access'), 'error');
                redirect($redirect);
            }
        }

        // Если есть смена post_user_id и это TL5
        if ($post['post_user_id'] != $post_user_new) {
            if ($uid['trust_level'] != 5) {
                $post_user_id = $post['post_user_id'];
            } else {
                $post_user_id = $post_user_new;
            }
        } else {
            $post_user_id = $post['post_user_id'];
        }

        Base::Limits($post_title, lang('Title'), '6', '250', $redirect);
        Base::Limits($post_content, lang('The post'), '6', '25000', $redirect);

        // Проверим хакинг формы
        if ($post['post_draft'] == 0) {
            $draft = 0;
        }

        // $draft = 1 // это черновик
        // $post_draft = 0 // изменили
        if ($draft == 1 && $post_draft == 0) {
            $post_date = date("Y-m-d H:i:s");
        } else {
            $post_date = $post['post_date'];
        }

        // Обложка поста
        $cover          = $_FILES['images'];
        $check_cover    = $_FILES['images']['name'][0];
        if ($check_cover) {
            $post_img = UploadImage::cover_post($cover, $post, $redirect);
        }

        $post_img = empty($post_img) ? $post['post_content_img'] : $post_img;
        $post_img = empty($post_img) ? '' : $post_img;

        $data = [
            'post_id'               => $post_id,
            'post_title'            => $post_title,
            'post_type'             => $post_type,
            'post_translation'      => $post_translation,
            'post_date'             => $post_date,
            'post_user_id'          => $post_user_id,
            'post_draft'            => $post_draft,
            'post_space_id'         => $post_space_id,
            'post_content'          => $post_content,
            'post_content_img'      => $post_img,
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

        redirect('/post/' . $post['post_id'] . '/' . $post['post_slug']);
    }

    // Покажем форму
    public function edit()
    {
        $post_id    = \Request::getInt('id');
        $uid        = Base::getUid();

        $post   = PostModel::getPostId($post_id);

        // Проверка доступа 
        if (!accessСheck($post, 'post', $uid, 0, 0)) {
            redirect('/');
        }

        $space = SpaceModel::getSpaceSelect($uid['id'], $uid['trust_level']);
        $user  = UserModel::getUser($post['post_user_id'], 'id');

        Request::getHead()->addStyles('/assets/css/image-uploader.css');
        Request::getResources()->addBottomStyles('/assets/css/select2.css');
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        Request::getResources()->addBottomStyles('/assets/editor/editormd.css');
        Request::getResources()->addBottomScript('/assets/editor/editormd.min.js');
        Request::getResources()->addBottomScript('/assets/editor/config.js');

        if ($uid['trust_level'] > 0) {
            Request::getResources()->addBottomScript('/assets/js/select2.min.js');
        }

        $post_related = PostModel::postRelated($post['post_related']);
        $post_topics  = PostModel::getPostTopic($post['post_id']);

        $data = [
            'sheet'         => 'edit-post',
            'h1'            => lang('Edit post'),
            'meta_title'    => lang('Edit post') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        return view(PR_VIEW_DIR . '/post/edit', ['data' => $data, 'uid' => $uid, 'post' => $post, 'post_related' => $post_related, 'space' => $space, 'user' => $user, 'post_topics' => $post_topics]);
    }

    // Удаление обложки
    function imgPostRemove()
    {
        $post_id    = \Request::getInt('id');
        $uid        = Base::getUid();

        $post = PostModel::getPostId($post_id);

        // Проверка доступа 
        if (!accessСheck($post, 'post', $uid, 0, 0)) {
            redirect('/');
        }

        $path_img = HLEB_PUBLIC_DIR . '/uploads/posts/cover/' . $post['post_content_img'];

        PostModel::setPostImgRemove($post['post_id']);
        unlink($path_img);

        Base::addMsg(lang('Cover removed'), 'success');
        redirect('/post/edit/' . $post['post_id']);
    }

    public function uploadimage()
    {
        // Фотографии в тело поста
        $img         = $_FILES['editormd-image-file'];
        $name_img    = $_FILES['editormd-image-file']['name'];
        if ($name_img) {
            $post_img = UploadImage::post_img($img);
            $response = array(
                "url"     => $post_img,
                "message" => lang('Successful download'),
                "success" => 1,
            );

            return json_encode($response);
        }

        $response = array(
            "message" => lang('Error in loading'),
            "success" => 0,
        );

        return json_encode($response);
    }
}
