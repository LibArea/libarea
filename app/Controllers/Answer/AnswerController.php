<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{UserModel, AnswerModel};
use Lori\{Content, Config, Base};

class AnswerController extends MainController
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

        $meta = [
            'canonical'     => Config::get(Config::PARAM_URL) . '/answers',
            'sheet'         => 'answers',
            'meta_title'    => lang('All answers') . $num . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('answers-desc') . $num . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'sheet'         => 'answers',
            'answers'       => $result,
        ];

        return view('/answer/answers', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
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
        $meta = [
            'canonical'     => Config::get(Config::PARAM_URL) . '/u/' . $login . '/answers',
            'sheet'         => 'user-answers',
            'meta_title'    => lang('Answers') . ' ' . $login . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => 'Ответы  учасника сообщества ' . $login . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'h1'            =>  lang('Answers-n') . ' ' . $login,
            'sheet'         => 'user-answers',
            'answers'       => $result
        ];

        return view('/answer/answer-user', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
