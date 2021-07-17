<?php

namespace App\Controllers\Post;

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use App\Models\ActionModel;
use App\Models\SpaceModel;
use App\Models\WebModel;
use App\Models\PostModel;
use App\Models\TopicModel;
use Lori\UploadImage;
use Lori\Content;
use Lori\Config;
use Lori\Base;
use UrlRecord;
use URLScraper;

class AddPostController extends \MainController
{
    // Добавим пост
    public function index()
    {
        // Получаем title и содержание
        $post_title             = \Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // не фильтруем
        $post_url               = \Request::getPost('post_url');
        $post_closed            = \Request::getPostInt('closed');
        $post_draft             = \Request::getPostInt('post_draft');
        $post_top               = \Request::getPostInt('top'); 
        $post_type              = \Request::getPostInt('post_type');
        $post_translation       = \Request::getPostInt('translation');
        $post_merged_id         = \Request::getPostInt('post_merged_id');
        $post_tl                = \Request::getPostInt('post_tl');
      
        $related        = empty($_POST['post_related']) ? '' : $_POST['post_related'];
        $post_related   = empty($related) ? '' : implode(',', $related);
        $topics         = empty($_POST['post_topics']) ? '' : $_POST['post_topics'];
        
        // Используем для возврата
        $redirect = '/post/add';

        // Данные кто добавляет
        $uid            = Base::getUid();
        $post_ip_int    = \Request::getRemoteAddress();
        
        // Получаем id пространства
        $space_id   = \Request::getPostInt('space_id');

        // Получаем информацию по пространству
        $space = SpaceModel::getSpace($space_id, 'id');
        if (!$space) {
            Base::addMsg(lang('Select space'), 'error');
            redirect($redirect);
        }

        // Если стоит ограничение: публиковать может только автор
        if ($space['space_permit_users'] == 1) {
            // Кроме персонала и владельца
            if ($uid['trust_level'] != 5 && $space['space_user_id'] != $uid['id']) {
               Base::addMsg(lang('You dont have access'), 'error');
               redirect($redirect);
            }
        }
            
        Base::Limits($post_title, lang('Title'), '6', '250', $redirect);
        Base::Limits($post_content, lang('The post'), '6', '25000', $redirect);
        
        if ($post_url) { 
            // Поскольку это для поста, то получим превью 
            $og_img             = self::grabOgImg($post_url);
            $parse              = parse_url($post_url);
            $post_url_domain    = $parse['host']; 
            $link_url           = $parse['scheme'] . '://' . $parse['host'];

            // Мы должны добавить домен, который появился в системе
            // Далее мы можем менять ему статус, запрещать и т.д.
            $link = WebModel::getLinkOne($post_url_domain);
            if (!$link) {
                // Запишем минимальные данный для дальнешей работы
                $data = [
                    'link_url'          => $link_url,
                    'link_url_domain'   => $post_url_domain,
                    'link_user_id'      => $uid['id'],
                    'link_type'         => 0,
                    'link_status'       => 200,
                    'link_cat_id'       => 1,
                ];
                WebModel::addLink($data);
            } else {
                WebModel::addLinkCount($post_url_domain);
            }
        }   

        // Обложка поста
        $cover          = $_FILES['images'];
        $check_cover    = $_FILES['images']['name'][0];
        if($check_cover) {
           $post_img = UploadImage::cover_post($cover, $post);
        } 
        
        // Проверяем url для > TL1
        // Ввести проверку дублей и запрещенных, для img повторов
        $post_url           = empty($post_url) ? '' : $post_url;
        $post_url_domain    = empty($post_url_domain) ? '' : $post_url_domain;
        $post_content_img   = empty($post_img) ? '' : $post_img;
        $og_img             = empty($og_img) ? '' : $og_img;

        // Участник с нулевым уровнем доверия должен быть ограничен в добавлении ответов
        if ($uid['trust_level'] < Config::get(Config::PARAM_TL_ADD_POST)) {
            $num_post =  PostModel::getPostSpeed($uid['id']);
            if (count($num_post) > 2) {
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
        if(Content::stopWordsExists($post_content)) {
            $post_published = 0;
            Base::addMsg(lang('post_audit'), 'error');
        }

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
            'post_ip_int'           => $post_ip_int,
            'post_published'        => $post_published,
            'post_user_id'          => $uid['id'],
            'post_space_id'         => $space_id,
            'post_url'              => $post_url,
            'post_url_domain'       => $post_url_domain,
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
        ];
        
        $last_post_id   = PostModel::AddPost($data);
        $url_post       = '/post/'. $last_post_id .'/'. $post_slug;
        
        if ($post_published == 0) {
            ActionModel::addAudit('post', $uid['id'], $last_post_id);
            // Оповещение админу
            $type = 15; // Упоминания в посте  
            $user_id  = 1; // админу        
            NotificationsModel::send($uid['id'], $user_id, $type, $last_post_id, $url_post, 1);
        } 
        
        if(!empty($topics)) { 
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
				if ($user_id == $post_user_id) {
					continue;
				}
 				$type = 10; // Упоминания в посте      
                NotificationsModel::send($post_user_id, $user_id, $type, $last_post_id, $url_post, 1);
			}
		}
        
        // Отправим в Discord
        if (Config::get(Config::PARAM_DISCORD) == 1) {
            if ($post_tl == 0 && $post_draft == 0) {
                Base::AddWebhook($post_content, $post_title, $url_post);
            }
        }
        
        redirect('/');   
    }
    
