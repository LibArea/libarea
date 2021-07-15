<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\CommentModel;
use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\VotesModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class CommentController extends \MainController
{
    // Все комментарии
    public function index()
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page'); 
        $page   = $page == 0 ? 1 : $page;
        
        $limit  = 25;
        $pagesCount = CommentModel::getCommentAllCount();  
        $comments   = CommentModel::getCommentsAll($page, $limit, $uid);

        $result = Array();
        foreach ($comments  as $ind => $row) {
            $row['date']                = lang_date($row['comment_date']);
            $row['comment_content']     = Content::text($row['comment_content'], 'line');
            $result[$ind]   = $row;
        }
        
        $num = ' | ';
        if ($page > 1) { 
            $num = sprintf(lang('page-number'), $page) . ' | ';
        } 
        
        $data = [
            'h1'            => lang('All comments'),
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'canonical'     => Config::get(Config::PARAM_URL) . '/comments', 
            'sheet'         => 'comments', 
            'meta_title'    => lang('All comments') . $num . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('comments-desc') . $num . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/comment/comments', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }

   // Покажем форму редактирования
	public function editCommentForm()
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

    // Редактируем комментарий
    public function editComment()
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
        
        $redirect   = '/post/' . $post['post_id'] . '/' . $post['post_slug'] . '#comment_' . $comment['comment_id'];
        
        // Редактируем комментарий
        CommentModel::CommentEdit($comment['comment_id'], $comment_content);
        redirect($redirect); 
	}

	// Покажем форму ответа
	public function addForm()
	{
        $post_id    = \Request::getPostInt('post_id');
        $answer_id  = \Request::getPostInt('answer_id');
        $comment_id = \Request::getPostInt('comment_id');
        
        $uid  = Base::getUid();
        $data = [
            'answer_id'     => $answer_id,
            'post_id'       => $post_id,
            'comment_id'    => $comment_id,
        ]; 
        
        return view(PR_VIEW_DIR . '/comment/add-form-answer-comment', ['data' => $data, 'uid' => $uid]);
    }

    // Комментарии участника
    public function userComments()
    {
        $login = \Request::get('login');

        // Если нет такого пользователя 
        $user   = UserModel::getUser($login, 'slug');
        Base::PageError404($user);
        
        $comm  = CommentModel::userComments($login); 
        
        $result = Array();
        foreach ($comm as $ind => $row) {
            $row['comment_content'] = Content::text($row['comment_content'], 'line');
            $row['date']            = lang_date($row['comment_date']);
            $result[$ind]           = $row;
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Comments-n') . ' ' . $login,
            'canonical'     => Config::get(Config::PARAM_URL) . '/u/' . $login . '/comments', 
            'sheet'         => 'user-comments', 
            'meta_title'    => lang('Comments-n') . ' ' . $login .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('Comments-n') . ' ' . $login .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];
        
        return view(PR_VIEW_DIR . '/comment/user-comment', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }

}