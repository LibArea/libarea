<?php

namespace App\Controllers\Comment;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\PostPresence;
use App\Services\Сheck\CommentPresence;
use App\Models\CommentModel;
use App\Models\User\UserModel;
use App\Validate\Validator;
use Meta, Access;

use App\Traits\Author;

class EditCommentController extends Controller
{
    use Author;

    // Edit form comment
    public function index()
    {
        $comment = CommentPresence::index(Request::getInt('id'));
        if (Access::author('comment', $comment) == false) {
            return false;
        }

        return $this->render(
            '/comments/edit',
            [
                'meta'  => Meta::get(__('app.edit_comment')),
                'data'  => [
                    'comment'	=> $comment,
                    'post'      => PostPresence::index($comment['comment_post_id'], 'id'),
                    'user'      => UserModel::getUser($comment['comment_user_id'], 'id'),
                ]
            ]
        );
    }

    public function change()
    {
        $comment_id  = Request::getPostInt('comment_id');
        $content    = $_POST['content']; // для Markdown

        // Access check
        $comment = CommentModel::getCommentId($comment_id);

        if (Access::author('comment', $comment) == false) {
            return false;
        }

        $post = PostPresence::index($comment['comment_post_id'], 'id');

        $url_post = post_slug($comment['comment_post_id'], $post['post_slug']);

        Validator::Length($content, 6, 5000, 'content', url('content.edit', ['type' => 'comment', 'id' => $comment['comment_id']]));

        CommentModel::edit(
            [
                'comment_id'         => $comment['comment_id'],
                'comment_content'    => $content,
                'comment_user_id'    => $this->selectAuthor($comment['comment_user_id'], Request::getPost('user_id')),
                'comment_modified'   => date("Y-m-d H:i:s"),
            ]
        );

        is_return(__('msg.change_saved'), 'success', $url_post . '#comment_' . $comment['comment_id']);
    }
}
