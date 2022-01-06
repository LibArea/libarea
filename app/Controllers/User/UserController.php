<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Middleware\Before\UserData;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{NotificationsModel, PostModel};
use Content, Translate;

class UserController extends MainController
{
    private $uid;

    protected $limit = 42;

    public function __construct()
    {
        $this->uid  = UserData::getUid();
    }

    // All users
    // Все пользователи
    function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $usersCount = UserModel::getUsersAllCount();
        $users      = UserModel::getUsersAll($page, $this->limit, $this->uid['user_id']);
        pageError404($users);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName($sheet),
        ];

        return agRender(
            '/user/all',
            [
                'meta'  => meta($m, Translate::get($type . 's'), Translate::get($sheet . '.desc')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($usersCount / $this->limit),
                    'pNum'          => $page,
                    'users'         => $users
                ]
            ]
        );
    }

    // Member bookmarks page
    // Страница закладок участника
    function favorites()
    {
        $favorites = UserModel::userFavorite($this->uid['user_id']);

        $result = [];
        foreach ($favorites as $ind => $row) {

            if ($row['favorite_type'] == 1) {
                $row['answer_post_id'] = $row['post_id'];
            }

            $row['answer_content']  = Content::text($row['answer_content'], 'text');
            $row['post']            = PostModel::getPost($row['answer_post_id'], 'id', $this->uid);
            $result[$ind]           = $row;
        }

        return agRender(
            '/user/favorite',
            [
                'meta'  => meta($m = [], Translate::get('favorites')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'     => 'favorites',
                    'type'      => 'favorites',
                    'favorites' => $result
                ]
            ]
        );
    }

    // Member Draft Page
    // Страница черновиков участника
    function drafts()
    {
        return agRender(
            '/user/draft',
            [
                'meta'  => meta($m = [], Translate::get('drafts')),
                'uid'   => $this->uid,
                'data'  => [
                    'drafts'    => UserModel::userDraftPosts($this->uid['user_id']),
                    'sheet'     => 'drafts',
                    'type'      => 'drafts',
                ]
            ]
        );
    }

    // User preferences page
    // Страница предпочтений пользователя
    public function subscribed()
    {
        $focus_posts = NotificationsModel::getFocusPostsListUser($this->uid['user_id']);

        $result = [];
        foreach ($focus_posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        return agRender(
            '/user/subscribed',
            [
                'meta'  => meta($m = [], Translate::get('subscribed')),
                'uid'   => $this->uid,
                'data'  => [
                    'h1'    => Translate::get('subscribed') . ' ' . $this->uid['user_login'],
                    'sheet' => 'subscribed',
                    'type'  => 'favorites',
                    'posts' => $result
                ]
            ]
        );
    }

    public function card()
    {
        $user_id    = Request::getPostInt('user_id');
        $user       = UserModel::getUser($user_id, 'id');
        $post       = PostModel::getPost($user['user_my_post'], 'id', $this->uid);
        $badges     = BadgeModel::getBadgeUserAll($user_id);

        agIncludeTemplate(
            '/content/user/card',
            [
                'user' => $user,
                'uid' => $this->uid,
                'post' => $post,
                'badges' => $badges
            ]
        );
    }
}
