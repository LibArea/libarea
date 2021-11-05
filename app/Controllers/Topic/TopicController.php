<?php

namespace App\Controllers\Topic;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use App\Models\{FeedModel, SubscriptionModel, TopicModel};
use Content, Base, Translate;

class TopicController extends MainController
{
    // all / new / my
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 40;
        $pagesCount = TopicModel::getTopicsAllCount($uid['user_id'], $sheet);
        $topics     = TopicModel::getTopicsAll($page, $limit, $uid['user_id'], $sheet);

        Base::PageError404($topics);

        $num = ' ';
        if ($page > 1) {
            $num = sprintf(Translate::get('page-number'), $page);
        }

        if ($sheet == 'all') {
            $url = getUrlByName('topics');
        } elseif ($sheet == 'new') {
            $url = getUrlByName('topics.new');
        } else {
            $url = getUrlByName('topics.my');
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => $url,
        ];

        return view(
            '/topic/topics',
            [
                'meta'  => meta($m, Translate::get('topics-' . $sheet) . $num, Translate::get('topic-desc-' . $sheet) . $num),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => 'topics-' . $sheet,
                    'topics'        => $topics,
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                ]
            ]
        );
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

        $topic['topic_add_date']    = lang_date($topic['topic_add_date']);

        $limit = 25;
        $data       = ['topic_slug' => $topic['topic_slug']];
        $posts      = FeedModel::feed($page, $limit, $uid, $sheet, 'topic', $data);
        $pagesCount = FeedModel::feedCount($uid, $sheet, 'topic', $data);

        $result = array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $url    = getUrlByName('topic', ['slug' => $topic['topic_slug']]);
        $title  = $topic['topic_seo_title'] . ' — ' .  Translate::get('topic');
        $descr  = $topic['topic_description'];
        if ($sheet == 'recommend') {
            $url =  getUrlByName('recommend', ['slug' => $topic['topic_slug']]);
            $title  = $topic['topic_seo_title'] . ' — ' .  Translate::get('recommended posts');
            $descr  = sprintf(Translate::get('recommended-posts-desc'), $topic['topic_seo_title']) . $topic['topic_description'];
        }

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/topics/logos/' . $topic['topic_img'],
            'url'        => $url,
        ];

        return view(
            '/topic/topic',
            [
                'meta'  => meta($m, $title, $descr),
                'uid'   => $uid,
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'sheet'         => $sheet == 'feed' ? 'topic' : 'recommend',
                    'topic'         => $topic,
                    'posts'         => $result,
                    'posts'         => $result,
                    'focus_users'   => TopicModel::getFocusUsers($topic['topic_id'], 5),
                    'topic_signed'  => SubscriptionModel::getFocus($topic['topic_id'], $uid['user_id'], 'topic'),
                    'user'          => UserModel::getUser($topic['topic_user_id'], 'id'),
                    'high_topics'   => TopicModel::getHighLevelList($topic['topic_id']),
                    'low_topics'    => TopicModel::getLowLevelList($topic['topic_id']),
                    'writers'       => TopicModel::getWriters($topic['topic_id']),
                ],
                'topic'   => $topic['topic_id']
            ]
        );
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

        $topic_select = empty($topic['topic_post_related']) ? 0 : $topic['topic_post_related'];

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/topics/logos/' . $topic['topic_img'],
            'url'        => getUrlByName('topic.info', ['slug' => $topic['topic_slug']]),
        ];

        return view(
            '/topic/info',
            [
                'meta'  => meta($m, $topic['topic_seo_title'] . ' — ' .  Translate::get('topic'), $topic['topic_description']),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => 'info',
                    'topic'         => $topic,
                    'topic_related' => TopicModel::topicRelated($topic['topic_related']),
                    'post_related'  => TopicModel::topicPostRelated($topic_select),
                    'high_topics'   => TopicModel::getHighLevelList($topic['topic_id']),
                    'low_topics'    => TopicModel::getLowLevelList($topic['topic_id']),
                    'user'          => UserModel::getUser($topic['topic_user_id'], 'id'),
                    'main_topic'    => $main_topic
                ]
            ]
        );
    }

    // Подписаны (25)
    public function followers()
    {
        $topic_id   = Request::getPostInt('topic_id');
        $users      = TopicModel::getFocusUsers($topic_id, 25);

        return includeTemplate('/topic/followers', ['users' => $users]);
    }
    
    // Структура
    public function structure()
    {
        $uid        = Base::getUid();
        $structure  = TopicModel::getStructure();

        return view(
            '/topic/structure',
            [
                'meta'  => meta($m = [], Translate::get('Структура '), Translate::get('Структура темы')),
                'uid'   => $uid,
                'data'  => [
                    'sheet'     => 'structure',
                    'structure' => self::builder(0, 0, $structure),
                ]
            ]
        );
    }
    
    // Дерево (tree)
    public static function builder($topic_chaid_id, $level, $structure, array $tree = []){
        $level++;
        foreach($structure as $topic) {
            if ($topic['topic_parent_id'] == $topic_chaid_id) {
                $topic['level'] = $level-1;
                $tree[]         = $topic;
                $tree           = self::builder($topic['topic_id'], $level, $structure, $tree);
            }
        }
		return $tree;
    } 
}
