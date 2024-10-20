<?php

declare(strict_types=1);

namespace App\Controllers\Comment;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Content\Сheck\{PostPresence, CommentPresence};
use App\Models\CommentModel;
use App\Models\User\UserModel;
use App\Validate\Validator;
use Meta, Msg;

use App\Traits\Author;

class EditCommentController extends Controller
{
    use Author;

    /**
     * Edit form comment
     * Покажем форму редактирования комментария
     *
     * @return void
     */
    public function index(): void
    {
        $comment = CommentPresence::index(Request::param('id')->asPositiveInt());
        if (!$this->container->access()->author('comment', $comment)) {
            return;
        }

        render(
            '/comments/edit',
            [
                'meta'  => Meta::get(__('app.edit_comment')),
                'data'  => [
                    'comment'   => $comment,
                    'post'      => PostPresence::index($comment['comment_post_id'], 'id'),
                    'user'      => UserModel::get($comment['comment_user_id'], 'id'),
                ]
            ]
        );
    }

    /**
     * Let's change the comment
     * Изменим комментарий
     *
     * @return void
     */
    public function edit()
    {
        $comment_id  = Request::post('comment_id')->asInt();
        $content    = $_POST['content']; // для Markdown

        $comment = CommentModel::getCommentId($comment_id);
        if (!$this->container->access()->author('comment', $comment)) {
            return;
        }

        $post = PostPresence::index($comment['comment_post_id'], 'id');

        notEmptyOrView404($post);

        Validator::Length($content, 6, 5000, 'content', url('comment.form.edit', ['id' => $comment_id]));

        CommentModel::edit(
            [
                'comment_id'         => $comment['comment_id'],
                'comment_content'    => $content,
                'comment_user_id'    => $this->selectAuthor($comment['comment_user_id'], Request::post('user_id')->value()),
                'comment_modified'   => date("Y-m-d H:i:s"),
            ]
        );

        Msg::redirect(__('msg.change_saved'), 'success', post_slug($comment['comment_post_id'], $post['post_slug']) . '#comment_' . $comment['comment_id']);
    }

    /**
     * Form for transferring a comment to another post or making a post out of it
     * Форма переноса комменатрия в другой пост или сделать из него пост
     * 
     * TODO!!!
     *
     * @return void
     */
    public function transfer(): void
    {
        $comment = CommentPresence::index(Request::param('id')->asPositiveInt());

        render(
            '/comments/transfer',
            [
                'meta'  => Meta::get(__('app.edit_comment')),
                'data'  => [
                    'comment'   => $comment,
                    'post'      => PostPresence::index($comment['comment_post_id'], 'id'),
                    'user'      => UserModel::get($comment['comment_user_id'], 'id'),
                ]
            ]
        );
    }
}
