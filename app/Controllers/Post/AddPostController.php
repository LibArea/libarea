<?php

declare(strict_types=1);

namespace App\Controllers\Post;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\{SubscriptionModel, ActionModel, PostModel, FacetModel, PollModel, NotificationModel};
use App\Content\Integration\{Discord, Telegram};
use App\Content\Сheck\{PostPresence, FacetPresence};
use UploadImage, URLScraper, Meta, Msg;

use Utopia\Domains\Domain;
use App\Validate\RulesPost;

use App\Traits\Poll;
use App\Traits\Slug;
use App\Traits\Related;

class AddPostController extends Controller
{
    use Poll;
    use Slug;
    use Related;

    /**
     * Form adding a post / page
     * Форма добавление поста / страницы
     *
     * @return void
     */
    public function index()
    {
        // Adding from page topic / blog
        // Добавление со странице темы / блога
        $facet_id   = Request::get('facet_id')->asInt();

        if ($facet_id) {
            $facet  =  FacetPresence::all($facet_id);
            if ($facet['facet_type'] == 'topic') {
                $topic  = FacetPresence::index($facet_id, 'id', 'topic');
            } elseif ($facet['facet_type'] == 'blog' && $facet['facet_user_id'] == $this->container->user()->id()) {
                $blog  = FacetPresence::index($facet_id, 'id', 'blog');
            }
        }
        return render(
            '/post/add',
            [
                'meta'      => Meta::get(__('app.add_post')),
                'data'  => [
                    'topic'         => $topic ?? false,
                    'blog'          => $blog ?? false,
                    'showing-blog'  => array_merge(FacetModel::getTeamFacets('blog'), FacetModel::getFacetsUser('blog')),
                    'post_arr'      => PostModel::postRelatedAll(),
                    'type'          => 'add',
                    'count_poll'    => PollModel::getUserQuestionsPollsCount(),
                ]
            ]
        );
    }

    /**
     * Add post 
     * Добавим пост
     *
     * @param [type] $type
     * @return void
     */
    public function add(string $type)
    {
        if ($post_url = Request::post('post_url')->value()) {
            $site = $this->addUrl($post_url);
        }

        $blog_id    = Request::post('blog_id')->value();
        $fields     = Request::allPost() ?? [];

        $content = $_POST['content'] == '' ? $_POST['content_qa'] : $_POST['content'];
        $content = $content == '' ? $_POST['content_url'] : $content;

        if ($type == 'page') {
            $count  = FacetModel::countFacetsUser($this->container->user()->id(), 'blog');
            notEmptyOrView404($count);
        }

        // Use to return
        // Используем для возврата
        $redirect = url('post.form.add');
        if ($blog_id > 0) {
            $redirect = $redirect . '/' . $blog_id;
        }

        // Let's check the stop words, url
        // Проверим стоп слова и url
        $trigger = (new \App\Controllers\AuditController())->prohibitedContent($content);

        RulesPost::rules($fields['post_title'], $content, $redirect);

        // Post cover
        // Обложка поста
        if (!empty($_FILES['images']['name'])) {
            $post_img = UploadImage::coverPost($_FILES['images'], 0, $redirect);
        }

        if (PostModel::getSlug($slug = $this->getSlug($fields['post_title']))) {
            $slug = $slug . "-";
        }

        $post_related = $this->relatedPost();

        $translation = $fields['translation'] ?? false;
        $post_draft = $fields['draft'] ?? false;
        $post_nsfw = $fields['nsfw'] ?? false;
        $post_hidden = $fields['hidden'] ?? false;
        $closed = $fields['closed'] ?? false;
        $top = $fields['top'] ?? false;

        $post_feature = config('general', 'qa_site_format') === true ? 1 : Request::post('post_feature')->asInt();

        $last_id = PostModel::create(
            [
                'post_title'            => $fields['post_title'],
                'post_content'          => $content,
                'post_content_img'      => $post_img ?? '',
                'post_thumb_img'        => $site['og_img'] ?? '',
                'post_related'          => $post_related  ?? '',
                'post_slug'             => $slug,
                'post_feature'          => $post_feature,
                'post_type'             => $type,
                'post_translation'      => $translation == 'on' ? 1 : 0,
                'post_draft'            => $post_draft == 'on' ? 1 : 0,
                'post_nsfw'             => $post_nsfw == 'on' ? 1 : 0,
                'post_hidden'           => $post_hidden == 'on' ? 1 : 0,
                'post_ip'               => Request::getUri()->getIp(),
                'post_published'        => ($trigger === false) ? 0 : 1,
                'post_user_id'          => $this->container->user()->id(),
                'post_url'              => $post_url ?? '',
                'post_url_domain'       => $site['post_url_domain'] ?? '',
                'post_tl'               => $fields['content_tl'] ?? 0,
                'post_closed'           => $closed == 'on' ? 1 : 0,
                'post_top'              => $top == 'on' ? 1 : 0,
                'post_poll'             => $this->selectPoll(Request::post('poll_id')->value() ?? ''),
            ]
        );

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            (new \App\Controllers\AuditController())->create('post', $last_id, post_slug($last_id, $slug));
        }

