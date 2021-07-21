<?php

namespace App\Controllers\Topic;

use Hleb\Constructor\Handlers\Request;
use App\Models\TopicModel;
use App\Models\FeedModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class TopicController extends \MainController
{
    // Все темы
    public function index()
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page'); 
        $page   = $page == 0 ? 1 : $page;

        $limit = 30; 
        $pagesCount = TopicModel::getTopicsAllCount();  
        $topics     = TopicModel::getTopicsAll($page, $limit);

        Base::PageError404($topics);

        $num = ' | ';
        if ($page > 1) 
        { 
            $num = sprintf(lang('page-number'), $page);
        } 
        
        $result = Array();
        foreach ($topics as $ind => $row) 
        {
            $row['topic_cropped']   = Base::cutWords($row['topic_description'], 81);
            $result[$ind]           = $row;
        }
        
        $news = TopicModel::getTopicNew();
        
        $data = [
            'h1'            => lang('All topics'),
            'sheet'         => 'topics',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'canonical'     => Config::get(Config::PARAM_URL) . '/topics',
            'meta_title'    => lang('All topics') . $num . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('topic-desc') . $num . Config::get(Config::PARAM_HOME_TITLE),            
        ];

        return view(PR_VIEW_DIR . '/topic/topics', ['data' => $data, 'uid' => $uid, 'topics' => $result, 'news' => $news]);
    }
    
    // Посты по теме
    public function posts($sheet)
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page'); 
        $page   = $page == 0 ? 1 : $page;

        $slug   = \Request::get('slug'); 
         
        $topic  = TopicModel::getTopic($slug, 'slug');
        Base::PageError404($topic);  
          
        // Показываем корневую тему на странице подтемы 
        $main_topic   = '';        
        if ($topic['topic_parent_id']  != 0) 
        {
            $main_topic   = TopicModel::getTopic($topic['topic_parent_id'], 'id');
        } 
        
        // Показываем подтемы корневой темы
        $subtopics  = '';
        if ($topic['topic_is_parent']  == 1 || $topic['topic_parent_id']  != 0) 
        { 
            $subtopics  = TopicModel::subTopics($topic['topic_id']);     
        }
        
        $topic['topic_add_date']    = lang_date($topic['topic_add_date']);
        
        $text = explode("\n", $topic['topic_description']);
        $topic['topic_cropped']    = Content::text($text[0], 'line');
 
        $limit = 5;  
        
        $data       = ['topic_slug' => $topic['topic_slug']];
        $posts      = FeedModel::feed($page, $limit, $uid, $sheet, 'topic', $data);
        $pagesCount = FeedModel::feedCount($uid, 'topic', $data);

        $result = Array();
        foreach ($posts as $ind => $row) 
        {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        } 

        $topic_related  = TopicModel::topicRelated($topic['topic_related']);
        $topic_signed   = TopicModel::getMyFocus($topic['topic_id'], $uid['id']);
        
       
        $meta_title = $topic['topic_seo_title'] . ' — ' .  lang('Topic');
        $data = [
            'h1'            => $topic['topic_seo_title'],
            'pagesCount'    => ceil($pagesCount / $limit),
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
         
        $topic  = TopicModel::getTopic($slug, 'slug');
        Base::PageError404($topic);  
          
        $topic['topic_add_date']    = lang_date($topic['topic_add_date']);
        
        $topic['topic_info']   = Content::text($topic['topic_info'], 'text');

        $topic_related  = TopicModel::topicRelated($topic['topic_related']);
        $post_related   = TopicModel::topicPostRelated($topic['topic_post_related']);
        
        // Показываем корневую тему на странице подтемы  
        $main_topic   = '';
        if ($topic['topic_parent_id']  != 0) 
        {
            $main_topic   = TopicModel::getTopic($topic['topic_parent_id'], 'id');
        } 
        
        // Показываем подтемы корневой темы
        $subtopics  = '';
        if ($topic['topic_is_parent']  == 1 || $topic['topic_parent_id']  != 0) { 
            $subtopics  = TopicModel::subTopics($topic['topic_id']);     
        } 
        
        $meta_title = $topic['topic_seo_title'] . ' — ' .  lang('Info');
        $data = [
            'h1'            => $topic['topic_seo_title'],
            'canonical'     => Config::get(Config::PARAM_URL) . '/topic/' . $topic['topic_slug'] . '/info',
            'sheet'         => 'info', 
            'meta_title'    => $meta_title .' | '. Config::get(Config::PARAM_NAME),
            'meta_desc'     => $topic['topic_description'] .'. '. lang('Info') .' '. Config::get(Config::PARAM_HOME_TITLE),            
        ];

        return view(PR_VIEW_DIR . '/topic/info', ['data' => $data, 'uid' => $uid, 'topic' => $topic, 'topic_related' => $topic_related, 'post_related' => $post_related, 'subtopics' => $subtopics, 'main_topic' => $main_topic]); 
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