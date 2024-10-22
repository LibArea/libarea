<?php

declare(strict_types=1);

namespace App\Controllers\Post;

use Hleb\Static\Request;
use Hleb\Base\Controller;

use App\Content\Сheck\PostPresence;
use App\Content\Сheck\FacetPresence;
use App\Models\{PostModel, CommentModel, SubscriptionModel, FeedModel};

use App\Traits\Views;
use App\Traits\Poll;
use App\Traits\LastDataModified;
use BuildTree, Html, Meta;

class PostController extends Controller
{
    use Views;
    use Poll;
    use LastDataModified;

    protected $limit = 25;

    public function post(): void
    {
        $this->callIndex('post');
    }

    public function page(): void
    {
        $this->callIndex('info.page');
    }

    /**
     * Full post
     * Полный пост
     *
     * @param [type] $type
     * @return void
     */
    public function callIndex(string $type)
    {
        $slug  = Request::param('slug')->asString();
        $id    = Request::param('id')->asPositiveInt();
        $sorting  = Request::get('sort')->value();

        $content = $this->presence($type, $id, $slug);

        $this->setPostView($content['post_id'], $this->container->user()->id());

        $content['modified'] = $content['post_date'] != $content['post_modified'] ? true : false;

        $facets = PostModel::getPostTopic($content['post_id'], 'topic');
        $blog   = PostModel::getPostTopic($content['post_id'], 'blog');

        // Show the draft only to the author
        if ($content['post_draft'] == 1 && $content['post_user_id'] != $this->container->user()->id()) {
            redirect('/');
        }

        // If the post type is a page, then depending on the conditions we make a redirect
        if ($content['post_type'] == 'page' && $id > 0) {
            redirect(url('facet.article', ['facet_slug' => 'info', 'slug' => $content['post_slug']]));
        }

        if ($content['post_related']) {
            $related_posts = PostModel::postRelated($content['post_related']);
        }

        // Sending Last-Modified and handling HTTP_IF_MODIFIED_SINCE
        $this->getDataModified($content['post_modified']);

        $comments = CommentModel::getCommentsPost($content['post_id'], $content['post_feature'], $sorting);

        if ($type === 'post') {
            render(
                '/post/post-view',
                [
                    'meta'  => Meta::post($content),
                    'data'  => [
                        'post'          => $content,
                        'comments'      => BuildTree::index(0, $comments),
                        'recommend'     => PostModel::postSimilars($content['post_id'], (int)$facets[0]['facet_id'] ?? null),
                        'related_posts' => $related_posts ?? '',
                        'post_signed'   => SubscriptionModel::getFocus($content['post_id'], 'post'),
                        'facet_signed'  => SubscriptionModel::getFocus($blog[0]['facet_id'] ?? null, 'facet'),
                        'facets'        => $facets,
                        'united'        => PostModel::getPostMerged($content['post_id']),
                        'blog'          => $blog ?? null,
                        'sorting'       => $sorting ?? null,
                        'sheet'         => 'article',
                        'type'          => 'post',
                        'poll'          => $this->getPoll($content['post_poll']),
                        'is_answer'     => CommentModel::isAnswerUser($content['post_id']),
                    ]
                ]
            );

        } else {

			$slug_facet = Request::param('facet_slug')->asString();
			$page  = FacetPresence::index($slug_facet, 'slug', 'section');

			render(
				'/post/page-view',
				[
					'meta'  => Meta::post($content),
					'data'  => [
						'sheet' => 'page',
						'type'  => $type,
						'page'  => $content,
						'facet' => [],
						'pages' => PostModel::recent($page['facet_id'], $content['post_id'])
					]
				]
			);
		}
    }

    public function presence(string $type, int $id, string|null $slug)
    {
        // Check id and get content data
        // Проверим id и получим данные контента
        if ($type === 'post') {
            $content = PostPresence::index($id);

            // If the post slug is different from the data in the database
            // Если slug поста отличается от данных в базе
            if (config('meta', 'slug_post') == true) {
                if ($slug != $content['post_slug']) {
                    redirect(post_slug($content['post_id'], $content['post_slug']));
                }
            }

            // Redirect when merging a post
            // Редирект при слиянии поста
            if ($content['post_merged_id'] > 0 && !$this->container->user()->admin()) {
                redirect(url('post.id', ['id' => $content['post_merged_id']]));
            }

            return $content;
        }

        return PostPresence::index($slug, 'slug');
    }

    /**
     * Posting your post on your profile
     * Размещение своего поста у себя в профиле
     */
    public function postProfile(): false|string
    {
        $post = PostPresence::index($post_id = Request::post('post_id')->asInt(), 'id');

        // Access check
        // Проверка доступа
        if ($post['post_user_id'] != $this->container->user()->id()) {
            redirect('/');
        }

        // Prohibit adding a draft to the profile
        // Запретим добавлять черновик в профиль
        if ($post['post_draft'] == 1) {
            return false;
        }

        return PostModel::setPostProfile($post_id, $this->container->user()->id());
    }

    /**
     * Posts by domain
     * Посты по домену
     *
     * @return void
     */
    public function domain()
    {
        $site = PostModel::availabilityDomain($domain = Request::param('domain')->asString());
        notEmptyOrView404($site);

        $posts      = FeedModel::feed(Html::pageNumber(), $this->limit, 'web.feed', $domain);
        $pagesCount = FeedModel::feedCount('web.feed', $domain);

        $m = [
            'og'    => false,
            'url'   => url('domain', ['domain' => $domain]),
        ];

        render(
            '/post/link',
            [
                'meta'  => Meta::get(__('app.domain') . ': ' . $domain, __('meta.domain_desc') . ': ' . $domain, $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => Html::pageNumber(),
                    'posts'         => $posts,
                    'count'         => $pagesCount,
                    'list'          => PostModel::listDomain($domain),
                    'site'          => $domain,
                    'type'          => 'domain',
                ]
            ]
        );
    }

    /**
     * Last 5 pages by content id
     * Последние 5 страниц по id контенту
     *
     * @param integer $content_id
     * @return array|false
     */
    public function last(int $content_id): array|false
    {
        return PostModel::recent($content_id, false);
    }
}
