<?php

namespace App\Controllers\Answer;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\AnswerModel;
use Content, Base, Translate;

class AnswerController extends MainController
{
    private $uid;

    protected $limit = 25;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Все ответы
    public function index()
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = AnswerModel::getAnswersAllCount('user');
        $answ       = AnswerModel::getAnswersAll($page, $this->limit, $this->uid, 'user');

        $result = [];
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

        return render(
            '/answer/answers',
            [
                'meta'  => meta($m, Translate::get('all answers'), Translate::get('answers-desc')),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => 'answers',
                    'answers'       => $result,
                ]
            ]
        );
    }

    // Ответы участника
    public function userAnswers()
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $login  = Request::get('login');
        $user   = UserModel::getUser($login, 'slug');
        pageError404($user);

        $answers    = AnswerModel::userAnswers($page, $this->limit, $user['user_id'], $this->uid['user_id']);
        $pagesCount = AnswerModel::userAnswersCount($user['user_id']);

        $result = [];
        foreach ($answers as $ind => $row) {
            $row['content'] = Content::text($row['answer_content'], 'text');
            $row['date']    = lang_date($row['answer_date']);
            $result[$ind]   = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('answers.user', ['login' => $user['user_login']]),
        ];

        return render(
            '/answer/answer-user',
            [
                'meta'  => meta($m, Translate::get('answers') . ' ' . $user['user_login'], Translate::get('responses-members') . ' ' . $user['user_login']),
                'uid'   => $this->uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
                    'sheet'         => 'user-answers',
                    'type'          => Translate::get('answers') . ' ' . $user['user_login'],
                    'answers'       => $result,
                    'user_login'    => $user['user_login'],
                ]
            ]
        );
    }
}
