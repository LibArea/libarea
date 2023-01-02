<?php

namespace App\Controllers\Post;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{SubscriptionModel, ActionModel, PostModel, FacetModel, NotificationModel};
use App\Services\Integration\{Discord, Telegram};
use App\Services\Сheck\{PostPresence, FacetPresence};
use UploadImage, URLScraper, Meta, UserData;

use Utopia\Domains\Domain;
use App\Validate\RulesPost;

use App\Traits\Slug;
use App\Traits\Related;

class AddPostController extends Controller
{
    use Slug;
    use Related;

    // Form adding a post / page
    // Форма добавление поста / страницы
    public function index($type)
    {
        // Adding from page topic 
        // Добавление со странице темы
        $topic_id   = Request::getInt('topic_id');
        if ($topic_id) {
            $topic  = FacetPresence::index($topic_id, 'id', 'topic');
        }

        $blog = FacetModel::getFacetsUser($this->user['id'], 'blog');
        $facets = FacetModel::getTeamFacets($this->user['id'], 'blog');

        return $this->render(
            '/post/add',
            [
                'meta'      => Meta::get(__('app.add_' . $type)),
                'data'  => [
                    'facets'    => ['topic' => $topic ?? false],
                    'blog'      => array_merge($facets, $blog),
                    'post_arr'  => PostModel::postRelatedAll(),
                    'type'      => 'add',
                ]
            ]
        );
    }

    // Add post 
    // Добавим пост
    public function create($type)
    {
        $post_url   = Request::getPost('post_url');
        $blog_id    = Request::getPostInt('blog_id');
        $fields     = Request::getPost() ?? [];

        $content = $_POST['content'] == '' ? $_POST['content_qa'] : $_POST['content'];
        $content = $content == '' ? $_POST['content_url'] : $content;

        if ($type == 'page') {
            $count  = FacetModel::countFacetsUser($this->user['id'], 'blog');
            notEmptyOrView404($count);
        }

        // Use to return
        // Используем для возврата
        $redirect = url('content.add', ['type' => 'post']);
        if ($blog_id > 0) {
            $redirect = url('content.add', ['type' => 'post']) . '/' . $blog_id;
        }

        // Let's check the stop words, url
        // Проверим стоп слова и url
        $trigger = (new \App\Services\Audit())->prohibitedContent($content);

        RulesPost::rules($fields['post_title'], $content, $redirect);

        if ($post_url) {
            $site = $this->addUrl($post_url, $fields['post_title']);
        }

        // Post cover
        // Обложка поста
        if (!empty($_FILES['images']['name'])) {
            $post_img = UploadImage::coverPost($_FILES['images'], 0, $redirect, $this->user['id']);
        }

        if (PostModel::getSlug($slug = $this->getSlug($fields['post_title']))) {
            $slug = $slug . "-";
        }

        $post_related = $this->relatedPost();

        $translation = $fields['translation'] ?? false;
        $post_draft = $fields['post_draft'] ?? false;
        $closed = $fields['closed'] ?? false;
        $top = $fields['top'] ?? false;

        $post_feature = config('general.qa_site_format') === true ? 1 : Request::getPostInt('post_feature');

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
                'post_ip'               => Request::getRemoteAddress(),
                'post_published'        => ($trigger === false) ? 0 : 1,
                'post_user_id'          => $this->user['id'],
                'post_url'              => $post_url ?? '',
                'post_url_domain'       => $site['post_url_domain'] ?? '',
                'post_tl'               => $fields['content_tl'] ?? 0,
                'post_closed'           => $closed == 'on' ? 1 : 0,
                'post_top'              => $top == 'on' ? 1 : 0,
            ]
        );

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            (new \App\Services\Audit())->create('post', $last_id, url('admin.audits'));
        }

        $url_content = post_slug($last_id, $slug);
        if ($type == 'page') {
            $url_content = url('info.page', ['slug' => $slug]);
        }

        // Add fastes (blogs, topics) to the post 
        $type = (new \App\Controllers\Post\EditPostController())->addFacetsPost($fields, $last_id, $url_content);

        // Contact via @
        // Обращение через @
        if ($message = \App\Services\Parser\Content::parseUser($content, true, true)) {
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

        is_return(__('msg.post_added'), 'success', $url_content);
    }

    // Since this is for the post, we will get a preview and analysis of the domain ...
    public function addUrl($post_url, $post_title)
    {
        $parse              = parse_url($post_url);
        $domain             = new Domain($parse['host']);

        $site = [
            'og_img'            => self::grabOgImg($post_url),
            'post_url_domain'   => $domain->getRegisterable(),
        ];

        return $site;
    }

    // Parsing
    // Парсинг
    public function grabMeta()
    {
        $url    = Request::getPost('uri');
        $meta   = new URLScraper($url);

        $meta->parse();
        $metaData = $meta->finalize();

        return json_encode($metaData);
    }

    // Getting Open Graph Protocol Data 
    // Получаем данные Open Graph Protocol 
    public static function grabOgImg($post_url)
    {
        $meta = new URLScraper($post_url);
        $meta->parse();
        $metaData = $meta->finalize();

        return UploadImage::thumbPost($metaData->image);
    }

    // Recommend post
    // Рекомендовать пост
    public function recommend()
    {
        $post_id = Request::getPostInt('post_id');

        if (!UserData::checkAdmin()) {
            return false;
        }

        $post = PostPresence::index($post_id);

        ActionModel::setRecommend($post_id, $post['post_is_recommend']);

        return true;
    }

    public function addIntegration($content, $url_content, $fields)
    {
        $post_draft = $fields['post_draft'] ?? false;

        if ($fields['content_tl'] == 0 && $post_draft == 0) {

            // Discord
            if (config('integration.discord')) {
                Discord::AddWebhook($content, $fields['post_title'], $url_content);
            }

            // Telegram
            if (config('integration.telegram')) {
                Telegram::AddWebhook($content, $fields['post_title'], $url_content);
            }
        }
    }
}
