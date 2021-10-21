<?php

namespace App\Controllers\Post;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{NotificationsModel, SubscriptionModel, ActionModel, WebModel, PostModel, TopicModel};
use Content, Base, UploadImage, Integration, Validation, SendEmail, UrlRecord, URLScraper, Config;

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
        Request::getHead()->addStyles('/assets/css/image-uploader.css');
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');
        Request::getResources()->addBottomStyles('/assets/editor/editormd.css');
        Request::getResources()->addBottomScript('/assets/editor/meditor.min.js');
        Request::getResources()->addBottomStyles('/assets/css/select2.css');
        Request::getResources()->addBottomScript('/assets/js/select2.min.js');

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($this->uid['user_id'], 'id');
        Base::accountBan($user);

        // Добавление со странице темы
        $topic_id   = Request::getInt('topic_id');
        $topic      = TopicModel::getTopic($topic_id, 'id');

        $meta = meta($m = [], lang('add post'));
        
        return view('/post/add', ['meta' => $meta, 'uid' => $this->uid, 'data' => ['topic' => $topic]]);
    }

    // Добавим пост
    public function create()
    {
        // Получаем title и содержание
        $post_title             = Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // для Markdown
        $post_url               = Request::getPost('post_url');
        $post_closed            = Request::getPostInt('closed');
        $post_draft             = Request::getPostInt('post_draft');
        $post_top               = Request::getPostInt('top');
        $post_type              = Request::getPostInt('post_type');
        $post_translation       = Request::getPostInt('translation');
        $post_merged_id         = Request::getPostInt('post_merged_id');
        $post_tl                = Request::getPostInt('post_tl');

        $post_fields    = Request::getPost() ?? [];
        $post_related   = implode(',', $post_fields['post_select'] ?? []);
        $topics         = $post_fields['topic_select'] ?? [];

        // Используем для возврата
        $redirect = '/post/add';

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($this->uid['user_id'], 'id');
        Base::accountBan($user);
        Content::stopContentQuietМode($user);

        Base::PageRedirection($topics, $redirect);


        Validation::Limits($post_title, lang('title'), '6', '250', $redirect);
        Validation::Limits($post_content, lang('the post'), '6', '25000', $redirect);

        if ($post_url) {
            // Поскольку это для поста, то получим превью 
            $og_img             = self::grabOgImg($post_url);
            $parse              = parse_url($post_url);
            $post_url_domain    = $parse['host'];
            $link_url           = $parse['scheme'] . '://' . $parse['host'];

            // Если домена нет, то добавим его
            $link = WebModel::getLinkOne($post_url_domain, $this->uid['user_id']);
            if (!$link) {
                // Запишем минимальные данный
                $data = [
                    'link_url'          => $link_url,
                    'link_url_domain'   => $post_url_domain,
                    'link_title'        => $post_title,
                    'link_content'      => $post_content,
                    'link_user_id'      => $this->uid['user_id'],
                    'link_type'         => 0,
                    'link_status'       => 200,
                ];
                WebModel::add($data);
            } else {
                WebModel::addLinkCount($post_url_domain);
            }
        }

        // Обложка поста
        $cover  = $_FILES['images'];
        if ($_FILES['images']['name'][0]) {
            $post_img = UploadImage::cover_post($cover, 0, $redirect);
        }

        // Ограничим добавления постов (в день)
        Validation::speedAdd($this->uid, 'post');

        // Получаем SEO поста
        $slugGenerator  = new UrlRecord();
        $slug           = $slugGenerator->GetSeoFriendlyName($post_title);
        $post_slug      = substr($slug, 0, 90);

        // Стоп слова (и другие условия) + оповещение
        $post_published = 1;
        if (Content::stopWordsExists($post_content)) {
            // Если меньше 2 постов и если контент попал в стоп лист, то заморозка
            $all_count = ActionModel::ceneralContributionCount($this->uid['user_id']);
            if ($all_count < 2) {
                ActionModel::addLimitingMode($this->uid['user_id']);
                addMsg(lang('limiting-mode-1'), 'error');
                redirect('/');
            }

            $post_published = 0;
            addMsg(lang('post-audit'), 'error');
        }

        $data = [
            'post_title'            => $post_title,
            'post_content'          => Content::change($post_content),
            'post_content_img'      => $post_img ?? '',
            'post_thumb_img'        => $og_img ?? '',
            'post_related'          => $post_related,
            'post_merged_id'        => $post_merged_id,
            'post_tl'               => $post_tl,
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

        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $row) {
                $arr[] = array($row, $last_post_id);
            }
            TopicModel::addPostTopics($arr, $last_post_id);
        }

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
}
