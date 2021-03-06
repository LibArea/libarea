<?php

namespace App\Controllers\Post;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\Item\WebModel;
use App\Models\{SubscriptionModel, ActionModel, PostModel, FacetModel, NotificationModel};
use Content, UploadImage, Discord, Validation, URLScraper, Meta, UserData;

use Cocur\Slugify\Slugify;
use Utopia\Domains\Domain;

use App\Traits\Related;

class AddPostController extends Controller
{
    use Related;

    // Form adding a post / page
    // Форма добавление поста / страницы
    public function index($type)
    {
        // Adding from page topic 
        // Добавление со странице темы
        $topic_id   = Request::getInt('topic_id');
        $topic      = FacetModel::getFacet($topic_id, 'id', 'topic');

        return $this->render(
            '/post/add',
            'base',
            [
                'meta'      => Meta::get(__('app.add_' . $type)),
                'data'  => [
                    'facets'    => ['topic' => $topic],
                    'blog'      => FacetModel::getFacetsUser($this->user['id'], 'blog'),
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
        $content    = $_POST['content']; // для Markdown
        $post_url   = Request::getPost('post_url');
        $blog_id    = Request::getPostInt('blog_id');
        $fields     = Request::getPost() ?? [];

        if ($type == 'page') {
            $count  = FacetModel::countFacetsUser($this->user['id'], 'blog');
            self::error404($count);
        }

        // Используем для возврата
        $redirect = url('post.add');
        if ($blog_id > 0) {
            $redirect = url('post.add') . '/' . $blog_id;
        }

        // Let's check the stop words, url
        // Проверим стоп слова, url
        $trigger = (new \App\Controllers\AuditController())->prohibitedContent($content);

        $post_title = str_replace("&nbsp;", '', $fields['post_title']);
        Validation::length($post_title, 6, 250, 'title', $redirect);
        Validation::length($content, 6, 25000, 'content', $redirect);
 
        if ($post_url) {
            $site = $this->addUrl($post_url, $post_title);
        }

        // Обложка поста
        if (!empty($_FILES['images']['name'])) {  
            $post_img = UploadImage::coverPost($_FILES['images'], 0, $redirect, $this->user['id']);
        }
 
        $slug = self::slug($post_title);

        $post_related = $this->relatedPost();

        $last_id = PostModel::create(
            [
                'post_title'            => $post_title,
                'post_content'          => $content,
                'post_content_img'      => $post_img ?? '',
                'post_thumb_img'        => $site['og_img'] ?? '',
                'post_related'          => $post_related  ?? '',
                'post_slug'             => $slug,
                'post_feature'          => $fields['post_feature'] == 'on' ? 1 : 0,
                'post_type'             => $type,
                'post_translation'      => $fields['translation'] == 'on' ? 1 : 0,
                'post_draft'            => $fields['post_draft'] == 'on' ? 1 : 0,
                'post_ip'               => Request::getRemoteAddress(),
                'post_published'        => ($trigger === false) ? 0 : 1,
                'post_user_id'          => $this->user['id'],
                'post_url'              => $post_url ?? '',
                'post_url_domain'       => $site['post_url_domain'] ?? '',
                'post_tl'               => $fields['content_tl'],
                'post_closed'           => $fields['closed'] == 'on' ? 1 : 0,
                'post_top'              => $fields['top'] == 'on' ? 1 : 0,
            ]
        );

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            (new \App\Controllers\AuditController())->create('post', $last_id, url('admin.audits'));
        }

        $redirect = url('post', ['id' => $last_id, 'slug' => $slug]);
        if ($type == 'page') {
            $redirect = url('info.page', ['slug' => $slug]);
        }

        // Add fastes (blogs, topics) to the post 
        $type = (new \App\Controllers\Post\EditPostController())->addFacetsPost($fields, $last_id, $redirect);

        // Notification (@login). 10 - mentions in post 
        if ($message = Content::parseUser($content, true, true)) {
            (new \App\Controllers\NotificationController())->mention(NotificationModel::TYPE_ADDRESSED_POST, $message, $redirect);
        }

        if (config('integration..discord')) {
            if ($post_tl == 0 && $post_draft == 0) {
                Discord::AddWebhook($content, $post_title, $redirect);
            }
        }

        SubscriptionModel::focus($last_id, $this->user['id'], 'post');

        ActionModel::addLogs(
            [
                'user_id'       => $this->user['id'],
                'user_login'    => $this->user['login'],
                'id_content'    => $last_id,
                'action_type'   => $type,
                'action_name'   => 'added',
                'url_content'   => $redirect,
            ]
        );

        Validation::comingBack(__('msg.post_added'), 'success', $redirect);
    }

    public static function slug($title)
    {
        $slugify = new Slugify();
        $uri = $slugify->slugify($title);

        $result = PostModel::getSlug($new_slug = substr($uri, 0, 90));
        if ($result) {
            return $new_slug . "-";
        }

        return $new_slug;
    }

    public function addUrl($post_url, $post_title)
    {
        // Поскольку это для поста, то получим превью и разбор домена...
        $og_img             = self::grabOgImg($post_url);
        $parse              = parse_url($post_url);
        $url_domain         = $parse['host'];
        $domain             = new Domain($url_domain);
        $post_url_domain    = $domain->getRegisterable();
        $item_url           = $parse['scheme'] . '://' . $parse['host'];

        // Если домена нет, то добавим его 
        if (!PostModel::getDomain($post_url_domain, $this->user['id'])) {
            WebModel::add(
                [
                    'item_url'          => $item_url,
                    'item_domain'       => $post_url_domain,
                    'item_title'        => $post_title,
                    'item_content'      => __('web.desc_formed'),
                    'item_published'    => 0,
                    'item_user_id'      => $this->user['id'],
                    'item_type_url'     => 0,
                    'item_status_url'   => 200,
                    'item_is_soft'      => 0,
                    'item_is_github'    => 0,
                    'item_votes'        => 0,
                    'item_close_replies' => 0,
                    'item_count'        => 1,
                ]
            );
        } else {
            WebModel::addItemCount($post_url_domain);
        }

        $site = [
            'og_img' => $og_img,
            'post_url_domain' => $post_url_domain,
        ];

        return $site;
    }

    // Парсинг
    public function grabMeta()
    {
        $url    = Request::getPost('uri');
        $meta   = new URLScraper($url);

        $meta->parse();
        $metaData = $meta->finalize();

        return json_encode($metaData);
    }

    // Получаем данные Open Graph Protocol 
    public static function grabOgImg($post_url)
    {
        $meta = new URLScraper($post_url);
        $meta->parse();
        $metaData = $meta->finalize();

        return UploadImage::thumbPost($metaData->image);
    }

    // Рекомендовать пост
    public function recommend()
    {
        $post_id = Request::getPostInt('post_id');

        if (!UserData::checkAdmin()) {
            return false;
        }

        $post = PostModel::getPost($post_id, 'id', $this->user);
        self::error404($post);

        ActionModel::setRecommend($post_id, $post['post_is_recommend']);

        return true;
    }
}
