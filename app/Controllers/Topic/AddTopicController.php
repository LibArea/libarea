<?php

namespace App\Controllers\Topic;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\TopicModel;
use Base, Validation, Config, Translate;

class AddTopicController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    // Форма добавить topic
    public function index()
    {
        $count_topic        = TopicModel::countTopicsUser($this->uid['user_id']);
        $count_add_topic    = $this->uid['user_trust_level'] == 5 ? 999 : Config::get('trust-levels.count_add_topic');

        $valid = Validation::validTl($this->uid['user_trust_level'], Config::get('trust-levels.tl_add_topic'), $count_topic, $count_add_topic);
        if ($valid === false) {
            redirect('/');
        }

        return view(
            '/topic/add',
            [
                'meta'  => meta($m = [], Translate::get('add topic')),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => 'topics',
                    'count_topic'   => $count_add_topic - $count_topic,
                ]
            ]
        );
    }

    // Добавим topic
    public function create()
    {
        $tl     = Validation::validTl($this->uid['user_trust_level'], 5, 0, 1);
        if ($tl === false) {
            redirect('/');
        }

        $topic_title                = Request::getPost('topic_title');
        $topic_description          = Request::getPost('topic_description');
        $topic_short_description    = Request::getPost('topic_short_description');
        $topic_slug                 = Request::getPost('topic_slug');
        $topic_seo_title            = Request::getPost('topic_seo_title');
        $topic_merged_id            = Request::getPost('topic_merged_id');
        $topic_related              = Request::getPost('topic_related');

        $redirect = getUrlByName('topic.add');

        Validation::charset_slug($topic_slug, 'Slug (url)', $redirect);
        Validation::Limits($topic_title, Translate::get('title'), '3', '64', $redirect);
        Validation::Limits($topic_description, Translate::get('neta description'), '44', '225', $redirect);
        Validation::Limits($topic_short_description, Translate::get('short description'), '11', '160', $redirect);
        Validation::Limits($topic_slug, Translate::get('slug'), '3', '43', $redirect);
        Validation::Limits($topic_seo_title, Translate::get('slug'), '4', '225', $redirect);

        if (TopicModel::getTopic($topic_slug, 'slug')) {
            addMsg(Translate::get('url-already-exists'), 'error');
            redirect($redirect);
        }

        if (preg_match('/\s/', $topic_slug) || strpos($topic_slug, ' ')) {
            addMsg(Translate::get('url-gaps'), 'error');
            redirect($redirect);
        }

        $topic_slug = strtolower($topic_slug);
        $data = [
            'topic_title'               => $topic_title,
            'topic_description'         => $topic_description,
            'topic_short_description'   => $topic_short_description,
            'topic_slug'                => $topic_slug,
            'topic_img'                 => 'topic-default.png',
            'topic_add_date'            =>  date("Y-m-d H:i:s"),
            'topic_seo_title'           => $topic_seo_title,
            'topic_merged_id'           => $topic_merged_id ?? 0,
            'topic_user_id'             => $this->uid['user_id'],
            'topic_related'             => $topic_related ?? 0,
            'topic_count'               => 0,
        ];

        $topic = TopicModel::add($data);

        redirect(getUrlByName('topic', ['slug' => $topic_slug]));
    }
}
