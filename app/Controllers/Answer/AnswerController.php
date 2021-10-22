<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\AnswerModel;
use Content, Base;

class AnswerController extends MainController
{
    // Все ответы
    public function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = AnswerModel::getAnswersAllCount('user');
        $answ       = AnswerModel::getAnswersAll($page, $limit, $uid, 'user');

        $result = array();
        foreach ($answ  as $ind => $row) {
            $row['answer_content']  = Content::text($row['answer_content'], 'text');
            $row['date']            = lang_date($row['answer_date']);
            $result[$ind]           = $row;
        }

        $m = [
           'og'         => false,
           'twitter'    => false,
           'imgurl'     => false,
           'url'        => getUrlByName('answers'),
        ];
        $meta = meta($m, lang('all answers'), lang('answers-desc'));

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

        $m = [
           'og'         => false,
           'twitter'    => false,
           'imgurl'     => false,
           'url'        => getUrlByName('answers.user', ['login' => $login]),
        ];
        $meta = meta($m, lang('answers') . ' ' . $login, lang('responses from community members') . ' ' . $login);

        $data = [
            'sheet'         => 'user-answers',
            'answers'       => $result,
            'user_login'    => $user['user_login'],
        ];

        return view('/answer/answer-user', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
