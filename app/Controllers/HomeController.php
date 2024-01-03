<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Services\Meta\Home;
use App\Models\HomeModel;
use UserData;

class HomeController extends Controller
{
    public function index($sheet)
    {
        $subscription = UserData::getUserSubscription();

        // Topics signed by the participant. If a guest, then default.    
        // Темы на которые подписан участник. Если гость, то дефолтные.
        $topics = \App\Models\FacetModel::advice($subscription);

        return $this->render(
            '/home',
            [
                'meta'  => Home::metadata($sheet),
                'data'  => [
                    'pagesCount'        => HomeModel::feedCount($sheet, $subscription),
                    'pNum'              => $this->pageNumber,
                    'sheet'             => $sheet,
                    'topics'            => $topics,
                    'type'              => 'main',
                    'latest_comments'	=> HomeModel::latestComments(),
                    'topics_user'       => $subscription,
                    'posts'             => HomeModel::feed($this->pageNumber, $sheet, $subscription),
                    'items'             => HomeModel::latestItems(),
                ],
            ],
        );
    }

    // Infinite scroll
    // Бесконечный скролл
    public function scroll()
    {
        $type	= Request::get('type') == 'all' ? 'all' : 'main.feed';
		
		$subscription = UserData::getUserSubscription();

        $posts	= HomeModel::feed($this->pageNumber, $type, $subscription);

        $this->insert(
            '/content/post/type-post',
            [
                'data'  => [
                    'pages' => $this->pageNumber,
                    'sheet' => 'main.feed',
                    'posts' => $posts, // $posts = empty($posts) ? 'null' : $posts;

                ]
            ]
        );
    }
}
