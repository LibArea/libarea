<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\TopicModel;
use Lori\UploadImage;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class TopicController extends \MainController
{
    // Все темы
    public function index()
    {
        $pg = \Request::getInt('page'); 
        $page = (!$pg) ? 1 : $pg;
        
        $uid        = Base::getUid();
         
        $pagesCount = TopicModel::getTopicAllCount();  
        $topics     = TopicModel::getTopicAll($page, $uid['id']);

        if ($page > 1) { 
            $num = ' — ' . lang('Page') . ' ' . $page;
        } else {
            $num = '';
        }
        
        $result = Array();
        foreach ($topics as $ind => $row) {
            $row['topic_cropped']   = Base::cutWords($row['topic_description'], 81);
            $result[$ind]           = $row;
        }
        
        $news = TopicModel::getTopicNew();
        
        $data = [
            'h1'            => lang('All topics'),
            'pagesCount'    => $pagesCount,
            'pNum'          => $page,
            'canonical'     => Config::get(Config::PARAM_URL) . '/topics',
            'sheet'         => 'topics', 
            'meta_title'    => lang('All topics') .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('topic-desc') .' '. Config::get(Config::PARAM_HOME_TITLE),            
        ];

        return view(PR_VIEW_DIR . '/topic/topics', ['data' => $data, 'uid' => $uid, 'topics' => $result, 'news' => $news]);
    }
    
    // Страница темы
    public function topic()
    {
        $pg = \Request::getInt('page'); 
        $page = (!$pg) ? 1 : $pg;
        
        $slug   = \Request::get('slug');
        $uid    = Base::getUid();
         
        $topic  = TopicModel::getTopicSlug($slug);
        Base::PageError404($topic);  
          
        // Показываем корневую тему на странице подтемы  
        if ($topic['topic_parent_id']  != 0) {
            $main_topic   = TopicModel::getTopicId($topic['topic_parent_id']);
        } else {
            $main_topic   = '';
        }
        
        // Показываем подтемы корневой темы
        if ($topic['topic_is_parent']  == 1 || $topic['topic_parent_id']  != 0) { 
            $subtopics  = TopicModel::subTopics($topic['topic_id']);     
        } else {
            $subtopics  = '';
        }
        
        $topic['topic_add_date']    = lang_date($topic['topic_add_date']);
        
        $text = explode("\n", $topic['topic_description']);
        $topic['topic_cropped']    = Content::text($text[0], 'line');
 
        $pagesCount = TopicModel::getPostsListByTopicCount($topic['topic_id'], $uid);  
        $posts      = TopicModel::getPostsListByTopic($topic['topic_id'], $uid, $page);
    
        $result = Array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_num'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        } 

        $topic_related  = TopicModel::topicRelated($topic['topic_related']);
        $topic_signed   = TopicModel::getMyFocus($topic['topic_id'], $uid['id']);
        
       
        $meta_title = $topic['topic_seo_title'] . ' — ' .  lang('Topic');
        $data = [
            'h1'            => $topic['topic_seo_title'],
            'pagesCount'    => $pagesCount,
            'pNum'          => $page,
            'canonical'     => Config::get(Config::PARAM_URL) . '/topic/' . $topic['topic_slug'],
            'sheet'         => 'topic', 
            'meta_title'    => $meta_title .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => $topic['topic_description'] .'. '. Config::get(Config::PARAM_HOME_TITLE),            
        ];

        return view(PR_VIEW_DIR . '/topic/topic', ['data' => $data, 'uid' => $uid, 'topic' => $topic, 'posts' => $result, 'topic_related' => $topic_related, 'topic_signed' => $topic_signed, 'main_topic' => $main_topic, 'subtopics' => $subtopics]);

    }
 
    // Информация по теме
    public function info()
    {
        $slug   = \Request::get('slug');
        $uid    = Base::getUid();
         
        $topic  = TopicModel::getTopicSlug($slug);
        Base::PageError404($topic);  
          
        $topic['topic_add_date']    = lang_date($topic['topic_add_date']);
        
        $topic['topic_info']   = Content::text($topic['topic_info'], 'text');

        $topic_related  = TopicModel::topicRelated($topic['topic_related']);
        $post_related   = TopicModel::topicPostRelated($topic['topic_post_related']);
        
        // Показываем корневую тему на странице подтемы  
        if ($topic['topic_parent_id']  != 0) {
            $main_topic   = TopicModel::getTopicId($topic['topic_parent_id']);
        } else {
            $main_topic   = '';
        }
        
        // Показываем подтемы корневой темы
        if ($topic['topic_is_parent']  == 1 || $topic['topic_parent_id']  != 0) { 
            $subtopics  = TopicModel::subTopics($topic['topic_id']);     
        } else {
            $subtopics  = '';
        }

        $meta_title = $topic['topic_seo_title'] . ' — ' .  lang('Info');
        $data = [
            'h1'            => $topic['topic_seo_title'],
            'canonical'     => Config::get(Config::PARAM_URL) . '/topic',
            'sheet'         => 'info', 
            'meta_title'    => $meta_title .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => $topic['topic_description'] .'. '. lang('Info') .' '. Config::get(Config::PARAM_HOME_TITLE),            
        ];

        return view(PR_VIEW_DIR . '/topic/info', ['data' => $data, 'uid' => $uid, 'topic' => $topic, 'topic_related' => $topic_related, 'post_related' => $post_related, 'subtopics' => $subtopics, 'main_topic' => $main_topic]); 
    }
 
    // Форма добавить topic
    public function addTopicForm() 
    {
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }

        $data = [
            'meta_title'    => lang('Add topic'),
            'sheet'         => 'topics',
        ]; 

        return view(PR_VIEW_DIR . '/admin/topic/add-topic', ['data' => $data, 'uid' => $uid]);
    }
    
    // Добавим topic
    public function topicAdd() 
    {
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }
        
        $topic_title        = \Request::getPost('topic_title');
        $topic_description  = \Request::getPost('topic_description');
        $topic_slug         = \Request::getPost('topic_slug');
        $topic_seo_title    = \Request::getPost('topic_seo_title');
        $topic_merged_id    = \Request::getPost('topic_merged_id');
        $topic_related      = \Request::getPost('topic_related');
        
        $redirect = '/admin/topic/add';

        Base::Limits($topic_title , lang('Title'), '3', '64', $redirect);
        Base::Limits($topic_description, lang('Meta Description'), '44', '225', $redirect);
        Base::Limits($topic_slug, lang('Slug'), '3', '43', $redirect);
        Base::Limits($topic_seo_title, lang('Slug'), '4', '225', $redirect);
        
        $topic_merged_id    = empty($topic_merged_id) ? 0 : $topic_merged_id;
        $topic_related      = empty($topic_related) ? '' : $topic_related;
        $topic_img          = 'topic-default.png';
        
        $topic_add_date = date("Y-m-d H:i:s");
        $data = [
            'topic_title'       => $topic_title, 
            'topic_description' => $topic_description, 
            'topic_slug'        => $topic_slug, 
            'topic_img'         => $topic_img,  
            'topic_add_date'    => $topic_add_date,
            'topic_seo_title'   => $topic_seo_title, 
            'topic_merged_id'   => $topic_merged_id,
            'topic_related'     => $topic_related,
            'topic_count'       => 0, 
        ];
        
        TopicModel::add($data);
        
        redirect('/admin/topics/');  
    }
 
    // Форма редактирования topic
    public function editTopicForm() 
    {
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }
        
        $topic_id   = \Request::getInt('id');
        $topic      = TopicModel::getTopicId($topic_id);
        
        Base::PageError404($topic);
        
        Request::getResources()->addBottomStyles('/assets/css/select2.css'); 
        Request::getHead()->addStyles('/assets/css/image-uploader.css'); 
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');
        Request::getResources()->addBottomScript('/assets/js/select2.min.js'); 
        
        $topic_related      = TopicModel::topicRelated($topic['topic_related']);
        
        if ($topic['topic_parent_id']  != 0) {
            $topic_parent_id    = TopicModel::topicMain($topic['topic_parent_id']);
        } else {
            $topic_parent_id    = '';
        }
        
        $data = [
            'meta_title'    => lang('Edit topic') . ' — ' . $topic['topic_title'],
            'sheet'         => 'topics',
        ]; 

        return view(PR_VIEW_DIR . '/admin/topic/edit-topic', ['data' => $data, 'uid' => $uid, 'topic' => $topic, 'topic_related' => $topic_related, 'topic_parent_id' => $topic_parent_id]);
    }
    
    // Edit topic
    public function topicEdit()
    {
        $uid    = Base::getUid();
        $tl     = validTl($uid['trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/'); 
        }
        
        $topic_id           = \Request::getPostInt('topic_id');
        $topic_title        = \Request::getPost('topic_title');
        $topic_description  = \Request::getPost('topic_description');
        $topic_info         = \Request::getPost('topic_info');
        $topic_slug         = \Request::getPost('topic_slug');
        $topic_seo_title    = \Request::getPost('topic_seo_title');
        $topic_merged_id    = \Request::getPostInt('topic_merged_id');
        $topic_is_parent    = \Request::getPostInt('topic_is_parent');
        $topic_count        = \Request::getPostInt('topic_count');
        
        $topic = TopicModel::getTopicId($topic_id);
        Base::PageError404($topic);
        
        $parent_id          = empty($_POST['topic_parent_id']) ? '' : $_POST['topic_parent_id'];
        $topic_parent_id    = empty($parent_id) ? 0 : implode(',', $parent_id);
        
        $related            = empty($_POST['topic_related']) ? '' : $_POST['topic_related'];
        $topic_related      = empty($related) ? '' : implode(',', $related);
        
        // Если убираем тему из корневой, то должны очистеть те темы, которые были подтемами
        if ($topic['topic_is_parent'] == 1 && $topic_is_parent == 0) {
            TopicModel::clearBinding($topic['topic_id']);
        }

        $redirect = '/admin/topic/' . $topic['topic_id'] . '/edit';

        Base::Limits($topic_title , lang('Title'), '3', '64', $redirect);
        Base::Limits($topic_slug, lang('Slug'), '3', '43', $redirect);
        Base::Limits($topic_seo_title, lang('Name SEO'), '4', '225', $redirect);
        Base::Limits($topic_description, lang('Meta Description'), '44', '225', $redirect);
        Base::Limits($topic_info, lang('Info'), '14', '5000', $redirect);
        
        $topic_merged_id    = empty($topic_merged_id) ? 0 : $topic_merged_id;
        $topic_parent_id    = empty($topic_parent_id) ? 0 : $topic_parent_id;
        $topic_is_parent    = empty($topic_is_parent) ? 0 : $topic_is_parent;
        $topic_count        = empty($topic_count) ? 0 : $topic_count;
        
        // Запишем img
        $img = $_FILES['images'];
        $check_img  = $_FILES['images']['name'][0];
        if($check_img) {
            UploadImage::img($img, $topic['topic_id'], 'topic');
        }         
         
        $topic_add_date = date("Y-m-d H:i:s");
        $data = [
            'topic_id'          => $topic_id, 
            'topic_title'       => $topic_title, 
            'topic_description' => $topic_description,
            'topic_info'        => $topic_info, 
            'topic_slug'        => $topic_slug, 
            'topic_add_date'    => $topic_add_date,
            'topic_seo_title'   => $topic_seo_title, 
            'topic_merged_id'   => $topic_merged_id,
            'topic_parent_id'   => $topic_parent_id,
            'topic_is_parent'   => $topic_is_parent,
            'topic_related'     => $topic_related,
            'topic_count'       => $topic_count, 
        ];
        
        TopicModel::edit($data);
        
        Base::addMsg(lang('Changes saved'), 'success');
        redirect($redirect);  
    }
    
    // Подписка / отписка от тем
    public function focus()
    {
        $uid        = Base::getUid();
        $topic_id   = \Request::getPostInt('topic_id'); 

        TopicModel::focus($topic_id, $uid['id']);
        
        return true;
    }
    
}