<?php

namespace App\Controllers\Post;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{PostModel, FeedModel, AnswerModel, CommentModel, SubscriptionModel};
use Content, Base, Translate, Config;


class PostController extends MainController
{
    // Полный пост
    public function index()
    {
        $uid        = Base::getUid();
        $slug       = Request::get('slug');
        $post_id    = Request::getInt('id');

        // Проверим (user_id, user_slug)
        $post_new   = PostModel::getPostId($post_id);
        pageError404($post_new);
        if ($slug != $post_new['post_slug']) {
            redirect(getUrlByName('post', ['id' => $post_new['post_id'], 'slug' => $post_new['post_slug']]));
        }

        $post = PostModel::getPostSlug($slug, $uid['user_id'], $uid['user_trust_level']);
        pageError404($post);

        // Если пользователь забанен
        if ($uid['user_id'] > 0) {
            $user   = UserModel::getUser($uid['user_id'], 'id');
            (new \App\Controllers\Auth\BanController())->getBan($user);
        }

        // Редирект для слияния
        if ($post['post_merged_id'] > 0 && $uid['user_trust_level'] != 5) {
            redirect('/post/' . $post['post_merged_id']);
        }

        // Просмотры поста
        if (!isset($_SESSION['pagenumbers'])) {
            $_SESSION['pagenumbers'] = [];
        }

        if (!isset($_SESSION['pagenumbers'][$post['post_id']])) {
            PostModel::updateCount($post['post_id'], 'hits');
            $_SESSION['pagenumbers'][$post['post_id']] = $post['post_id'];
        }

        // Выводить или нет? Что дает просмотр даты изменения?
        // Учитывать ли изменение в сортировки и в оповещение в будущем...
        $post['modified'] = $post['post_date'] != $post['post_modified'] ? true : false;

        $facets = PostModel::getPostTopic($post['post_id'], $uid['user_id'], 'topic');
        $blog   = PostModel::getPostTopic($post['post_id'], $uid['user_id'], 'blog');

        // Покажем черновик только автору
        if ($post['post_draft'] == 1 && $post['post_user_id'] != $uid['user_id']) {
            redirect('/');
        }

        $post['post_content']   = Content::text($post['post_content'], 'text');
        $post['post_date_lang'] = lang_date($post['post_date']);

        // Q&A (post_type == 1) или Дискуссия
        $post['amount_content'] = $post['post_answers_count'];
        if ($post['post_type'] == 0) {
            $comment_n = $post['post_comments_count'] + $post['post_answers_count'];
            $post['amount_content'] = $comment_n;
        }

        $post_answers = AnswerModel::getAnswersPost($post['post_id'], $uid['user_id'], $post['post_type']);

        $answers = [];
        foreach ($post_answers as $ind => $row) {

            if (strtotime($row['answer_modified']) < strtotime($row['answer_date'])) {
                $row['edit'] = 1;
            }
            // TODO: N+1 см. AnswerModel()
            $row['comm']            = CommentModel::getComments($row['answer_id'], $uid['user_id']);
            $row['answer_content']  = Content::text($row['answer_content'], 'text');
            $row['answer_date']     = lang_date($row['answer_date']);
            $answers[$ind]          = $row;
        }

        $content_img  = Config::get('meta.img_path');
        if ($post['post_content_img']) {
            $content_img  = AG_PATH_POSTS_COVER . $post['post_content_img'];
        } elseif ($post['post_thumb_img']) {
            $content_img  = AG_PATH_POSTS_THUMB . $post['post_thumb_img'];
        }

        $desc  = explode("\n", $post['post_content']);
        $desc  = strip_tags($desc[0]);
        if ($desc == '') {
            $desc = strip_tags($post['post_title']);
        }

        if ($post['post_is_deleted'] == 1) {
            Request::getHead()->addMeta('robots', 'noindex');
        }

        Request::getResources()->addBottomScript('/assets/js/shares.js');
        Request::getResources()->addBottomStyles('/assets/js/prism/prism.css');
        Request::getResources()->addBottomScript('/assets/js/prism/prism.js');

        if ($uid['user_id'] > 0 && $post['post_closed'] == 0) {
            Request::getResources()->addBottomStyles('/assets/js/editor/toastui-editor.min.css');
            Request::getResources()->addBottomStyles('/assets/js/editor/dark.css');
            Request::getResources()->addBottomScript('/assets/js/editor/toastui-editor-all.min.js');
        }

        if ($post['post_related']) {
            $related_posts = PostModel::postRelated($post['post_related']);
        }

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => $content_img,
            'url'        => getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]),
        ];
        
        $topic = $facets[0]['facet_title'] ?? 'agouti';
        if ($blog) {
            $topic = $blog[0]['facet_title'];
        }
        
        $meta = meta($m, strip_tags($post['post_title']) . ' — ' . $topic, $desc . ' — ' . $topic, $date_article = $post['post_date']);

        return view(
            '/post/view',
            [
                'meta'  => $meta,
                'uid'   => $uid,
                'data'  => [
                    'post'          => $post,
                    'answers'       => $answers,
                    'recommend'     => PostModel::postsSimilar($post['post_id'], $uid, 5),
                    'related_posts' => $related_posts ?? '',
                    'post_signed'   => SubscriptionModel::getFocus($post['post_id'], $uid['user_id'], 'post'),
                    'facets'        => $facets,
                    'blog'          => $blog ?? null,
                    'last_user'     => PostModel::getPostLastUser($post_id),
                    'sheet'         => 'article',
                ]
            ]
        );
    }

    // Посты участника
    public function posts($sheet)
    {
        $uid    = Base::getUid();
        $login  = Request::get('login');
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        // Если нет такого пользователя 
        $user   = UserModel::getUser($login, 'slug');
        pageError404($user);

        $limit = 100;
        $data       = ['post_user_id' => $user['user_id']];
        $posts      = FeedModel::feed($page, $limit, $uid, $sheet, 'user', $data);
        $pagesCount = FeedModel::feedCount($uid, $sheet, 'user', $data);

        $result = [];
        foreach ($posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/users/avatars/' . $user['user_avatar'],
            'url'        => getUrlByName('posts.user', ['login' => $login]),
        ];

        return view(
            '/post/post-user',
            [
                'meta'  => meta($m, Translate::get('posts') . ' ' . $login, Translate::get('participant posts') . ' ' . $login),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => 'user-post',
                    'posts'         => $result,
                    'user_login'    => $user['user_login'],
                ]
            ]
        );
    }

    // Размещение своего поста у себя в профиле
    public function addPostProfile()
    {
        $post_id    = Request::getPostInt('post_id');
        $post       = PostModel::getPostId($post_id);

        // Проверка доступа
        $uid     = Base::getUid();
        if (!accessСheck($post, 'post', $uid, 0, 0)) {
            redirect('/');
        }

        // Запретим добавлять черновик в профиль
        if ($post['post_draft'] == 1) {
            return false;
        }

        PostModel::addPostProfile($post_id, $uid['user_id']);

        return true;
    }

    // Удаление поста в профиле
    public function deletePostProfile()
    {
        $post_id    = Request::getPostInt('post_id');
        $post       = PostModel::getPostId($post_id);

        // Проверка доступа
        $uid     = Base::getUid();
        if (!accessСheck($post, 'post', $uid, 0, 0)) {
            redirect('/');
        }

        PostModel::deletePostProfile($post_id, $uid['user_id']);

        return true;
    }

    // Просмотр поста с титульной страницы
    public function shownPost()
    {
        $post_id = Request::getPostInt('post_id');
        $post    = PostModel::getPostId($post_id);

        $post['post_content'] = Content::text($post['post_content'], 'text');

        includeTemplate('/content/post/postcode', ['post' => $post]);
    }
}
