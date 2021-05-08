<?php

namespace App\Controllers;
use App\Models\CommentModel;
use App\Models\UserModel;
use App\Models\AnswerModel;
use App\Models\PostModel;
use App\Models\VotesCommentModel;
use App\Models\VotesAnswerModel;
use App\Models\NotificationsModel;
use App\Models\FlowModel;
use Hleb\Constructor\Handlers\Request;
use Lori\Base;
use Parsedown;

class AnswerController extends \MainController
{
    // Все ответы
    public function index()
    {
        $pg = \Request::getInt('page'); 
        $page = (!$pg) ? 1 : $pg;
        
        $uid        = Base::getUid();
        $user_id    = $uid['id'];
         
        $pagesCount = AnswerModel::getAnswersAllCount();  
        $answ       = AnswerModel::getAnswersAll($page, $user_id);
 
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        
        $result = Array();
        foreach($answ  as $ind => $row){
            $row['answers_content'] = $Parsedown->text($row['answer_content']);
            $row['date']            = Base::ru_date($row['answer_date']);
            // N+1 - перенести в запрос
            $row['answ_vote_status'] = VotesAnswerModel::getVoteStatus($row['answer_id'], $user_id);
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
            'canonical'     => '/answers', 
        ];

        // title, description
        Base::Meta(lang('All answers'), lang('answers-desc'), $other = false);
 
        return view(PR_VIEW_DIR . '/answer/answ-all', ['data' => $data, 'uid' => $uid, 'answers' => $result]);
    }

    // Добавление ответа
    public function createAnswer()
    {
        // Получим относительный url поста для возрата (упростить)
        $url        = str_replace('//', '', $_SERVER['HTTP_REFERER']);
        $return_url = substr($url, strpos($url, '/') + 1);
        
        $answer = \Request::getPost('answer');
        
        if (Base::getStrlen($answer) < 6 || Base::getStrlen($answer) > 1024)
        {
            Base::addMsg('Длина ответа должна быть от 6 до 1000 знаков', 'error');
            redirect('/' . $return_url);
            return true;
        }

        $post_id    = \Request::getPostInt('post_id');   // в каком посту ответ
        $answer     = $_POST['answer'];                 // не фильтруем
        $ip         = \Request::getRemoteAddress();      // ip отвечающего 
        
        // id того, кто отвечает
        $account   = \Request::getSession('account');
        $my_id     = $account['user_id'];
        
        // Ограничим частоту добавления
        // Добавить условие TL
        $num_answ =  CommentModel::getCommentSpeed($my_id);
        if(count($num_answ) > 35) {
            Base::addMsg('Вы исчерпали лимит ответов (35) на сегодня', 'error');
            redirect('/');
        }
        
        // Записываем ответ
        $last_id = AnswerModel::answerAdd($post_id, $ip, $answer, $my_id);
         
        // Адрес ответа 
        $url = $return_url . '#answ_' . $last_id; 
         
        // Добавим в чат и поток
        $data_flow = [
            'flow_action_id'    => 3, // add ответы
            'flow_content'      => $answer, // не фильтруем
            'flow_user_id'      => $my_id,
            'flow_pubdate'      => date("Y-m-d H:i:s"),
            'flow_url'          => $url,
            'flow_target_id'    => $last_id,
            'flow_about'        => lang('add_answer'),            
            'flow_space_id'     => 0,
            'flow_tl'           => 0,
            'flow_ip'           => $ip, 
        ];
        FlowModel::FlowAdd($data_flow);        
         
        // Пересчитываем количество ответов для поста + 1
        PostModel::getNumAnswers($post_id);
        
        // Оповещение автору поста, что появился ответ
        // Добавить
        
        redirect('/' . $return_url . '#answ_' . $last_id); 
    }
    
