<?php

namespace App\Controllers;
use App\Models\CommentModel;
use App\Models\UserModel;
use App\Models\AnswerModel;
use App\Models\PostModel;
use App\Models\VotesModel;
use App\Models\NotificationsModel;
use App\Models\FlowModel;
use Hleb\Constructor\Handlers\Request;
use Lori\Config;
use Lori\Base;

class AnswerController extends \MainController
{
    // Все ответы
    public function index()
    {
        $pg = \Request::getInt('page'); 
        $page = (!$pg) ? 1 : $pg;
        
        $uid        = Base::getUid();
         
        $pagesCount = AnswerModel::getAnswersAllCount();  
        $answ       = AnswerModel::getAnswersAll($page, $uid['id'], $uid['trust_level']);
 
        $result = Array();
        foreach ($answ  as $ind => $row) {
            $row['answer_content']  = Base::text($row['answer_content'], 'md');
            $row['date']            = lang_date($row['answer_date']);
            // N+1 - перенести в запрос
            $row['answer_vote_status'] = VotesModel::voteStatus($row['answer_id'], $uid['id'], 'answer');
            $result[$ind]   = $row;
        }
        
        if($page > 1) { 
            $num = ' — ' . lang('Page') . ' ' . $page;
        } else {
            $num = '';
        }
        
        $data = [
            'h1'            => lang('All answers'),
            'pagesCount'    => $pagesCount,
            'pNum'          => $page,
            'canonical'     => Config::get(Config::PARAM_URL) . '/answers',
            'sheet'         => 'answers', 
            'meta_title'    => lang('All answers') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('answers-desc') .' '. Config::get(Config::PARAM_HOME_TITLE),            
        ];

        return view(PR_VIEW_DIR . '/answer/all-answer', ['data' => $data, 'uid' => $uid, 'answers' => $result]);
    }

    // Добавление ответа
    public function createAnswer()
    {
        $answer     = \Request::getPost('answer');

        // Получаем информацию по посту
        $post_id    = \Request::getPostInt('post_id');  
        $post       = PostModel::postId($post_id);
        Base::PageError404($post);
        
        $answer     = $_POST['answer'];                 // не фильтруем
        $ip         = \Request::getRemoteAddress();     // ip отвечающего 
        
        // id того, кто отвечает
        $uid        = Base::getUid();
        
        $redirect = '/post/' . $post['post_id'] . '/' . $post['post_slug'];
        Base::Limits($answer, lang('Bodies'), '6', '5000', $redirect);
        
        // Ограничим частоту добавления
        // Добавить условие TL
        if($uid['trust_level'] < 2) {
            $num_answ =  AnswerModel::getAnswerSpeed($uid['id']);
            if(count($num_answ) > 10) {
                Base::addMsg('Вы исчерпали лимит ответов (10) на сегодня', 'error');
                redirect('/');
            }
        }
        
        // Записываем ответ и получаем его url
        $last_answer_id = AnswerModel::answerAdd($post_id, $ip, $answer, $uid['id']);
        $url_answer     = $redirect . '#answer_' . $last_answer_id; 
        
        // Уведомление (@login)
        if ($message = Base::parseUser($answer, true, true)) 
        {
            foreach ($message as $user_id) {
                // Запретим отправку себе
                if ($user_id == $uid['id']) {
                    continue;
                }
                $type = 11; // Упоминания в ответе      
                NotificationsModel::send($uid['id'], $user_id, $type, $last_answer_id, $url_answer, 1);
            }
        }

        // Добавим в поток
        $data_flow = [
            'flow_action_type'  => 'add_answer',
            'flow_content'      => $answer, // не фильтруем
            'flow_user_id'      => $uid['id'],
            'flow_pubdate'      => date("Y-m-d H:i:s"),
            'flow_url'          => $url_answer,
            'flow_target_id'    => $last_answer_id,
            'flow_space_id'     => 0,
            'flow_tl'           => $post['post_tl'], // TL поста
            'flow_ip'           => $ip, 
        ];
        FlowModel::FlowAdd($data_flow);        
         
        // Пересчитываем количество ответов для поста + 1
        PostModel::getNumAnswers($post_id);
        
        // Оповещение автору поста, что появился ответ
        // Добавить
        
        redirect($url_answer); 
    }
    