    // Форма добавление поста
    public function add() 
    {
        $uid        = Base::getUid();
        $space      = SpaceModel::getSpaceSelect($uid['id'], $uid['trust_level']);
        
        $space_id   = \Request::getInt('space_id');
        
        $data = [
            'h1'            => lang('Add post'),
            'sheet'         => 'add-post',
            'meta_title'    => lang('Add post'),
        ];  
        
        Request::getHead()->addStyles('/assets/css/image-uploader.css'); 
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        Request::getResources()->addBottomStyles('/assets/editor/editormd.css');
        Request::getResources()->addBottomScript('/assets/editor/editormd.js');
        Request::getResources()->addBottomScript('/assets/editor/lib/marked.min.js');
        Request::getResources()->addBottomScript('/assets/editor/lib/prettify.min.js');
        Request::getResources()->addBottomScript('/assets/editor/config.js');

        Request::getResources()->addBottomStyles('/assets/css/select2.css'); 

        if ($uid['trust_level'] > 0) {
            Request::getResources()->addBottomScript('/assets/js/select2.min.js'); 
        } 

        return view(PR_VIEW_DIR . '/post/add', ['data' => $data, 'uid' => $uid, 'space' => $space, 'space_id' => $space_id]);
    }    
    
    // Парсинг
    public function grabMeta() 
    {
        $url    = \Request::getPost('uri');
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
        } else {
            $image = $result['tags_meta']['image'];
        }
        
        return UploadImage::thumb_post($image);
    }

    // Удаление и восстановление контента
    public function deletingAndRestoring()
    {
        $uid        = Base::getUid();
        $info       = \Request::getPost('info');
        $status     = preg_split('/(@)/', $info);
        $type_id    = (int)$status[0]; // id конткнта
        $type       = $status[1];      // тип контента

        $allowed = ['post','comment','answer'];
        if(!in_array($type, $allowed)) {
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
        
        if ($type == 'post') {
            $info_post_id = $info_type[$type . '_id'];
        } else {
            $info_post_id = $info_type[$type . '_post_id'];
        }
        
        $data = [
            'user_id'       => $uid['id'], 
            'user_tl'       => $uid['trust_level'], 
            'created_at'    => date("Y-m-d H:i:s"), 
            'post_id'       => $info_post_id,
            'content_id'    => $info_type[$type . '_id'], 
            'action'        => $status,
            'reason'        => '',
        ];
        
        ActionModel::moderationsAdd($data); 

        return true;        
    }
    
    // Журнал логирования удалений / восстановлений контента
    public function moderation()
    {
        $moderations_log      = ActionModel::getModerations();
        
        $result = Array();
        foreach ($moderations_log as $ind => $row) {
            $row['mod_created_at']    = lang_date($row['mod_created_at']);
            $result[$ind]         = $row;
        } 
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Moderation Log'),
            'canonical'     => '/moderations',
            'sheet'         => 'moderations',
            'meta_title'    => lang('Moderation Log') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('meta-moderation') .' '. Config::get(Config::PARAM_HOME_TITLE),
        ];
        
        return view(PR_VIEW_DIR . '/moderation/index', ['data' => $data, 'uid' => $uid, 'moderations' => $result]);
    }

}