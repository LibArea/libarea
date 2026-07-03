<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\User\{UserModel, BadgeModel};
use App\Models\{FacetModel, FeedModel, CommentModel, PublicationModel, IgnoredModel};
use Html, Meta;

use App\Traits\Views;

class ProfileController extends Controller
{
    use Views;

    protected int $limit = 15;

    /**
     * Member page (profile) 
     * Страница участника (профиль)
     */
    public function index(): void
    {
        $this->callIndex('profile');
    }

    /**
     * User posts
     * Посты пользователя
     */
    public function contents(): void
    {
        $this->callIndex('profile_posts');
    }

    /**
     * Общая логика для index() и contents()
     * 
     * @param string $sheet — тип страницы для Meta::profile()
     */
    private function callIndex(string $sheet): void
    {
        $profile = $this->profile();

        if (!$profile['about']) {
            $profile['about'] = __('app.riddle') . '...';
        }

        // ★ Ключевая оптимизация: считаем feedCount только 1 раз
        // для обложки страниц (sidebar нужен на обеих)
        $pagesCount = FeedModel::feedCount('profile.posts', $profile['id']);

        // Для главной страницы показываем полный список постов,
        // для contents — то же самое, но с другой мета-информацией
        $contents = ($sheet === 'profile') 
            ? FeedModel::feed(Html::pageNumber(), $this->limit, 'profile.posts', $profile['id'])
            : FeedModel::feed(Html::pageNumber(), $this->limit, 'profile.posts', $profile['id']);

        $view = ($sheet === 'profile') 
            ? '/user/profile/index' 
            : '/user/profile/contents';

        $data = [
            'pagesCount'    => (int) ceil($pagesCount / $this->limit),
            'pNum'          => Html::pageNumber(),
            'contents'      => $contents,
        ];

        if ($sheet === 'profile') {
            $data['participation'] = FacetModel::participation($profile['id']);
        }

        render(
            $view,
            [
                'meta' => Meta::profile($sheet, $profile),
                'data' => array_merge($this->sidebar($pagesCount, $profile), $data),
            ]
        );
    }

    /**
     * User comments
     * Комментарии пользователя
     */
    public function comments(): void
    {
        $profile       = $this->profile();
        $comments      = CommentModel::userComments(Html::pageNumber(), $profile['id'], $this->container->user()->id());
        $commentsCount = (int) CommentModel::userCommentsCount($profile['id']);

        render(
            '/user/profile/comments',
            [
                'meta' => Meta::profile('profile_comments', $profile),
                'data' => array_merge(
                    $this->sidebar($commentsCount, $profile),
                    ['comments' => $comments]
                ),
            ]
        );
    }

    /**
     * Sidebar data for profile pages
     * Данные сайдбара для страниц профиля
     */
    public function sidebar(int $pagesCount, array $profile): array
    {
        return [
            'pagesCount'  => $pagesCount,
            'profile'     => $profile,
            'type'        => 'profile',
            'delet_count' => UserModel::contentCount($profile['id'], 'remote'),
            'counts'      => UserModel::contentCount($profile['id'], 'active'),
            'topics'      => FacetModel::getFacetsTopicProfile($profile['id']),
            'blogs'       => FacetModel::getOwnerFacet($profile['id'], 'blog'),
            'badges'      => BadgeModel::getBadgeUserAll($profile['id']),
            'my_post'     => PublicationModel::getPost($profile['my_post'] ?? null, 'id'),
            'button_pm'   => $this->accessPm($profile['id']),
            'ignored'     => IgnoredModel::getUserIgnored($profile['id']),
        ];
    }

    /**
     * Get profile data
     * Получение данных профиля
     */
    public function profile(): array
    {
        $result = Request::param('login')->value();

        notEmptyOrView404($profile = UserModel::get($result, 'slug'));

        $this->setProfileView($profile['id']);

        return $profile;
    }

    /**
     * Check access to send personal messages
     * Проверка доступа к отправке личных сообщений
     */
    public function accessPm(int $for_user_id): bool
    {
        // We forbid sending to ourselves
        // Запрещаем отправку самому себе
        if ($this->container->user()->id() == $for_user_id) {
            return false;
        }

        // If the trust level is less than the established one
        // Если уровень доверия меньше установленного
        if ($this->container->user()->tl() < config('trust-levels', 'tl_add_pm')) {
            return false;
        }

        return true;
    }
}