<?php

namespace App\Controllers;
use App\Models\CommentModel;
use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\AnswerModel;
use App\Models\VotesModel;
use App\Models\NotificationsModel;
use App\Models\FlowModel;
use Hleb\Constructor\Handlers\Request;
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
        $comm       = CommentModel::getCommentsAll($page, $uid['id'], $uid['trust_level']);

        $result = Array();
        foreach ($comm  as $ind => $row) {
            $row['date']                = lang_date($row['comment_date']);
            $row['comment_content']     = Base::text($row['comment_content'], 'line');
            $row['comment_vote_status']    = VotesModel::voteStatus($row['comment_id'], $uid['id'], 'comment');
            $result[$ind]   = $row;
        }
        
        if($page > 1) { 
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

        return view(PR_VIEW_DIR . '/comment/all-comment', ['data' => $data, 'uid' => $uid, 'comments' => $result]);
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

        if (Base::getStrlen($comment) < 6 || Base::getStrlen($comment) > 1024)
        {
            Base::addMsg('Длина комментария должна быть от 6 до 1000 знаков', 'error');
            redirect($redirect);
            return true;
        }
        
        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении комментариев
        if($uid['trust_level'] < Config::get(Config::PARAM_TL_ADD_COMM)) {
            $num_comm =  CommentModel::getCommentSpeed($uid['id']);
            if(count($num_comm) > 9) {
                Base::addMsg('Вы исчерпали лимит комментариев (15) на сегодня', 'error');
                redirect('/');
            }
        }
        
        // Записываем коммент и получаем его url
        $last_comment_id    = CommentModel::commentAdd($post_id, $answer_id, $comment_id, $ip, $comment, $uid['id']);
        $url_comment        = $redirect . '#comment_' . $last_comment_id; 
         
        // Добавим в чат и поток
        $data_flow = [
            'flow_action_type'  => 'add_comment',
            'flow_content'      => $comment,  
            'flow_user_id'      => $uid['id'],
            'flow_pubdate'      => date("Y-m-d H:i:s"),
            'flow_url'          => $url_comment,
            'flow_target_id'    => $last_comment_id,
            'flow_space_id'     => 0,
            'flow_tl'           => $post['post_tl'], // TL поста
            'flow_ip'           => $ip, 
        ];
        FlowModel::FlowAdd($data_flow);        
         
        // Пересчитываем количество комментариев для поста + 1
        PostModel::getNumComments($post_id);
        
        // Оповещение автору ответа, что есть комментарий
        if($answer_id) {
            // Себе не записываем (перенести в общий, т.к. ничего для себя не пишем в notf)
            $answ = AnswerModel::getAnswerOne($answer_id);
            if($uid['id'] != $answ['answer_user_id']) {
                $type = 4; // Ответ на пост        
                NotificationsModel::send($uid['id'], $answ['answer_user_id'], $type, $last_comment_id, $url_comment, 1);
            }
        }
        
        // Уведомление (@login)
        if ($message = Base::parseUser($comment, true, true)) 
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
        Base::accessСheck($comment, 'comment', $uid); 
        
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
        Base::accessСheck($comment, 'comment', $uid); 

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
            $row['comment_content'] = Base::text($row['comment_content'], 'line');
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
        // Доступ только персоналу
        $uid = Base::getUid();
        if ($uid['trust_level'] != 5) {
            return false;
        }
        
        $comment_id = \Request::getPostInt('comment_id');
        
        CommentModel::CommentsDel($comment_id);
        
        return false;
    }
}