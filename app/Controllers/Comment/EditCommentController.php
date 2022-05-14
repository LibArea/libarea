<?php

namespace App\Controllers\Comment;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{CommentModel, PostModel};
use Html;

class EditCommentController extends Controller
{
    // Comment Editing Form
    // Форма редактирования комментария
    public function index()
    {
        // Access verification
        // Проверка доступа 
        $comment_id = Request::getPostInt('comment_id');
        $comment    = CommentModel::getCommentsId($comment_id);
        if (!Html::accessСheck($comment, 'comment', 0, 0)) return false;

        insert(
            '/_block/form/edit-form-comment',
            [
                'data'  => [
                    'comment_id'        => $comment_id,
                    'comment_content'   => $comment['comment_content'],
                ],
                'user'   => $this->user
            ]
        );
    }

    public function edit()
    {
        $comment_id = Request::getPostInt('comment_id');
        $content    = $_POST['comment']; // для Markdown

        // Access verification 
        $comment = CommentModel::getCommentsId($comment_id);
        if (!Html::accessСheck($comment, 'comment', 0, 0)) {
            redirect('/');
        }

        // If the user is frozen
        (new \App\Controllers\AuditController())->stopContentQuietМode($this->user['limiting_mode']);

        $post       = PostModel::getPost($comment['comment_post_id'], 'id', $this->user);
        Html::pageRedirection($post, '/');

        $slug = url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        $redirect   = $slug . '#comment_' . $comment['comment_id'];

        CommentModel::edit(
            [
                'comment_id'        => $comment_id,
                'comment_content'   => $content,
                'comment_modified'  => date("Y-m-d H:i:s"),
            ]
        );

        redirect($redirect);
    }
}
