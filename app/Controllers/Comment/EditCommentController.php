<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{CommentModel, PostModel};
use Content, Tpl, UserData;

class EditCommentController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Comment Editing Form
    // Форма редактирования комментария
    public function index()
    {
        $comment_id     = Request::getPostInt('comment_id');
        $post_id        = Request::getPostInt('post_id');

        // Access verification
        // Проверка доступа 
        $comment = CommentModel::getCommentsId($comment_id);
        if (!accessСheck($comment, 'comment', $this->user, 0, 0)) return false;

        Tpl::agIncludeTemplate(
            '/_block/form/edit-form-comment',
            [
                'data'  => [
                    'comment_id'        => $comment_id,
                    'post_id'           => $post_id,
                    'comment_content'   => $comment['comment_content'],
                ],
                'user'   => $this->user
            ]
        );
    }

    public function edit()
    {
        $comment_id = Request::getPostInt('comment_id');
        $post_id    = Request::getPostInt('post_id');
        $content    = $_POST['comment']; // для Markdown

        $post       = PostModel::getPost($post_id, 'id', $this->user);
        pageRedirection($post, '/');

        // Access verification 
        $comment = CommentModel::getCommentsId($comment_id);
        if (!accessСheck($comment, 'comment', $this->user, 0, 0)) {
            redirect('/');
        }

        // If the user is frozen
        (new \App\Controllers\AuditController())->stopContentQuietМode($this->user['limiting_mode']);

        $slug = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        $redirect   = $slug . '#comment_' . $comment['comment_id'];

        CommentModel::edit(
            [
                'comment_id'        => $comment_id,
                'comment_content'   => Content::change($content),
                'comment_modified'  => date("Y-m-d H:i:s"),
            ]
        );

        redirect($redirect);
    }
}
