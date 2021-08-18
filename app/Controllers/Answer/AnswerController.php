<?php

namespace App\Controllers\Answer;

use Hleb\Constructor\Handlers\Request;
use App\Models\{UserModel, AnswerModel};
use Lori\{Content, Config, Base};

class AnswerController extends \MainController
{
    // Все ответы
    public function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = AnswerModel::getAnswersAllCount();
        $answ       = AnswerModel::getAnswersAll($page, $limit, $uid);

        $result = array();
        foreach ($answ  as $ind => $row) {
            $row['answer_content']  = Content::text($row['answer_content'], 'text');
            $row['date']            = lang_date($row['answer_date']);
            $result[$ind]           = $row;
        }

        $num = ' | ';
        if ($page > 1) {
            $num = sprintf(lang('page-number'), $page) . ' | ';
        }

        $data = [
            'h1'            => lang('All answers'),
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'canonical'     => Config::get(Config::PARAM_URL) . '/answers',
            'sheet'         => 'answers',
            'meta_title'    => lang('All answers') . $num . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('answers-desc') . $num . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/answer/answers', ['data' => $data, 'uid' => $uid, 'answers' => $result]);
    }

    // Ответы участника
    public function userAnswers()
    {
        $login  = Request::get('login');
        $user   = UserModel::getUser($login, 'slug');

        Base::PageError404($user);

        $answers  = AnswerModel::userAnswers($login);

        $result = array();
        foreach ($answers as $ind => $row) {
            $row['content'] = Content::text($row['answer_content'], 'text');
            $row['date']    = lang_date($row['answer_date']);
            $result[$ind]   = $row;
        }

        $uid  = Base::getUid();
        $data = [
            'h1'            =>  lang('Answers-n') . ' ' . $login,
            'canonical'     => Config::get(Config::PARAM_URL) . '/u/' . $login . '/answers',
            'sheet'         => 'user-answers',
            'meta_title'    => lang('Answers') . ' ' . $login . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => 'Ответы  учасника сообщества ' . $login . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/answer/answer-user', ['data' => $data, 'uid' => $uid, 'answers' => $result]);
    }

    // Помещаем комментарий в закладки
    public function addAnswerFavorite()
    {
        $uid        = Base::getUid();
        $answer_id  = Request::getPostInt('answer_id');
        $answer     = AnswerModel::getAnswerId($answer_id);

        Base::PageRedirection($answer);

        AnswerModel::setAnswerFavorite($answer_id, $uid['user_id']);

        return true;
    }
}
