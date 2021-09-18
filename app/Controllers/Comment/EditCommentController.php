<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{CommentModel, PostModel, UserModel};
use Agouti\{Content, Base};

class EditCommentController extends MainController
{
    // Редактируем комментарий
    public function index()
    {
        $uid                = Base::getUid();
        $comment_id         = Request::getPostInt('comment_id');
        $post_id            = Request::getPostInt('post_id');
        $comment_content    = Request::getPost('comment');

        // Получим относительный url поста для возрата
        $post       = PostModel::getPostId($post_id);
        Base::PageRedirection($post, '/');

        // Проверка доступа 
        $comment = CommentModel::getCommentsId($comment_id);
        if (!accessСheck($comment, 'comment', $uid, 0, 0)) {
            redirect('/');
        }

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($uid['user_id'], 'id');
        Base::accountBan($user);
        Content::stopContentQuietМode($user);

        $redirect   = '/post/' . $post['post_id'] . '/' . $post['post_slug'] . '#comment_' . $comment['comment_id'];

        $comment_content = Content::change($comment_content);

        // Редактируем комментарий
        CommentModel::CommentEdit($comment['comment_id'], $comment_content);
        redirect($redirect);
    }

    // Покажем форму
    public function edit()
    {
        $comment_id     = Request::getPostInt('comment_id');
        $post_id        = Request::getPostInt('post_id');
        $uid            = Base::getUid();

        // Проверка доступа 
        $comment = CommentModel::getCommentsId($comment_id);
        if (!accessСheck($comment, 'comment', $uid, 0, 0)) {
            redirect('/');
        }

        $data = [
            'comment_id'        => $comment_id,
            'post_id'           => $post_id,
            'comment_content'   => $comment['comment_content'],
        ];

        includeTemplate('/_block/form/edit-form-comment', ['data' => $data, 'uid' => $uid]);
    }
}
