<?php

namespace App\Controllers\User;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{PostModel, FolderModel};
use Tpl, Meta, Html, UserData;

class UserController extends MainController
{
    private $user;

    protected $limit = 40;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // All users
    // Все пользователи
    function index($sheet, $type)
    {
        $pageNumber = Tpl::pageNumber();

        $usersCount = UserModel::getUsersAllCount();
        $users      = UserModel::getUsersAll($pageNumber, $this->limit, $this->user['id'], $sheet);
        Html::pageError404($users);

        $m = [
            'og'    => false,
            'url'   => getUrlByName($sheet),
        ];

        return Tpl::agRender(
            '/user/all',
            [
                'meta'  => Meta::get(__($type . 's'), __($sheet . '.desc'), $m),
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($usersCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'users'         => $users
                ]
            ]
        );
    }

    // Member bookmarks page
    // Страница закладок участника
    function favorites()
    {
        $favorites = UserModel::userFavorite($this->user['id']);

        $result = [];
        foreach ($favorites as $ind => $row) {
            if ($row['action_type'] == 'post') {
                $row['answer_post_id'] = $row['post_id'];
            }

            $row['post']    = PostModel::getPost($row['answer_post_id'], 'id', $this->user);
            $result[$ind]   = $row;
        }

        return Tpl::agRender(
            '/user/favorite/all',
            [
                'meta'  => Meta::get(__('favorites')),
                'data'  => [
                    'sheet'     => 'favorites',
                    'type'      => 'favorites',
                    'favorites' => $result,
                    'tags'      => FolderModel::get('favorite', $this->user['id']),
                ]
            ]
        );
    }


    // Participant's folder page (for bookmarks)
    // Страница папок участника (для закладок)
    function folders()
    {
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        $folders = FolderModel::get('favorite', $this->user['id']);

        return Tpl::agRender(
            '/user/favorite/folders',
            [
                'meta'  => Meta::get(__('folders')),
                'data'  => [
                    'sheet'     => 'folders',
                    'type'      => 'folders',
                    'folders'   => $folders,
                    'count'     => count($folders),
                ]
            ]
        );
    }

    public function foldersFavorite()
    {
        return Tpl::agRender(
            '/user/favorite/all',
            [
                'meta'  => Meta::get(__('favorites')),
                'data'  => [
                    'sheet'     => 'favorites',
                    'type'      => 'favorites',
                    'favorites' => UserModel::userFavorite($this->user['id'], Request::getInt('id'))
                ]
            ]
        );
    }

    // Member Draft Page
    // Страница черновиков участника
    function drafts()
    {
        return Tpl::agRender(
            '/user/draft',
            [
                'meta'  => Meta::get(__('drafts')),
                'data'  => [
                    'drafts'    => UserModel::userDraftPosts($this->user['id']),
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
        return Tpl::agRender(
            '/user/favorite/subscribed',
            [
                'meta'  => Meta::get(__('subscribed')),
                'data'  => [
                    'h1'    => __('subscribed') . ' ' . $this->user['login'],
                    'sheet' => 'subscribed',
                    'type'  => 'favorites',
                    'posts' => PostModel::getFocusPostsListUser($this->user['id'])
                ]
            ]
        );
    }

    // User card when hovering over the avatar in the post feed
    public function card()
    {
        $user_id    = Request::getPostInt('user_id');
        $user       = UserModel::getUser($user_id, 'id');
        $post       = PostModel::getPost($user['my_post'], 'id', $this->user);
        $badges     = BadgeModel::getBadgeUserAll($user_id);

        Tpl::agIncludeTemplate(
            '/content/user/card',
            [
                'user'      => $user,
                'post'      => $post,
                'badges'    => $badges
            ]
        );
    }
}
