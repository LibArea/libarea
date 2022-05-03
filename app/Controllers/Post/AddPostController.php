<?php

namespace App\Controllers\Post;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\WebModel;
use App\Models\{SubscriptionModel, ActionModel, PostModel, FacetModel, NotificationModel};
use Content, UploadImage, Integration, Validation, URLScraper, Tpl, Meta, Html, UserData;

use Cocur\Slugify\Slugify;
use Utopia\Domains\Domain;

class AddPostController extends MainController
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Form adding a post / page
    // Форма добавление поста / страницы
    public function index($type)
    {
        if ($type == 'page') {
            $count  = FacetModel::countFacetsUser($this->user['id'], 'blog');
            Html::pageError404($count);
        }

        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');
        Request::getResources()->addBottomStyles('/assets/js/editor/easymde.min.css');
        Request::getResources()->addBottomScript('/assets/js/editor/easymde.min.js');

        if ($type != 'page') {
            Request::getResources()->addBottomScript('/assets/js/uploads.js');
        }

        // Adding from page topic 
        // Добавление со странице темы
        $topic_id   = Request::getInt('topic_id');
        $topic      = FacetModel::getFacet($topic_id, 'id', 'topic');

        $puth = $type == 'page' ? '/page/add' : '/post/add';

        return Tpl::LaRender(
            $puth,
            [
                'meta'      => Meta::get(__('app.add_option', ['name' => __('app.post')])),
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
        $post_title             = Request::getPost('post_title');
        $content                = $_POST['content']; // для Markdown
        $post_url               = Request::getPost('post_url');
        $post_closed            = Request::getPostInt('closed');
        $post_draft             = Request::getPostInt('post_draft');
        $post_top               = Request::getPostInt('top');
        $post_feature           = Request::getPostInt('post_feature');
        $post_translation       = Request::getPostInt('translation');
        $post_tl                = Request::getPostInt('content_tl');
        $blog_id                = Request::getPostInt('blog_id');

        $fields = Request::getPost() ?? [];

        if ($type == 'page') {
            $count  = FacetModel::countFacetsUser($this->user['id'], 'blog');
            Html::pageError404($count);
        }

        // Related posts 
        // Связанные посты
        $json_post  = $fields['post_select'] ?? null;
        if ($json_post) {
            $arr_post   = json_decode($json_post, true);
            if ($arr_post) {
                foreach ($arr_post as $value) {
                    $id[]   = $value['id'];
                }
            }
            $post_related = implode(',', $id ?? []);
        }

        // Используем для возврата
        $redirect = url('post.add');
        if ($blog_id > 0) {
            $redirect = url('post.add') . '/' . $blog_id;
        }

        // We will check for freezing, stop words, the frequency of posting content per day 
        // Проверим на заморозку, стоп слова, частоту размещения контента в день
        $trigger = (new \App\Controllers\AuditController())->placementSpeed($content, 'post');

        $post_title = str_replace("&nbsp;", '', $post_title);
        Validation::Length($post_title, 'title', '6', '250', $redirect);
        Validation::Length($content, 'content', '6', '25000', $redirect);

        if ($post_url) {
            $site = $this->addUrl($post_url, $post_title);
        }

        // Обложка поста
        if (!empty($_FILES['images']['name'])) {
            $post_img = UploadImage::cover_post($_FILES['images'], 0, $redirect, $this->user['id']);
        }

        // Получаем SEO поста
        $slug = self::slug($post_title);

        $last_id = PostModel::create(
            [
                'post_title'            => $post_title,
                'post_content'          => Content::change($content),
                'post_content_img'      => $post_img ?? '',
                'post_thumb_img'        => $site['og_img'] ?? '',
                'post_related'          => $post_related  ?? false,
                'post_tl'               => $post_tl ?? 0,
                'post_slug'             => $slug,
                'post_feature'          => $post_feature,
                'post_type'             => $type,
                'post_translation'      => $post_translation,
                'post_draft'            => $post_draft,
                'post_ip'               => Request::getRemoteAddress(),
                'post_published'        => ($trigger === false) ? 0 : 1,
                'post_user_id'          => $this->user['id'],
                'post_closed'           => $post_closed,
                'post_top'              => $post_top,
                'post_url'              => $post_url ?? '',
                'post_url_domain'       => $site['post_url_domain'] ?? '',
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

        if (config('general.discord')) {
            if ($post_tl == 0 && $post_draft == 0) {
                Integration::AddWebhook($content, $post_title, $redirect);
            }
        }

        SubscriptionModel::focus($last_id, $this->user['id'], 'post');

        ActionModel::addLogs(
            [
                'user_id'       => $this->user['id'],
                'user_login'    => $this->user['login'],
                'id_content'    => $last_id,
                'action_type'   => $type,
                'action_name'   => 'content_added',
                'url_content'   => $redirect,
            ]
        );

        redirect($redirect);
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
                    'item_content'      => __('app.description.formed'),
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

        return UploadImage::thumb_post($metaData->image);
    }

    // Рекомендовать пост
    public function recommend()
    {
        $post_id = Request::getPostInt('post_id');

        if (!UserData::checkAdmin()) {
            return false;
        }

        $post = PostModel::getPost($post_id, 'id', $this->user);
        Html::pageError404($post);

        ActionModel::setRecommend($post_id, $post['post_is_recommend']);

        return true;
    }
}
