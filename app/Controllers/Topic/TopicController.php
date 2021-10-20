<?php

namespace App\Controllers\Topic;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{FeedModel, SubscriptionModel, TopicModel};
use Content, Base;

class TopicController extends MainController
{
    // Все темы
    public function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 20;
        $pagesCount = TopicModel::getTopicsAllCount($uid['user_id'], 'all');
        $topics     = TopicModel::getTopicsAll($page, $limit, $uid['user_id'], 'all');

        Base::PageError404($topics);

        $num = ' ';
        if ($page > 1) {
            $num = sprintf(lang('page-number'), $page);
        }

        $result = array();
        foreach ($topics as $ind => $row) {
            $row['topic_cropped']   = cutWords($row['topic_description'], 9);
            $result[$ind]           = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('topics'),
        ];
        $meta = meta($m, lang('all topics') . $num, lang('topic-desc') . $num);

        $data = [
            'sheet'         => 'topics',
            'topics'        => $result,
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'news'          => TopicModel::getTopicNew(10),
        ];

        return view('/topic/topics', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Темы участника
    public function topicsUser()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $limit  = 30;

        $pagesCount = TopicModel::getTopicsAllCount($uid['user_id'], 'subscription');
        $topics     = TopicModel::getTopicsAll($page, $limit, $uid['user_id'], 'subscription');

        $result = array();
        foreach ($topics as $ind => $row) {
            $row['topic_cropped']   = cutWords($row['topic_description'], 9);
            $result[$ind]           = $row;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => false,
        ];
        $meta = meta($m, lang('Читаю темы'), $desc = '');

        $data = [
            'h1'                => lang('Читаю темы'),
            'sheet'             => 'my-topics',
            'pagesCount'        => ceil($pagesCount / $limit),
            'pNum'              => $page,
            'topics'            => $result,
        ];

        return view('/topic/topics', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Посты по теме
    public function posts($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $slug   = Request::get('slug');
        $topic  = TopicModel::getTopic($slug, 'slug');
        Base::PageError404($topic);

        // Показываем корневую тему на странице подтемы 
        $main_topic   = '';
        if ($topic['topic_parent_id']  != 0) {
            $main_topic   = TopicModel::getTopic($topic['topic_parent_id'], 'id');
        }

        // Показываем подтемы корневой темы
        $subtopics  = '';
        if ($topic['topic_is_parent']  == 1 || $topic['topic_parent_id']  != 0) {
            $subtopics  = TopicModel::subTopics($topic['topic_id']);
        }

        $topic['topic_add_date']    = lang_date($topic['topic_add_date']);

        $text = explode("\n", $topic['topic_description']);
        $topic['topic_cropped']    = Content::text($text[0], 'line');

        $limit = 25;
        $data       = ['topic_slug' => $topic['topic_slug']];
        $posts      = FeedModel::feed($page, $limit, $uid, $sheet, 'topic', $data);
        $pagesCount = FeedModel::feedCount($uid, 'topic', $data);

        $result = array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('answer'), lang('answers-m'), lang('answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/topics/logos/' . $topic['topic_img'],
            'url'        => getUrlByName('topic', ['slug' => $topic['topic_slug']]),
        ];
        $meta = meta($m, $topic['topic_seo_title'] . ' — ' .  lang('topic'), $topic['topic_description']);


        $writers = TopicModel::getWriters($topic['topic_id']);

        $data = [
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'sheet'         => 'topic',
            'topic'         => $topic,
            'posts'         => $result,
            'topic_related' => TopicModel::topicRelated($topic['topic_related']),
            'topic_signed'  => SubscriptionModel::getFocus($topic['topic_id'], $uid['user_id'], 'topic'),
            'user'          => UserModel::getUser($topic['topic_user_id'], 'id'),
            'main_topic'    => $main_topic,
            'subtopics'     => $subtopics,
            'writers'       => $writers,
        ];

        return view('/topic/topic', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Информация по теме
    public function info()
    {
        $slug   = Request::get('slug');
        $uid    = Base::getUid();

        $topic  = TopicModel::getTopic($slug, 'slug');
        Base::PageError404($topic);

        $topic['topic_add_date']    = lang_date($topic['topic_add_date']);

        $topic['topic_info']   = Content::text($topic['topic_info'], 'text');

        // Показываем корневую тему на странице подтемы  
        $main_topic   = '';
        if ($topic['topic_parent_id']  != 0) {
            $main_topic   = TopicModel::getTopic($topic['topic_parent_id'], 'id');
        }

        // Показываем подтемы корневой темы
        $subtopics  = '';
        if ($topic['topic_is_parent']  == 1 || $topic['topic_parent_id']  != 0) {
            $subtopics  = TopicModel::subTopics($topic['topic_id']);
        }

        $topic_select = empty($topic['topic_post_related']) ? 0 : $topic['topic_post_related'];

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/topics/logos/' . $topic['topic_img'],
            'url'        => getUrlByName('topic.info', ['slug' => $topic['topic_slug']]),
        ];
        $meta = meta($m, $topic['topic_seo_title'] . ' — ' .  lang('topic'), $topic['topic_description']);

        $data = [
            'sheet'         => 'info',
            'topic'         => $topic,
            'topic_related' => TopicModel::topicRelated($topic['topic_related']),
            'post_select'   => TopicModel::topicPostRelated($topic_select),
            'subtopics'     => $subtopics,
            'user'          => UserModel::getUser($topic['topic_user_id'], 'id'),
            'main_topic'    => $main_topic
        ];

        return view('/topic/info', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
