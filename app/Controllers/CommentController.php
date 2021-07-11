<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use App\Models\ModerationModel;
use App\Models\CommentModel;
use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\AnswerModel;
use App\Models\VotesModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class CommentController extends \MainController
{
    // Все комментарии
    public function index()
    {
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        
        $uid    = Base::getUid();
         
        $pagesCount = CommentModel::getCommentAllCount();  
        $comm       = CommentModel::getCommentsAll($page, $uid['trust_level']);

        $result = Array();
        foreach ($comm  as $ind => $row) {
            $row['date']                = lang_date($row['comment_date']);
            $row['comment_content']     = Content::text($row['comment_content'], 'line');
            $row['comment_vote_status'] = VotesModel::voteStatus($row['comment_id'], $uid['id'], 'comment');
            $result[$ind]   = $row;
        }
        
        if ($page > 1) { 
            $num = ' — ' . lang('Page') . ' ' . $page;
        } else {
            $num = '';
        }
        
        $data = [
            'h1'            => lang('All comments'),
            'pagesCount'    => $pagesCount,
            'pNum'          => $page,
            'canonical'     => Config::get(Config::PARAM_URL) . '/comments', 
            'sheet'         => 'comments', 
            'meta_title'    => lang('All comments') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('comments-desc') .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/comment/comments', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }

    // Добавление комментария
    public function createComment()
    {
        $comment    = \Request::getPost('comment');
        $post_id    = \Request::getPostInt('post_id');   // в каком посту ответ
        $answer_id  = \Request::getPostInt('answer_id');   // на какой ответ
        $comment_id = \Request::getPostInt('comment_id');   // на какой комментарий
        
        $uid        = Base::getUid();
        $ip         = \Request::getRemoteAddress(); 
        
        $post       = PostModel::postId($post_id);
        Base::PageError404($post);

        $redirect = '/post/' . $post['post_id'] . '/' . $post['post_slug'];

        // Проверяем длину тела
        Base::Limits($comment, lang('Comments-m'), '6', '1024', $redirect);

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении комментариев
        if ($uid['trust_level'] < Config::get(Config::PARAM_TL_ADD_COMM)) {
            $num_comm =  CommentModel::getCommentSpeed($uid['id']);
            if (count($num_comm) > 9) {
                Base::addMsg('Вы исчерпали лимит комментариев (15) на сегодня', 'error');
                redirect('/');
            }
        }
        
        // Записываем коммент и получаем его url
        $last_comment_id    = CommentModel::commentAdd($post_id, $answer_id, $comment_id, $ip, $comment, $uid['id']);
        $url_comment        = $redirect . '#comment_' . $last_comment_id; 

        // Пересчитываем количество комментариев для поста + 1
        PostModel::updateCount($post_id, 'comments');
        
        // Оповещение автору ответа, что есть комментарий
        if ($answer_id) {
            // Себе не записываем (перенести в общий, т.к. ничего для себя не пишем в notf)
            $answ = AnswerModel::getAnswerOne($answer_id);
            if ($uid['id'] != $answ['answer_user_id']) {
                $type = 4; // Ответ на пост        
                NotificationsModel::send($uid['id'], $answ['answer_user_id'], $type, $last_comment_id, $url_comment, 1);
            }
        }
        
        // Уведомление (@login)
        if ($message = Content::parseUser($comment, true, true)) 
        {
            foreach ($message as $user_id) {
                // Запретим отправку себе и автору ответа (оповщение ему выше)
                if ($user_id == $uid['id'] || $user_id == $answ['answer_user_id']) {
                    continue;
                }
                $type = 12; // Упоминания в комментарии      
                NotificationsModel::send($uid['id'], $user_id, $type, $last_comment_id, $url_comment, 1);
            }
        }
        
        redirect($url_comment); 
    }
    
    // Редактируем комментарий
    public function editComment()
    {
        $comment_id         = \Request::getPostInt('comment_id');
        $post_id            = \Request::getPostInt('post_id');
        $comment_content    = \Request::getPost('comment');

        // Получим относительный url поста для возрата
        $post       = PostModel::postId($post_id);
        $redirect   = '/post/' . $post['post_id'] . '/' . $post['post_slug'] . '#comment_' . $comment_id;
        
        // id того, кто редактирует
        $uid        = Base::getUid();
        $user_id    = $uid['id'];
        
        $comment = CommentModel::getCommentsOne($comment_id);
        
        // Проверка доступа 
        if (!accessСheck($comment, 'comment', $uid, 0, 0)) {
            redirect('/');
        }  
        
        // Редактируем комментарий
        CommentModel::CommentEdit($comment_id, $comment_content);
        redirect($redirect); 
	}

   // Покажем форму редактирования
	public function editCommentForm()
	{
        $comment_id     = \Request::getPostInt('comment_id');
        $post_id        = \Request::getPostInt('post_id');
        $uid            = Base::getUid();
        
        $comment = CommentModel::getCommentsOne($comment_id);

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
        $user   = UserModel::getUserLogin($login);
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

    // Удаление комментария
    public function deletComment()
    {
        $uid        = Base::getUid();
        $comment_id = \Request::getPostInt('comment_id');
 
        $comment = CommentModel::getCommentsOne($comment_id); 
        
        // Проверка доступа 
        if (!accessСheck($comment, 'comment', $uid, 1, 30)) {
            redirect('/');
        } 
        
        CommentModel::CommentDel($comment['comment_id']);
        
        $data = [
            'user_id'       => $uid['id'], 
            'user_tl'       => $uid['trust_level'], 
            'created_at'    => date("Y-m-d H:i:s"), 
            'post_id'       => $comment['comment_post_id'],
            'content_id'    => $comment['comment_id'], 
            'action'        => 'deleted-comment',
            'reason'        => '',
        ];
        
        ModerationModel::moderationsAdd($data);
        
        return true;
    }
    
    // Восстановление комментария
    public function recoverComment()
    {
        $uid        = Base::getUid();
        $comment_id = \Request::getPostInt('id');
        
        $comment = CommentModel::getCommentsOne($comment_id); 
        
        // Проверка доступа 
        if (!accessСheck($comment, 'comment', $uid, 1, 30)) {
            redirect('/');
        } 
        
        CommentModel::commentRecover($comment['comment_id']);
        
        $data = [
            'user_id'       => $uid['id'], 
            'user_tl'       => $uid['trust_level'], 
            'created_at'    => date("Y-m-d H:i:s"), 
            'post_id'       => $comment['comment_post_id'],
            'content_id'    => $comment['comment_id'], 
            'action'        => 'restored-comment',
            'reason'        => '',
        ];
        
        ModerationModel::moderationsAdd($data);
        
        return true;
    }
    
}