        $url_content = post_slug($last_id, $slug);
        if ($type == 'page') {
            $url_content = url('info.page', ['slug' => $slug]);
        }

        // Add fastes (blogs, topics) to the post 
        $type = (new \App\Controllers\Post\EditPostController())->addFacetsPost($fields, $last_id, $url_content);

        // Contact via @
        // Обращение через @
        if ($message = \App\Content\Parser\Content::parseUsers($content, true, true)) {
            (new \App\Controllers\NotificationController())->mention(NotificationModel::TYPE_ADDRESSED_POST, $message, $url_content);
        }

        $this->addIntegration($content, $url_content, $fields);

        SubscriptionModel::focus($last_id, 'post');

        ActionModel::addLogs(
            [
                'id_content'    => $last_id,
                'action_type'   => $type,
                'action_name'   => 'added',
                'url_content'   => $url_content,
            ]
        );

        Msg::redirect(__('msg.post_added'), 'success', $url_content);
    }

    /**
     * Since this is for the post, we will get a preview and analysis of the domain...
     *
     * @param [type] $post_url
     * @return void
     */
    public function addUrl(string $post_url)
    {
        $domain = new Domain(host($post_url));

        $site = [
            'og_img'            => self::grabOgImg($post_url),
            'post_url_domain'   => $domain->getRegisterable(),
        ];

        return $site;
    }

    /**
     * Parsing
     * Парсинг
     *
     * @return void
     */
    public function grabMeta()
    {
        $url    = Request::post('uri')->value();
        $result = URLScraper::get($url);

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    /**
     * Getting Open Graph Protocol Data
     * Получаем данные Open Graph Protocol 
     *
     * @param [type] $post_url
     * @return void
     */
    public static function grabOgImg(string $post_url)
    {
        $meta = URLScraper::get($post_url);

        return UploadImage::thumbPost($meta['image']);
    }

    /**
     * Recommend post
     * Рекомендовать пост
     *
     * @return void
     */
    public function recommend()
    {
        $post_id = Request::post('post_id')->asInt();

        if (!$this->container->user()->admin()) {
            return false;
        }

        $post = PostPresence::index($post_id);

        ActionModel::setRecommend($post_id, $post['post_is_recommend']);

        return true;
    }

    public function addIntegration(string $content, string $url_content, array $fields)
    {
        $post_draft = $fields['post_draft'] ?? false;

        if ($fields['content_tl'] == 0 && $post_draft == 0) {

            // Discord
            if (config('integration', 'discord')) {
                Discord::AddWebhook($content, $fields['post_title'], $url_content);
            }

            // Telegram
            if (config('integration', 'telegram')) {
                Telegram::AddWebhook($content, $fields['post_title'], $url_content);
            }
        }
    }
}
