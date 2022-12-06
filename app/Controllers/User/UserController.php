<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\User\UserModel;
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
        notEmptyOrView404($users);

        $m = [
            'og'    => false,
            'url'   => url($sheet),
        ];

        return $this->render(
            '/user/all',
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
            '/user/favorite/read-subscribed',
            [
                'meta'  => Meta::get(__('app.subscribed')),
                'data'  => [
                    'h1'    => __('app.subscribed') . ' ' . $this->user['login'],
                    'sheet' => 'subscribed',
                    'type'  => 'favorites',
                    'posts' => PostModel::getPostsListUser($this->user['id'], 'subscribed')
                ]
            ]
        );
    }

    // User preferences page
    // Страница предпочтений пользователя
    public function read()
    {
        return $this->render(
            '/user/favorite/read-subscribed',
            [
                'meta'  => Meta::get(__('app.i_read')),
                'data'  => [
                    'h1'    => __('app.i_read') . ' ' . $this->user['login'],
                    'sheet' => 'read',
                    'type'  => 'favorites',
                    'posts' => PostModel::getPostsListUser($this->user['id'], 'read')
                ]
            ]
        );
    }
}
