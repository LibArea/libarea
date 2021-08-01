<?php

namespace App\Controllers\Comment;

use Hleb\Constructor\Handlers\Request;
use App\Models\CommentModel;
use App\Models\PostModel;
use Lori\Content;
use Lori\Base;

class EditCommentController extends \MainController
{
    // Редактируем комментарий
    public function index()
    {
        $uid                = Base::getUid();
        $comment_id         = \Request::getPostInt('comment_id');
        $post_id            = \Request::getPostInt('post_id');
        $comment_content    = \Request::getPost('comment');

        // Получим относительный url поста для возрата
        $post       = PostModel::getPostId($post_id);
        Base::PageRedirection($post);

        $comment = CommentModel::getCommentsId($comment_id);

        // Проверка доступа 
        if (!accessСheck($comment, 'comment', $uid, 0, 0)) {
            redirect('/');
        }

        Content::stopContentQuietМode($uid);

        $redirect   = '/post/' . $post['post_id'] . '/' . $post['post_slug'] . '#comment_' . $comment['comment_id'];

        // Редактируем комментарий
        CommentModel::CommentEdit($comment['comment_id'], $comment_content);
        redirect($redirect);
    }

    // Покажем форму
    public function edit()
    {
        $comment_id     = \Request::getPostInt('comment_id');
        $post_id        = \Request::getPostInt('post_id');
        $uid            = Base::getUid();

        $comment = CommentModel::getCommentsId($comment_id);

        // Проверка доступа 
        if (!accessСheck($comment, 'comment', $uid, 0, 0)) {
            redirect('/');
        }

        $data = [
            'comment_id'           => $comment_id,
            'post_id'           => $post_id,
            'user_id'           => $uid['id'],
            'comment_content'   => $comment['comment_content'],
        ];

        return view(PR_VIEW_DIR . '/comment/edit-form-comment', ['data' => $data]);
    }
}
