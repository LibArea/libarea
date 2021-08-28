<?php

namespace App\Controllers\Post;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{NotificationsModel, SubscriptionModel, ActionModel, SpaceModel, WebModel, PostModel, TopicModel, UserModel};
use Lori\{Content, Config, Base, UploadImage, Integration, Validation};
use UrlRecord;
use URLScraper;

class AddPostController extends MainController
{
    // Добавим пост
    public function index()
    {
        // Получаем title и содержание
        $post_title             = Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // не фильтруем
        $post_url               = Request::getPost('post_url');
        $post_closed            = Request::getPostInt('closed');
        $post_draft             = Request::getPostInt('post_draft');
        $post_top               = Request::getPostInt('top');
        $post_type              = Request::getPostInt('post_type');
        $post_translation       = Request::getPostInt('translation');
        $post_merged_id         = Request::getPostInt('post_merged_id');
        $post_tl                = Request::getPostInt('post_tl');

        $related        = empty($_POST['post_related']) ? '' : $_POST['post_related'];
        $post_related   = empty($related) ? '' : implode(',', $related);
        $topics         = empty($_POST['post_topics']) ? '' : $_POST['post_topics'];

        // Используем для возврата
        $redirect = '/post/add';

        // Данные кто добавляет
        $uid        = Base::getUid();
        $post_ip    = Request::getRemoteAddress();

        // Если пользователь забанен / заморожен
        $user = UserModel::getUser($uid['user_id'], 'id');
        Base::accountBan($user);
        Content::stopContentQuietМode($user);

        // Получаем информацию по пространству
        $space_id   = Request::getPostInt('space_id');
        $space      = SpaceModel::getSpace($space_id, 'id');
        Base::PageRedirection($space, $redirect);

        // Если стоит ограничение: публиковать может только автор
        if ($space['space_permit_users'] == 1) {
            // Кроме персонала и владельца
            if ($uid['user_trust_level'] != 5 && $space['space_user_id'] != $uid['user_id']) {
                Base::addMsg(lang('You dont have access'), 'error');
                redirect($redirect);
            }
        }

        Validation::Limits($post_title, lang('Title'), '6', '250', $redirect);
        Validation::Limits($post_content, lang('The post'), '6', '25000', $redirect);

        if ($post_url) {
            // Поскольку это для поста, то получим превью 
            $og_img             = self::grabOgImg($post_url);
            $parse              = parse_url($post_url);
            $post_url_domain    = $parse['host'];
            $link_url           = $parse['scheme'] . '://' . $parse['host'];

            // Если домена нет, то добавим его
            $link = WebModel::getLinkOne($post_url_domain, $uid['user_id']);
            if (!$link) {
                // Запишем минимальные данный
                $data = [
                    'link_url'          => $link_url,
                    'link_url_domain'   => $post_url_domain,
                    'link_title'        => $post_title,
                    'link_content'      => $post_content,
                    'link_user_id'      => $uid['user_id'],
                    'link_type'         => 0,
                    'link_status'       => 200,
                    'link_category_id'  => 1,
                ];
                WebModel::addLink($data);
            } else {
                WebModel::addLinkCount($post_url_domain);
            }
        }

        // Обложка поста
        $cover          = $_FILES['images'];
        $check_cover    = $_FILES['images']['name'][0];
        if ($check_cover) {
            $post_img = UploadImage::cover_post($cover, $space_id, $redirect);
        }

        // Проверяем url для > TL1
        // Ввести проверку дублей и запрещенных, для img повторов
        $post_url           = empty($post_url) ? '' : $post_url;
        $post_url_domain    = empty($post_url_domain) ? '' : $post_url_domain;
        $post_content_img   = empty($post_img) ? '' : $post_img;
        $og_img             = empty($og_img) ? '' : $og_img;

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении ответов
        if ($uid['user_trust_level'] < Config::get(Config::PARAM_TL_ADD_POST)) {
            $num_post =  PostModel::getPostSpeed($uid['user_id']);
            if ($num_post > 2) {
                Base::addMsg(lang('limit-post-day'), 'error');
                redirect('/');
            }
        }

        // Получаем SEO поста
        $slugGenerator  = new UrlRecord();
        $slug           = $slugGenerator->GetSeoFriendlyName($post_title);
        $post_slug      = substr($slug, 0, 90);

        // Стоп слова (и другие условия) + оповещение
        $post_published = 1;
        if (Content::stopWordsExists($post_content)) {
            // Если меньше 2 постов и если контент попал в стоп лист, то заморозка
            $all_count = ActionModel::ceneralContributionCount($uid['user_id']);
            if ($all_count < 2) {
                ActionModel::addLimitingMode($uid['user_id']);
                Base::addMsg(lang('limiting_mode_1'), 'error');
                redirect('/');
            }

            $post_published = 0;
            Base::addMsg(lang('post_audit'), 'error');
        }

        $post_content = Content::change($post_content);

        $data = [
            'post_title'            => $post_title,
            'post_content'          => $post_content,
            'post_content_img'      => $post_content_img,
            'post_thumb_img'        => $og_img,
            'post_related'          => $post_related,
            'post_merged_id'        => $post_merged_id,
            'post_tl'               => $post_tl,
            'post_slug'             => $post_slug,
            'post_type'             => $post_type,
            'post_translation'      => $post_translation,
            'post_draft'            => $post_draft,
            'post_ip'               => $post_ip,
            'post_published'        => $post_published,
            'post_user_id'          => $uid['user_id'],
            'post_space_id'         => $space_id,
            'post_url'              => $post_url,
            'post_url_domain'       => $post_url_domain,
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
        ];

        $last_post_id   = PostModel::AddPost($data);
        $url_post       = '/post/' . $last_post_id . '/' . $post_slug;

        if ($post_published == 0) {
            ActionModel::addAudit('post', $uid['user_id'], $last_post_id);
            // Оповещение админу
            $type = 15; // Упоминания в посте  
            $user_id  = 1; // админу        
            NotificationsModel::send($uid['user_id'], $user_id, $type, $last_post_id, $url_post, 1);
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
                if ($user_id == $uid['user_id']) {
                    continue;
                }
                $type = 10; // Упоминания в посте      
                NotificationsModel::send($uid['user_id'], $user_id, $type, $last_post_id, $url_post, 1);
                Base::mailText($user_id, 'appealed');
            }
        }

