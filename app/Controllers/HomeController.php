<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\HomeModel;
use Config, Tpl, UserData, Meta, Translate;

class HomeController extends MainController
{
    protected $limit = 5;

    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        if ($sheet == 'main.deleted' && $this->user['trust_level'] != UserData::REGISTERED_ADMIN) {
            redirect('/');
        }

        $latest_answers = HomeModel::latestAnswers($this->user);
        $topics_user    = HomeModel::subscription($this->user['id']);
        $pagesCount     = HomeModel::feedCount($topics_user, $this->user, $sheet);
        $posts          = HomeModel::feed($page, $this->limit, $topics_user, $this->user, $sheet);

        $topics = [];
        if (count($topics_user) == 0) {
            $topics = \App\Models\FacetModel::advice($this->user['id']);
        }

        $meta_title = sprintf(Translate::get($sheet . '.title'), Config::get('meta.name'));
        $meta_desc  = sprintf(Translate::get($sheet . '.desc'), Config::get('meta.name'));

        $m = [
            'og'         => true,
            'imgurl'     => Config::get('meta.img_path'),
            'url'        => $sheet == 'top' ? '/top' : '/',
        ];

        return Tpl::agRender(
            '/home',
            [
                'meta'  => Meta::get($m, $meta_title, $meta_desc, 'main'),
                'data'  => [
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $page,
                    'sheet'             => $sheet,
                    'type'              => $type,
                    'latest_answers'    => $latest_answers,
                    'topics_user'       => $topics_user,
                    'posts'             => $posts,
                    'topics'            => $topics,
                ],
            ],
        );
    }

    // Infinite scroll
    // Бесконечный скролл
    public function scroll()
    {
        $page           = Request::getInt('page');
        $page           = $page == 0 ? 1 : $page;
        $topics_user    = HomeModel::subscription($this->user['id']);
        $posts          = HomeModel::feed($page, $this->limit, $topics_user, $this->user, 'main.feed');

        Tpl::agIncludeTemplate(
            '/content/post/postscroll',
            [
                'user'  => $this->user,
                'data'  => [
                    'pages' => $page,
                    'sheet' => 'main.feed',
                    'posts' => $posts,

                ]
            ]
        );
    }
}
