<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\{HomeModel, IgnoredModel};
use Meta;

class HomeController extends Controller
{
    protected $limit = 15;

    public function index($sheet)
    {
        $ignored        = IgnoredModel::getIgnoredUsers(50);
        $latest_answers = HomeModel::latestAnswers($this->user);
        $topics_user    = HomeModel::subscription($this->user['id']);
        $pagesCount     = HomeModel::feedCount($topics_user, $ignored, $this->user, $sheet);
        $posts          = HomeModel::feed($this->pageNumber, $this->limit, $topics_user, $ignored, $this->user, $sheet);
        $items          = HomeModel::latestItems(3); // (LIMIT)

        // Topics signed by the participant. If a guest, then default.    
        // Темы на которые подписан участник. Если гость, то дефолтные.
        $topics = \App\Models\FacetModel::advice($this->user['id']);

        $m = [
            'main'      => 'main',
            'og'        => true,
            'imgurl'    => config('meta.img_path'),
            'url'       => self::canonical($sheet),
        ];

        return $this->render(
            '/home',
            [
                'meta'  => Meta::get(config('meta.' . $sheet . '_title'), config('meta.' . $sheet . '_desc'), $m),
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

    public static function canonical($url)
    {
        switch ($url) {
            case 'questions':
                $url    = '/questions';
                break;
            case 'posts':
                $url    = '/posts';
                break;
            case 'top':
                $url    = '/top';
                break;
            default:
                $url    = '/';
        }

        return $url;
    }

    // Infinite scroll
    // Бесконечный скролл
    public function scroll()
    {
        $type = Request::get('type') == 'all' ? 'all' : 'main.feed';
        
        $ignored        = IgnoredModel::getIgnoredUsers(50);
        $topics_user    = HomeModel::subscription($this->user['id']);
        $posts          = HomeModel::feed($this->pageNumber, $this->limit, $topics_user, $ignored, $this->user, $type);

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
