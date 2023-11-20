<?php

namespace App\Controllers\Post;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Services\Сheck\PostPresence;
use App\Services\Сheck\FacetPresence;
use App\Services\Meta\Post;
use App\Services\Tree\BuildTree;
use App\Models\{PostModel, CommentModel, SubscriptionModel, FeedModel};
use Meta, UserData;

use App\Traits\Views;
use App\Traits\Poll;
use App\Traits\LastDataModified;

class PostController extends Controller
{
    use Views;
    use Poll;
    use LastDataModified;

    protected $limit = 25;

    // Full post
    // Полный пост
    public function index($type)
    {
        $slug  = Request::get('slug');
        $id    = Request::getInt('id');
		$sorting  = Request::getGet('sort');

        $content = self::presence($type, $id, $slug);

        $this->setPostView($content['post_id'], $this->user['id']);

        $content['modified'] = $content['post_date'] != $content['post_modified'] ? true : false;

        $facets = PostModel::getPostTopic($content['post_id'], 'topic');
        $blog   = PostModel::getPostTopic($content['post_id'], 'blog');

        // Show the draft only to the author
        if ($content['post_draft'] == 1 && $content['post_user_id'] != $this->user['id']) {
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

        if ($type == 'post') {
            return $this->render(
                '/post/post-view',
                [
                    'meta'  => Post::metadata($content),
                    'data'  => [
                        'post'          => $content,
                        'comments'		=> BuildTree::index(0, $comments),
                        'recommend'     => PostModel::postSimilars($content['post_id'], $facets[0]['facet_id'] ?? null),
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
                    ]
                ]
            );
        }

        $slug_facet = Request::get('facet_slug');
        $facet  = FacetPresence::index($slug_facet, 'slug', 'section');

        return $this->render(
            '/post/page-view',
            [
                'meta'  => Post::metadata($content),
                'data'  => [
                    'sheet' => 'page',
                    'type'  => $type,
                    'page'  => $content,
                    'facet' => $facet,
                    'pages' => PostModel::recent($facet['facet_id'], $content['post_id'])
                ]
            ]
        );
    }

    public static function presence($type, $id, $slug)
    {
        // Check id and get content data
        // Проверим id и получим данные контента
        if ($type == 'post') {
            $content = PostPresence::index($id, 'id');

            // If the post slug is different from the data in the database
            // Если slug поста отличается от данных в базе
            if (config('meta.slug_post') == true) {
                if ($slug != $content['post_slug']) {
                    redirect(post_slug($content['post_id'], $content['post_slug']));
                }
            }

            // Redirect when merging a post
            // Редирект при слиянии поста
            if ($content['post_merged_id'] > 0 && !UserData::checkAdmin()) {
                redirect(url('post_id', ['id' => $content['post_merged_id']]));
            }

            return $content;
        }

        return PostPresence::index($slug, 'slug');
    }

    // Posting your post on your profile
    // Размещение своего поста у себя в профиле
    public function postProfile()
    {
        $post = PostPresence::index($post_id = Request::getPostInt('post_id'), 'id');

        // Access check
        // Проверка доступа
        if ($post['post_user_id'] != UserData::getUserId()) {
            redirect('/');
        }

        // Prohibit adding a draft to the profile
        // Запретим добавлять черновик в профиль
        if ($post['post_draft'] == 1) {
            return false;
        }

        return PostModel::setPostProfile($post_id, $this->user['id']);
    }

    // Posts by domain
    // Посты по домену
    public function domain()
    {
        $site = PostModel::availabilityDomain($domain = Request::get('domain'));
        notEmptyOrView404($site);

        $posts      = FeedModel::feed($this->pageNumber, $this->limit, 'web.feed', $domain);
        $pagesCount = FeedModel::feedCount('web.feed', $domain);

        $m = [
            'og'    => false,
            'url'   => url('domain', ['domain' => $domain]),
        ];

        return $this->render(
            '/post/link',
            [
                'meta'  => Meta::get(__('app.domain') . ': ' . $domain, __('meta.domain_desc') . ': ' . $domain, $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'posts'         => $posts,
                    'count'         => $pagesCount,
                    'list'          => PostModel::listDomain($domain),
                    'site'          => $domain,
                    'type'          => 'domain',
                ]
            ]
        );
    }

    // Last 5 pages by content id
    // Последние 5 страниц по id контенту
    public function last($content_id)
    {
        return PostModel::recent($content_id, null);
    }
}