        // Отправим в Discord
        if (Config::get(Config::PARAM_DISCORD) == 1) {
            if ($post_tl == 0 && $post_draft == 0) {
                Integration::AddWebhook($post_content, $post_title, $url_post);
            }
        }

        SubscriptionModel::focus($last_post_id, $uid['user_id'], 'post');

        redirect($url_post);
    }

    // Форма добавление поста
    public function add()
    { 
        $uid        = Base::getUid(); 
        
      
        $spaces     = SpaceModel::getSpaceSelect($uid['user_id'], $uid['user_trust_level']);
        $space_id   = Request::getInt('space_id');

        Request::getHead()->addStyles('/assets/css/image-uploader.css');
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        Request::getResources()->addBottomStyles('/assets/editor/editormd.css');
        Request::getResources()->addBottomScript('/assets/editor/editormd.min.js');
        Request::getResources()->addBottomScript('/assets/editor/config.js');

        Request::getResources()->addBottomStyles('/assets/css/select2.css');

        if ($uid['user_trust_level'] > 0) {
            Request::getResources()->addBottomScript('/assets/js/select2.min.js');
        }

        $meta = [
            'sheet'         => 'add-post',
            'meta_title'    => lang('Add post') . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        $data = [
            'spaces'    => $spaces,
            'space_id'  => $space_id,
        ];

        return view('/post/add', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Парсинг
    public function grabMeta()
    {
        $url    = Request::getPost('uri');
        $result = URLScraper::get($url);

        return json_encode($result);
    }

    // Получаем данные Open Graph Protocol 
    public static function grabOgImg($post_url)
    {
        $result = URLScraper::get($post_url);

        if ($result['image']) {
            $image = $result['image'];
        } elseif ($result['tags_meta']['twitter:image']) {
            $image = $result['tags_meta']['twitter:image'];
        } elseif ($result['tags_meta']['og:image']) {
            $image = $result['tags_meta']['og:image'];
        } elseif ($result['tags_my']['image']) {
            $image = $result['tags_my']['image'];
        } else {
            $image = $result['tags_meta']['image'];
        }

        return UploadImage::thumb_post($image);
    }

    // Удаление и восстановление контента
    public function deletingAndRestoring()
    {
        $uid        = Base::getUid();
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
        if (!accessСheck($info_type, $type, $uid, 1, 30)) {
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
            'user_id'       => $uid['user_id'],
            'user_tl'       => $uid['user_trust_level'],
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
