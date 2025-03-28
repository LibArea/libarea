<?php

declare(strict_types=1);

namespace App\Controllers\Publication;

use Hleb\Static\Request;
use Hleb\Base\Controller;

use App\Content\Сheck\Availability;
use App\Models\{PublicationModel, CommentModel, SubscriptionModel, FeedModel};

use App\Traits\Views;
use App\Traits\Poll;
use App\Traits\LastDataModified;
use BuildTree, Html, Meta, MetaImage, Img;

use Parsedown;
use League\HTMLToMarkdown\HtmlConverter;

class PublicationController extends Controller
{
    use Views;
    use Poll;
    use LastDataModified;

    protected $limit = 25;

    public function post(): void
    {
        $this->callIndex('post');
    }

    public function article(): void
    {
        $this->callIndex('article');
    }

    public function question(): void
    {
        $this->callIndex('question');
    }

    public function note(): void
    {
        $this->callIndex('note');
    }

    public function page(): void
    {
        $this->callIndex('page');
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

        $facets = PublicationModel::getPostTopic($content['post_id'], 'topic');
        $blog   = PublicationModel::getPostTopic($content['post_id'], 'blog');

        // Show the draft only to the author
        if ($content['post_draft'] == 1 && $content['post_user_id'] != $this->container->user()->id()) {
            redirect('/');
        }

        // If the post type is a page, then depending on the conditions we make a redirect
        if ($content['post_type'] == 'page' && $id > 0) {
            redirect(url('page', ['facet_slug' => 'info', 'slug' => $content['post_slug']]));
        }

        if ($content['post_related']) {
            $related_posts = PublicationModel::postRelated($content['post_related']);
        }

        // Sending Last-Modified and handling HTTP_IF_MODIFIED_SINCE
        $this->getDataModified($content['post_modified']);

        $comments = CommentModel::getCommentsPost($content['post_id'], $content['post_type'], $sorting);

        if ($type === 'page') {

            $page  = Availability::facet('info', 'slug', 'section');

            render(
                '/publications/view/page',
                [
                    'meta'  => Meta::publication($type, $content),
                    'data'  => [
                        'sheet' => 'page',
                        'type'  => 'info',
                        'page'  => $content,
                        'facet' => [],
                        'pages' => PublicationModel::morePages($content['post_id'])
                    ]
                ]
            );
        } else {

            render(
                '/publications/view/post',
                [
                    'meta'  => Meta::publication($type, $content),
                    'data'  => [
                        'post'          => $content,
                        'comments'      => BuildTree::index(0, $comments),
                        'recommend'     => PublicationModel::postSimilars($content['post_id'], (int)$facets[0]['facet_id'] ?? null),
                        'related_posts' => $related_posts ?? '',
                        'post_signed'   => SubscriptionModel::getFocus($content['post_id'], 'post'),
                        'facet_signed'  => SubscriptionModel::getFocus($blog[0]['facet_id'] ?? null, 'facet'),
                        'facets'        => $facets,
                        'united'        => PublicationModel::getPostMerged($content['post_id']),
                        'blog'          => $blog ?? null,
                        'sorting'       => $sorting ?? null,
                        'sheet'         => 'article',
                        'type'          => 'post',
                        'poll'          => $this->getPoll($content['post_poll']),
                        'is_answer'     => CommentModel::isAnswerUser($content['post_id']),
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
            $content = Availability::content($id);

            // If the post slug is different from the data in the database
            // Если slug поста отличается от данных в базе
            if (config('meta', 'slug_post') == true) {
                if ($slug != $content['post_slug']) {
                    redirect(post_slug($type, $content['post_id'], $content['post_slug']));
                }
            }

            // Redirect when merging a post
            // Редирект при слиянии поста
            if ($content['post_merged_id'] > 0 && !$this->container->user()->admin()) {
                redirect(url('post.id', ['id' => $content['post_merged_id']]));
            }

            return $content;
        }

        return Availability::content($slug, 'slug');
    }

    /**
     * Posting your post on your profile
     * Размещение своего поста у себя в профиле
     */
    public function postProfile(): false|string
    {
        $post = Availability::content($post_id = Request::post('post_id')->asInt(), 'id');

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

        return PublicationModel::setPostProfile($post_id, $this->container->user()->id());
    }

    /**
     * Posts by domain
     * Посты по домену
     *
     * @return void
     */
    public function domain()
    {
        $site = PublicationModel::availabilityDomain($domain = Request::param('domain')->asString());
        notEmptyOrView404($site);

        $posts      = FeedModel::feed(Html::pageNumber(), $this->limit, 'web.feed', $domain);
        $pagesCount = FeedModel::feedCount('web.feed', $domain);

        $m = [
            'og'    => false,
            'url'   => url('domain', ['domain' => $domain]),
        ];

        render(
            '/publications/link',
            [
                'meta'  => Meta::get(__('app.domain') . ': ' . $domain, __('meta.domain_desc') . ': ' . $domain, $m),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => Html::pageNumber(),
                    'posts'         => $posts,
                    'count'         => $pagesCount,
                    'list'          => PublicationModel::listDomain($domain),
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
        return PublicationModel::recent($content_id, false);
    }

    public function OgImage()
    {
        $id = Request::param('id')->value();
        $post = Availability::content($id);

        MetaImage::get($post['post_title'], $post['login'], Img::PATH['avatars'] .  $post['avatar'],  Meta::publicationImage($post));
    }

    public function editorTest()
    {
        $md = Availability::content(1936);

        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true);

        $md_content =  $Parsedown->text($md['post_content']);

        render(
            '/publications/editor-test',
            [
                'meta'  => Meta::get(__('app.development'), __('meta.development'), ['og'    => false]),
                'data'  => ['type' => 'test', 'md' => $md_content]
            ]
        );
    }

    public function addEditTest()
    {
        $html = Request::post('content')->value();

        $converter = new HtmlConverter(array('strip_tags' => true));

        $markdown = $converter->convert($html);

        print_r('<pre>' . $markdown . '</pre>');
    }
}