   // Покажем форму редактирования
	public function editAnswerPage()
	{
        $answer_id  = \Request::getInt('answer_id');
        $post_id    = \Request::getInt('post_id');
        $uid        = Base::getUid();
        
        $answer = AnswerModel::getAnswerOne($answer_id);

        // Проверка доступа 
        Base::accessСheck($answer, 'answer', $uid); 

        $post = PostModel::postId($post_id);
        Base::PageError404($post);

        Request::getResources()->addBottomStyles('/assets/md/editor.css');  
        Request::getResources()->addBottomScript('/assets/md/Markdown.Converter.js'); 
        Request::getResources()->addBottomScript('/assets/md/Markdown.Sanitizer.js');
        Request::getResources()->addBottomScript('/assets/md/Markdown.Editor.js');
        Request::getResources()->addBottomScript('/assets/md/editor.js');
        
        $data = [
            'h1'                => lang('Edit answer'),
            'answer_id'         => $answer_id,
            'post_id'           => $post_id,
            'user_id'           => $uid['id'],
            'answer_content'    => $answer['answer_content'],
            'sheet'             => 'edit-answers', 
            'meta_title'        => lang('All answers'),
            'meta_desc'         => lang('answers-desc'),  
        ]; 
        
        return view(PR_VIEW_DIR . '/answer/edit-form-answer', ['data' => $data, 'uid' => $uid, 'post' => $post]);
    }

    // Редактируем ответ
    public function editAnswer()
    {
       
        $answer_id      = \Request::getPostInt('answer_id');
        $post_id        = \Request::getPostInt('post_id');
        $answer_content = $_POST['answer']; // не фильтруем

        $post = PostModel::postId($post_id);

        // Получим относительный url поста для возрата
        $url = '/post/' . $post['post_id'] . '/' . $post['post_slug'];
        
        // id того, кто редактирует
        $uid        = Base::getUid();
        $user_id    = $uid['id'];
        
        $answer = AnswerModel::getAnswerOne($answer_id);
        
        // Проверка доступа 
        Base::accessСheck($answer, 'answer', $uid); 
        
        Base::Limits($answer_content, lang('Bodies'), '6', '5000', '/' . $url);

        // Редактируем комментарий
        AnswerModel::AnswerEdit($answer_id, $answer_content);
        
        redirect('/' . $url . '#answer_' . $answer_id); 
	}
    
    // Ответы участника
    public function userAnswers()
    {
        $login = \Request::get('login');
       
        // Если нет такого пользователя 
        $user   = UserModel::getUserLogin($login);
        Base::PageError404($user);
        
        $answ  = AnswerModel::userAnswers($login); 
        
        $result = Array();
        foreach ($answ as $ind => $row) {
            $row['content'] = Base::text($row['answer_content'], 'md');
            $row['date']    = lang_date($row['answer_date']);
            $result[$ind]   = $row;
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'            =>  lang('Answers-n') .' '. $login,
            'canonical'     => Config::get(Config::PARAM_URL) . '/u/' . $login . '/answers',
            'sheet'         => 'user-answers', 
            'meta_title'    => lang('Answers') .' '. $login .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => 'Ответы  учасника сообщества ' . $login .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];
        
        return view(PR_VIEW_DIR . '/answer/user-answer', ['data' => $data, 'uid' => $uid, 'answers' => $result]);
    }

    // Удаление комментария
    public function deletAnswer()
    {
        // Доступ только персоналу
        $uid        = Base::getUid();
        if ($uid['trust_level'] != 5) {
            return false;
        }
        
        $answer_id = \Request::getPostInt('answer_id');

        AnswerModel::AnswerDel($answer_id);
        
        return false;
    }
    
    // Помещаем комментарий в закладки
    public function addAnswerFavorite()
    {
        
        $uid        = Base::getUid();
        $answer_id  = \Request::getPostInt('answer_id');
        $answer     = AnswerModel::getAnswerOne($answer_id); 
        
        if(!$answer) {
            redirect('/');
        }
        
        AnswerModel::setAnswerFavorite($answer_id, $uid['id']);
       
        return true;
    } 
}