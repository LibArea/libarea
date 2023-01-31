<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Services\Meta\Home;
use App\Models\HomeModel;

class HomeController extends Controller
{
    protected $limit = 15;

    public function index($sheet)
    {
        $latest_answers = HomeModel::latestAnswers();
        $topics_user    = HomeModel::subscription();
        $pagesCount     = HomeModel::feedCount($topics_user, $sheet);
        $posts          = HomeModel::feed($this->pageNumber, $this->limit, $topics_user, $sheet);
        $items          = HomeModel::latestItems(3); // (LIMIT)

        // Topics signed by the participant. If a guest, then default.    
        // Темы на которые подписан участник. Если гость, то дефолтные.
        $topics = \App\Models\FacetModel::advice();

        return $this->render(
            '/home',
            [
                'meta'  => Home::metadata($sheet),
                'data'  => [
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $this->pageNumber,
                    'sheet'             => $sheet,
                    'type'              => 'main',
                    'latest_answers'    => $latest_answers,
                    'topics_user'       => $topics_user,
                    'posts'             => $posts,
                    'topics'            => $topics,
                    'items'             => $items,
                ],
            ],
        );
    }

    // Infinite scroll
    // Бесконечный скролл
    public function scroll()
    {
        $type = Request::get('type') == 'all' ? 'all' : 'main.feed';

        $topics_user    = HomeModel::subscription();
        $posts          = HomeModel::feed($this->pageNumber, $this->limit, $topics_user, $type);

        $this->insert(
            '/content/post/type-post',
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
