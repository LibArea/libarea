<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\HomeModel;
use Content, Config, Tpl, UserData;

class HomeController extends MainController
{
    protected $limit = 25;

    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        if ($sheet == 'main.deleted' && $this->user['trust_level'] != 10) {
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

        $meta_title = Config::get('meta.' . $sheet . '.title');
        $meta_desc  = Config::get('meta.' . $sheet . '.desc');

        $m = [
            'og'         => true,
            'twitter'    => false,
            'imgurl'     => '/assets/images/agouti-max.png',
            'url'        => $sheet == 'top' ? '/top' : '/',
        ];

        return Tpl::agRender(
            '/home',
            [
                'meta'  => meta($m, $meta_title ?? Config::get('meta.title'), $meta_desc ?? Config::get('meta.desc')),
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
}
