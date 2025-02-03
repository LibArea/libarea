<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\{HomeModel, CommentModel};
use Meta, Html;

class HomeController extends Controller
{
    public function feed(): void
    {
        $this->callIndex('feed');
    }

    public function questions(): void
    {
        $this->callIndex('questions');
    }

    public function posts(): void
    {
        $this->callIndex('posts');
    }

    public function all(): void
    {
        $this->callIndex('all');
    }

    public function deleted(): void
    {
        $this->callIndex('deleted');
    }

    /**
     * The central page of the site
     * Центральная страница сайта
     *
     * @param string $sheet
     * @return void
     */
    private function callIndex(string $sheet)
    {
        $subscription = HomeModel::getSubscription();

        // Topics signed by the participant. If a guest, then default.    
        // Темы на которые подписан участник. Если гость, то дефолтные.
        $topics = \App\Models\FacetModel::advice($subscription);

        $signed = [];
        foreach ($subscription as $ind => $row) {
            $signed[$ind] = $row['facet_id'];
        }

        render(
            'home',
            [
                'meta'  => Meta::home($sheet),
                'data'  => [
                    'pagesCount'        => HomeModel::feedCount($signed, $sheet),
                    'pNum'              => Html::pageNumber(),
                    'sheet'             => $sheet,
                    'topics'            => $topics,
                    'type'              => 'main',
                    'latest_comments'   => CommentModel::latestComments(4),
                    'posts'             => HomeModel::feed($signed, Html::pageNumber(), $sheet),
                    'items'             => HomeModel::latestItems() ?? [],
                ],
            ],
        );
    }

    /**
     * Infinite scroll
     * Бесконечный скролл
     *
     * @return void
     */
    public function scroll(): void
    {
        $type = Request::param('type')->value() == 'all' ? 'all' : 'main.feed';

        $subscription = HomeModel::getSubscription();

        $signed = [];
        foreach ($subscription as $ind => $row) {
            $signed[$ind] = $row['facet_id'];
        }

        insert(
            '/content/post/type-post',
            [
                'data'  => [
                    'pages' => Html::pageNumber(),
                    'sheet' => 'main.feed',
                    'posts' => HomeModel::feed($signed, Html::pageNumber(), $type),
                ]
            ]
        );
    }
}
