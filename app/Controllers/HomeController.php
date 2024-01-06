<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Services\Meta\Home;
use App\Models\User\PreferencesModel;
use App\Models\HomeModel;

class HomeController extends Controller
{
    public function index($sheet)
    {
        $subscription = HomeModel::getSubscription($this->user['id']);

        // Topics signed by the participant. If a guest, then default.    
        // Темы на которые подписан участник. Если гость, то дефолтные.
        $topics = \App\Models\FacetModel::advice($subscription);

        return $this->render(
            '/home',
            [
                'meta'  => Home::metadata($sheet),
                'data'  => [
                    'pagesCount'        => HomeModel::feedCount($sheet),
                    'pNum'              => $this->pageNumber,
                    'sheet'             => $sheet,
                    'topics'            => $topics,
                    'type'              => 'main',
                    'latest_comments'    => HomeModel::latestComments(),
                    'facets'               => PreferencesModel::getMenu(),
                    'posts'             => HomeModel::feed($this->pageNumber, $sheet),
                    'items'             => HomeModel::latestItems(),
                ],
            ],
        );
    }

    // Infinite scroll
    // Бесконечный скролл
    public function scroll()
    {
        $type    = Request::get('type') == 'all' ? 'all' : 'main.feed';

        $this->insert(
            '/content/post/type-post',
            [
                'data'  => [
                    'pages' => $this->pageNumber,
                    'sheet' => 'main.feed',
                    'posts' => HomeModel::feed($this->pageNumber, $type),

                ]
            ]
        );
    }
}
