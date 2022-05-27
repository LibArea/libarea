<?php

namespace App\Controllers;

use App\Models\HomeModel;
use Meta;

class HomeController extends Controller
{
    protected $limit = 25;

    public function index($sheet)
    {
        $latest_answers = HomeModel::latestAnswers($this->user);
        $topics_user    = HomeModel::subscription($this->user['id']);
        $pagesCount     = HomeModel::feedCount($topics_user, $this->user, $sheet);
        $posts          = HomeModel::feed($this->pageNumber, $this->limit, $topics_user, $this->user, $sheet);

        // If guest, show default topics      
        // Если гость, то покажим темы по умолчанию
        $topics = \App\Models\FacetModel::advice($this->user['id']);

        $title = __('meta-main.' . $sheet . '_title', ['name' => config('meta.name')]);
        $description = __('meta-main.' . $sheet . '_desc', ['name' => config('meta.name')]);

        $m = [
            'main'      => 'main',
            'og'        => true,
            'imgurl'    => config('meta.img_path'),
            'url'       => $sheet == 'top' ? '/top' : '/',
        ];

        return $this->render(
            '/home',
            'base',
            [
                'meta'  => Meta::get($title, $description, $m),
                'data'  => [
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $this->pageNumber,
                    'sheet'             => $sheet,
                    'type'              => 'main',
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
        $topics_user    = HomeModel::subscription($this->user['id']);
        $posts          = HomeModel::feed($this->pageNumber, $this->limit, $topics_user, $this->user, 'main.feed');

        $this->insert(
            '/content/post/postscroll',
            [
                'data'  => [
                    'pages' => $this->pageNumber,
                    'sheet' => 'main.feed',
                    'posts' => $posts,

                ]
            ]
        );
    }
}
