<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{CommentModel, PostModel};
use Content, Base;

class EditCommentController extends MainController
{
    // Форма редактирования comment
    public function index()
    {
        $comment_id     = Request::getPostInt('comment_id');
        $post_id        = Request::getPostInt('post_id');
        $uid            = Base::getUid();

        // Проверка доступа 
        $comment = CommentModel::getCommentsId($comment_id);
        if (!accessСheck($comment, 'comment', $uid, 0, 0)) return false;

        includeTemplate(
            '/_block/form/edit-form-comment',
            [
                'data'  => [
                    'comment_id'        => $comment_id,
                    'post_id'           => $post_id,
                    'comment_content'   => $comment['comment_content'],
                ],
                'uid'   => $uid
            ]
        );
    }

    public function edit()
    {
        $uid                = Base::getUid();
        $comment_id         = Request::getPostInt('comment_id');
        $post_id            = Request::getPostInt('post_id');
        $comment_content    = Request::getPost('comment');

        // Получим относительный url поста для возрата
        $post       = PostModel::getPostId($post_id);
        pageRedirection($post, '/');

        // Проверка доступа 
        $comment = CommentModel::getCommentsId($comment_id);
        if (!accessСheck($comment, 'comment', $uid, 0, 0)) {
            redirect('/');
        }

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($uid['user_id'], 'id');
        (new \App\Controllers\Auth\BanController())->getBan($user);
        Content::stopContentQuietМode($user);

        $slug = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        $redirect   = $slug . '#comment_' . $comment['comment_id'];

        $comment_content = Content::change($comment_content);

        // Редактируем комментарий
        CommentModel::CommentEdit($comment['comment_id'], $comment_content);
        redirect($redirect);
    }
}
