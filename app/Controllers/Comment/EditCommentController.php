<?php

namespace App\Controllers\Comment;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{CommentModel, PostModel};
use App\Validate\Validator;
use Access;

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
        if (Access::author('comment', $comment, config('trust-levels.edit_time_comment')) == false) {
            return false;
        }

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

    public function change()
    {
        $comment_id = Request::getPostInt('comment_id');
        $content    = $_POST['comment']; // для Markdown

        // Access verification 
        $comment = CommentModel::getCommentsId($comment_id);
        if (Access::author('comment', $comment, config('trust-levels.edit_time_comment')) == false) {
            redirect('/');
        }

        $post = PostModel::getPost($comment['comment_post_id'], 'id', $this->user);
        self::redirection($post, '/');

        $slug = url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        $redirect = $slug . '#comment_' . $comment['comment_id'];

        Validator::length($content, 3, 5500, 'content', $redirect);

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
