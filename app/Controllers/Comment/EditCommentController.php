<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\{CommentModel, PostModel};
use Content;

class EditCommentController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    // Форма редактирования comment
    public function index()
    {
        $comment_id     = Request::getPostInt('comment_id');
        $post_id        = Request::getPostInt('post_id');

        // Проверка доступа 
        $comment = CommentModel::getCommentsId($comment_id);
        if (!accessСheck($comment, 'comment', $this->uid, 0, 0)) return false;

        agIncludeTemplate(
            '/_block/form/edit-form-comment',
            [
                'data'  => [
                    'comment_id'        => $comment_id,
                    'post_id'           => $post_id,
                    'comment_content'   => $comment['comment_content'],
                ],
                'uid'   => $this->uid
            ]
        );
    }

    public function edit()
    {
        $comment_id         = Request::getPostInt('comment_id');
        $post_id            = Request::getPostInt('post_id');
        $comment_content    = Request::getPost('comment');

        // Получим относительный url поста для возрата
        $post       = PostModel::getPost($post_id, 'id', $this->uid);
        pageRedirection($post, '/');

        // Проверка доступа 
        $comment = CommentModel::getCommentsId($comment_id);
        if (!accessСheck($comment, 'comment', $this->uid, 0, 0)) {
            redirect('/');
        }

        // Если пользователь заморожен
        (new \App\Controllers\AuditController())->stopContentQuietМode($this->uid['user_limiting_mode']); 

        $slug = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
        $redirect   = $slug . '#comment_' . $comment['comment_id'];

        $comment_content = Content::change($comment_content);

        // Редактируем комментарий
        CommentModel::CommentEdit($comment['comment_id'], $comment_content);
        redirect($redirect);
    }
}
