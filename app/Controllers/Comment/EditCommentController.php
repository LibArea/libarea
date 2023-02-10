<?php

namespace App\Controllers\Comment;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\{PostPresence, CommentPresence};
use App\Models\CommentModel;
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
        $comment    = CommentPresence::index(Request::getPostInt('comment_id'));
        if (Access::author('comment', $comment) == false) {
            return false;
        }

        insert(
            '/_block/form/edit-form-comment',
            [
                'data'  => [
                    'comment_id'        => $comment['comment_id'],
                    'comment_content'   => $comment['comment_content'],
                ],
                'user'   => $this->user
            ]
        );
    }

    public function change()
    {
        // Access verification 
        $comment = CommentPresence::index(Request::getPostInt('comment_id'));
        if (Access::author('comment', $comment) == false) {
            redirect('/');
        }

        $post = PostPresence::index($comment['comment_post_id'], 'id');

        $redirect = post_slug($post['post_id'], $post['post_slug']) . '#comment_' . $comment['comment_id'];

        $content = $_POST['comment']; // для Markdown
        Validator::length($content, 3, 5500, 'content', $redirect);

        CommentModel::edit(
            [
                'comment_id'        => $comment['comment_id'],
                'comment_content'   => $content,
                'comment_modified'  => date("Y-m-d H:i:s"),
            ]
        );

        redirect($redirect);
    }
}