   // Покажем форму редактирования
	public function editFormAnswer()
	{
        $answ_id    = \Request::getInt('answ_id');
        $post_id    = \Request::getInt('post_id');
        $uid        = Base::getUid();
        
 
        $answ = AnswerModel::getAnswerOne($answ_id);

        // Проверим автора комментария и админа
        if($uid['id'] != $answ['answer_user_id'] && $uid['trust_level'] != 5) {
            return true; 
        }

        $post = PostModel::getPostId($post_id);
        
        if (!$post) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $data = [
            'h1'                => lang('Edit answer'),
            'canonical'         => '/***',
            'answ_id'           => $answ_id,
            'post_id'           => $post_id,
            'user_id'           => $uid['id'],
            'answer_content'    => $answ['answer_content'],
        ]; 
        
        Base::Meta(lang('Edit answer'), lang('Edit answer'), $other = false);
         
        Request::getResources()->addBottomStyles('/assets/js/editor/editor.css');
        Request::getResources()->addBottomScript('/assets/js/editor/editor.js');
        Request::getResources()->addBottomScript('/assets/js/editor/marked.js');
        Request::getResources()->addBottomScript('/assets/js/editor.js');
        
        return view(PR_VIEW_DIR . '/answer/answ-edit-form', ['data' => $data, 'uid' => $uid, 'post' => $post]);
    }

    // Редактируем ответ
    public function editAnswer()
    {
       
        $answ_id    = \Request::getPostInt('answ_id');
        $post_id    = \Request::getPostInt('post_id');
        $answer     = $_POST['answer']; // не фильтруем
        
      
     
        $post = PostModel::getPostId($post_id);

        // Получим относительный url поста для возрата
        $url = '/post/' . $post['post_id'] . '/' . $post['post_slug'];
        
        // id того, кто редактирует
        $uid        = Base::getUid();
        $user_id    = $uid['id'];
        
        $answ = AnswerModel::getAnswerOne($answ_id);
        
        // Проверим автора комментария и админа
        if(!$user_id == $answ['answer_user_id']) {
            return true; 
        }
        
        // $answer = 'test 33 test "><script>alert("cookie: "+document.cookie)</script>,';
        // Редактируем комментарий
        AnswerModel::AnswerEdit($answ_id, $answer);
        
        redirect('/' . $url . '#answ_' . $answ_id); 
	}
    
    // Ответы участника
    public function userAnswers()
    {
        $login = \Request::get('login');
       
        // Если нет такого пользователя 
        $user   = UserModel::getUserLogin($login);
        if(!$user) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        
        $answ  = AnswerModel::getUsersAnswers($login); 
        
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        
        $result = Array();
        foreach($answ as $ind => $row){
            $row['content'] = $Parsedown->text($row['answer_content']);
            $row['date']    = Base::ru_date($row['answer_date']);
            $result[$ind]   = $row;
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'        => 'Ответы ' . $login,
            'canonical' => '/u/' . $login . '/answers', 
        ];

        $meta_title = 'Ответы ' . $login;
        $meta_desc  = 'Ответы  учасника сообщества ' . $login . ' на сайте ';
        
        // title, description
        Base::Meta($meta_title, $meta_desc, $other = false);
        
        return view(PR_VIEW_DIR . '/answer/answ-user', ['data' => $data, 'uid' => $uid, 'answers' => $result]);
    }

    // Удаление комментария
    public function deletAnswer()
    {
        // Доступ только персоналу
        $uid        = Base::getUid();
        if ($uid['trust_level'] != 5) {
            return false;
        }
        
        $answ_id = \Request::getPostInt('answ_id');

        AnswerModel::AnswerDel($answ_id);
        
        return false;
    }
    
    // Помещаем комментарий в закладки
    public function addAnswerFavorite()
    {
        
        $uid        = Base::getUid();
        
        $answ_id = \Request::getPostInt('answ_id');
        $answ    = AnswerModel::getAnswerOne($answ_id); 
        
        if(!$answ) {
            redirect('/');
        }
        
        AnswerModel::setAnswerFavorite($answ_id, $uid['id']);
       
        return true;
    } 
}