<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\User\UserModel;
use App\Models\{PostModel, FolderModel};
use Meta, Html;

class UserController extends Controller
{
    protected $limit = 35;

    public function all(): void
    {
        $this->callIndex('all');
    }

    public function new(): void
    {
        $this->callIndex('new');
    }

    /**
     * All users
     * Все пользователи
     *
     * @param [type] $sheet
     * @return void
     */
    function callIndex($sheet)
    {
        $usersCount = UserModel::getUsersAllCount();
        $users      = UserModel::getUsersAll(Html::pageNumber(), $this->limit, $sheet);
        notEmptyOrView404($users);

        $m = [
            'og'    => false,
            'url'   => url('users.' . $sheet),
        ];

        render(
            '/user/all',
            [
                'meta'  => Meta::get(__('meta.' . $sheet . '_users'), __('meta.' . $sheet . '_users_desc'), $m),
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => 'all_users',
                    'pagesCount'    => ceil($usersCount / $this->limit),
                    'pNum'          => Html::pageNumber(),
                    'users'         => $users
                ]
            ]
        );
    }

    /**
     * Member bookmarks page
     * Страница закладок участника
     *
     * @return void
     */
    function favorites()
    {
        $favorites = UserModel::userFavorite();

        $result = [];
        foreach ($favorites as $ind => $row) {
            if ($row['action_type'] == 'post') {
                $row['comment_post_id'] = $row['post_id'];
            }

            $row['post']    = PostModel::getPost($row['comment_post_id'], 'id');
            $result[$ind]   = $row;
        }

        render(
            '/user/favorite/all',
            [
                'meta'  => Meta::get(__('app.favorites')),
                'data'  => [
                    'sheet'     => 'favorites',
                    'type'      => 'favorites',
                    'favorites' => $result,
                    'tags'      => FolderModel::get('favorite', $this->container->user()->id()),
                ]
            ]
        );
    }

    /**
     * Participant's folder page (for bookmarks)
     * Страница папок участника (для закладок)
     *
     * @return void
     */
    function folders()
    {
        /** @toDo здесь возможно, ошибка, так как у get() только один параметр */
        $folders = FolderModel::get('favorite', $this->container->user()->id());

        render(
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

    /**
     * Undocumented function
     *
     * @return void
     */
    public function foldersFavorite()
    {
        render(
            '/user/favorite/all',
            [
                'meta'  => Meta::get(__('app.favorites')),
                'data'  => [
                    'sheet'     => 'favorites',
                    'type'      => 'favorites',
                    'favorites' => UserModel::userFavorite(Request::param('id')->asInt())
                ]
            ]
        );
    }

    /**
     * Member Draft Page
     * Страница черновиков участника
     *
     * @return void
     */
    function drafts()
    {
        render(
            '/user/draft',
            [
                'meta'  => Meta::get(__('app.drafts')),
                'data'  => [
                    'drafts'    => UserModel::userDraftPosts(),
                    'sheet'     => 'drafts',
                    'type'      => 'drafts',
                ]
            ]
        );
    }

    /**
     * User preferences page
     * Страница предпочтений пользователя
     *
     * @return void
     */
    public function subscribed()
    {
        render(
            '/user/favorite/read-subscribed',
            [
                'meta'  => Meta::get(__('app.subscribed')),
                'data'  => [
                    'h1'    => __('app.subscribed') . ' ' . $this->container->user()->login(),
                    'sheet' => 'subscribed',
                    'type'  => 'favorites',
                    'posts' => PostModel::getPostsListUser('subscribed')
                ]
            ]
        );
    }

    /**
     * User preferences page
     * Страница предпочтений пользователя
     *
     * @return void
     */
    public function read()
    {
        render(
            '/user/favorite/read-subscribed',
            [
                'meta'  => Meta::get(__('app.i_read')),
                'data'  => [
                    'h1'    => __('app.i_read') . ' ' . $this->container->user()->login(),
                    'sheet' => 'read',
                    'type'  => 'favorites',
                    'posts' => PostModel::getPostsListUser('read')
                ]
            ]
        );
    }
}
