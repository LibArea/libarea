<?php

namespace App\Controllers\Post;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{NotificationsModel, SubscriptionModel, ActionModel, WebModel, PostModel, FacetModel};
use Content, Base, UploadImage, Integration, Validation, SendEmail, Slug, URLScraper, Config, Translate, Domains;

class AddPostController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Форма добавление поста
    public function index()
    {
        Request::getResources()->addBottomScript('/assets/js/uploads.js');
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');
        Request::getResources()->addBottomStyles('/assets/js/editor/toastui-editor.min.css');
        Request::getResources()->addBottomStyles('/assets/js/editor/dark.css');
        Request::getResources()->addBottomScript('/assets/js/editor/toastui-editor-all.min.js');

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($this->uid['user_id'], 'id');
        (new \App\Controllers\Auth\BanController())->getBan($user);

        // Добавление со странице темы
        $topic_id   = Request::getInt('topic_id');
        $topic      = FacetModel::getFacet($topic_id, 'id');

        $facets = ['topic' => $topic];
        if ($topic) {
            if ($topic['facet_type'] == 'blog') {
                $facets  = ['blog' => $topic];
                if ($topic['facet_user_id'] != $this->uid['user_id']) redirect('/');
            }
       } 

        return view(
            '/post/add',
            [
                'meta'      => meta($m = [], Translate::get('add post')),
                'uid'       => $this->uid,
                'data'  => [
                    'facets'     => $facets,
                    'user_blog'  => FacetModel::getFacetsUser($this->uid['user_id'], 'blog'),
                    'post_arr'   => PostModel::postRelatedAll(),
                ]
            ]
        );
    }

    // Добавим пост
    public function create()
    {
        // Получаем title и содержание
        $post_title             = Request::getPost('post_title');
        $post_content           = $_POST['content']; // для Markdown
        $post_url               = Request::getPost('post_url');
        $post_closed            = Request::getPostInt('closed');
        $post_draft             = Request::getPostInt('post_draft');
        $post_top               = Request::getPostInt('top');
        $post_type              = Request::getPostInt('post_type');
        $post_translation       = Request::getPostInt('translation');
        $post_merged_id         = Request::getPostInt('post_merged_id');
        $post_tl                = Request::getPostInt('post_tl');
        $blog_id                = Request::getPostInt('blog_id');
        
        $post_fields    = Request::getPost() ?? [];

        // Связанные посты
        $json_post  = $post_fields['post_select'] ?? [];
        $arr_post   = json_decode($json_post[0], true);
        if ($arr_post) {  
            foreach ($arr_post as $value) {
               $id[]   = $value['id'];
            }
        }
        $post_related = implode(',', $id ?? []);


        // Темы для поста
        $facet_post     = $post_fields['facet_select'] ?? [];
        $topics         = json_decode($facet_post[0], true);

        // Используем для возврата
        $redirect = getUrlByName('post.add');
        if ($blog_id > 0) {
           $redirect = getUrlByName('post.add') . '/' . $blog_id; 
        }

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($this->uid['user_id'], 'id');
        (new \App\Controllers\Auth\BanController())->getBan($user);
        Content::stopContentQuietМode($user);

        // Если нет темы
        if (!$topics) {
            addMsg(Translate::get('select topic') . '!', 'error');
            redirect($redirect);
        }
 
        Validation::Limits($post_title, Translate::get('title'), '6', '250', $redirect);
        Validation::Limits($post_content, Translate::get('the post'), '6', '25000', $redirect);

        if ($post_url) {
            
            // Поскольку это для поста, то получим превью и разбор домена...
            $og_img             = self::grabOgImg($post_url);
            $parse              = parse_url($post_url);
            $url_domain         = $parse['host'];
            $domain             = new Domains($url_domain);
            $post_url_domain    = $domain->getRegisterable();
            $item_url           = $parse['scheme'] . '://' . $parse['host'];

            // Если домена нет, то добавим его
            $item = WebModel::getItemOne($post_url_domain, $this->uid['user_id']);
            if (!$item) {
                // Запишем минимальные данный
                $data = [
                    'item_url'          => $item_url,
                    'item_url_domain'   => $post_url_domain,
                    'item_title_url'    => $post_title,
                    'item_content_url'  => Translate::get('description is formed'),
                    'item_published'    => 0,
                    'item_user_id'      => $this->uid['user_id'],
                    'item_type_url'     => 0,
                    'item_status_url'   => 200,
                ];
                WebModel::add($data);
            } else {
                WebModel::addItemCount($post_url_domain);
            }
        }

        // Обложка поста
        $cover  = $_FILES['images'];
        if ($_FILES['images']['name']) {
            $post_img = UploadImage::cover_post($cover, 0, $redirect, $this->uid['user_id']);
        }

        // Ограничим добавления постов (в день)
        Validation::speedAdd($this->uid, 'post');

        // Получаем SEO поста
        $slug       = new Slug();
        $uri        = $slug->create($post_title);
        $post_slug  = substr($uri, 0, 90);

        // Если контента меньше N и он содержит ссылку 
        // Оповещение админу
        $post_published = 1;
        if (!Validation::stopSpam($post_content, $this->uid['user_id'])) {
            addMsg(Translate::get('content-audit'), 'error');
            $post_published = 0;
        }

        $data = [
            'post_title'            => $post_title,
            'post_content'          => Content::change($post_content),
            'post_content_img'      => $post_img ?? '',
            'post_thumb_img'        => $og_img ?? '',
            'post_related'          => $post_related,
            'post_merged_id'        => $post_merged_id,
            'post_tl'               => $post_tl ?? 0,
            'post_slug'             => $post_slug,
            'post_type'             => $post_type,
            'post_translation'      => $post_translation,
            'post_draft'            => $post_draft,
            'post_ip'               => Request::getRemoteAddress(),
            'post_published'        => $post_published,
            'post_user_id'          => $this->uid['user_id'],
            'post_url'              => $post_url ?? '',
            'post_url_domain'       => $post_url_domain ?? '',
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
        ];

        $last_post_id   = PostModel::AddPost($data);
        $url_post       = getUrlByName('post', ['id' => $last_post_id, 'slug' => $post_slug]);

        if ($post_published == 0) {
            ActionModel::addAudit('post', $this->uid['user_id'], $last_post_id);
            // Оповещение админу
            $type = 15; // Упоминания в посте  
            $user_id  = 1; // админу        
            NotificationsModel::send($this->uid['user_id'], $user_id, $type, $last_post_id, $url_post, 1);
        }

        // Получим id блога с формы выбора
        $blog_post  = $post_fields['blog_select'] ?? [];
        $blog       = json_decode($blog_post, true); // <- Array ([0]=> Array ([id]=> 53 [value]=> Блог [tl]=> 0)) 
        $form_id    = $blog[0]['id'];

        if ($blog) {
            $topics = array_merge($blog, $topics);
        }  
  
        // Запишем темы и блог
        $arr = [];
        foreach ($topics as $ket => $row) {
           $arr[] = $row;
        }
        FacetModel::addPostFacets($arr, $last_post_id);
     

        // Уведомление (@login)
        if ($message = Content::parseUser($post_content, true, true)) {
            foreach ($message as $user_id) {
                // Запретим отправку себе
                if ($user_id == $this->uid['user_id']) {
                    continue;
                }
                $type = 10; // Упоминания в посте      
                NotificationsModel::send($this->uid['user_id'], $user_id, $type, $last_post_id, $url_post, 1);
                SendEmail::mailText($user_id, 'appealed');
            }
        }

        // Отправим в Discord
        if (Config::get('general.discord') == 1) {
            if ($post_tl == 0 && $post_draft == 0) {
                Integration::AddWebhook($post_content, $post_title, $url_post);
            }
        }

        SubscriptionModel::focus($last_post_id, $this->uid['user_id'], 'post');

        redirect($url_post);
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

    // Удаление и восстановление контента
    public function deletingAndRestoring()
    {
        $info       = Request::getPost('info');
        $status     = preg_split('/(@)/', $info);
        $type_id    = (int)$status[0]; // id конткнта
        $type       = $status[1];      // тип контента

        $allowed = ['post', 'comment', 'answer'];
        if (!in_array($type, $allowed)) {
            return false;
        }

        // Проверка доступа 
        $info_type = ActionModel::getInfoTypeContent($type_id, $type);
        if (!accessСheck($info_type, $type, $this->uid, 1, 30)) {
            redirect('/');
        }

        ActionModel::setDeletingAndRestoring($type, $info_type[$type . '_id'], $info_type[$type . '_is_deleted']);

        $status = 'deleted-' . $type;
        if ($info_type[$type . '_is_deleted'] == 1) {
            $status = 'restored-' . $type;
        }

        $info_post_id = $info_type[$type . '_post_id'];
        if ($type == 'post') {
            $info_post_id = $info_type[$type . '_id'];
        }

        $data = [
            'user_id'       => $this->uid['user_id'],
            'user_tl'       => $this->uid['user_trust_level'],
            'created_at'    => date("Y-m-d H:i:s"),
            'post_id'       => $info_post_id,
            'content_id'    => $info_type[$type . '_id'],
            'action'        => $status,
            'reason'        => '',
        ];

        ActionModel::moderationsAdd($data);

        return true;
    }

    // Рекомендовать пост
    public function recommend()
    {
        $post_id = Request::getPostInt('post_id');

        // Проверка доступа 
        $tl     = Validation::validTl($this->uid['user_trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/');
        }

        $post = PostModel::getPostId($post_id);
        pageError404($post);

        ActionModel::setRecommend($post_id, $post['post_is_recommend']);

        return true;
    }
}
