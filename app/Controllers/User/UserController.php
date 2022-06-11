<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{PostModel, FolderModel};
use Meta;

class UserController extends Controller
{
    protected $limit = 35;

    // All users
    // Все пользователи
    function index($sheet)
    {
        $usersCount = UserModel::getUsersAllCount();
        $users      = UserModel::getUsersAll($this->pageNumber, $this->limit, $this->user['id'], $sheet);
        self::error404($users);

        $m = [
            'og'    => false,
            'url'   => url($sheet),
        ];

        return $this->render(
            '/user/all',
            'base',
            [
                'meta'  => Meta::get(__('meta.' . $sheet . '_users'), __('meta.' . $sheet . 'users_desc'), $m),
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => 'users',
                    'pagesCount'    => ceil($usersCount / $this->limit),
                    'pNum'          => $this->pageNumber,
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

        return $this->render(
            '/user/favorite/all',
            'base',
            [
                'meta'  => Meta::get(__('app.favorites')),
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

        return $this->render(
            '/user/favorite/folders',
            'base',
            [
                'meta'  => Meta::get(__('app.folders')),
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
        return $this->render(
            '/user/favorite/all',
            'base',
            [
                'meta'  => Meta::get(__('app.favorites')),
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
        return $this->render(
            '/user/draft',
            'base',
            [
                'meta'  => Meta::get(__('app.drafts')),
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
        return $this->render(
            '/user/favorite/subscribed',
            'base',
            [
                'meta'  => Meta::get(__('app.subscribed')),
                'data'  => [
                    'h1'    => __('app.subscribed') . ' ' . $this->user['login'],
                    'sheet' => 'subscribed',
                    'type'  => 'favorites',
                    'posts' => PostModel::getFocusPostsListUser($this->user['id'])
                ]
            ]
        );
    }
}
