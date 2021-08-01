<?php

namespace App\Controllers\Space;

use Hleb\Constructor\Handlers\Request;
use App\Models\SpaceModel;
use App\Models\FeedModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class SpaceController extends \MainController
{
    // Все пространства сайта
    public function index()
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 25;
        $pagesCount = SpaceModel::getSpacesAllCount();
        $spaces     = SpaceModel::getSpacesAll($page, $limit, $uid['id'], 'all');

        Base::PageError404($spaces);

        // Введем ограничение на количество создаваемых пространств (кроме tl5)
        $sp                 = SpaceModel::getUserCreatedSpaces($uid['id']);
        $count_space        = count($sp);
        $total_allowed = $uid['trust_level'] == 5 ? 999 : 3;
        $add_space_button   = validTl($uid['trust_level'], Config::get(Config::PARAM_TL_ADD_SPACE), $count_space, $total_allowed);

        $result = array();
        foreach ($spaces as $ind => $row) {
            $row['users']   = SpaceModel::numSpaceSubscribers($row['space_id']);
            $result[$ind]   = $row;
        }

        Request::getHead()->addStyles('/assets/css/space.css');

        $data = [
            'h1'            => lang('All space'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/spaces',
            'sheet'         => 'spaces',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'meta_title'    => lang('All space') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('all-space-desc') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/space/spaces', ['data' => $data, 'uid' => $uid, 'spaces' => $result, 'add_space_button' => $add_space_button]);
    }

    // Пространства участника
    public function spaseUser()
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $limit  = 25;

        $pagesCount = SpaceModel::getSpacesAllCount();
        $space      = SpaceModel::getSpacesAll($page, $limit, $uid['id'], 'subscription');

        // Введем ограничение на количество создаваемых пространств
        $all_space          = SpaceModel::getUserCreatedSpaces($uid['id']);
        $count_space        = count($all_space);
        $add_space_button   = validTl($uid['trust_level'], Config::get(Config::PARAM_TL_ADD_SPACE), $count_space, 3);

        $result = array();
        foreach ($space as $ind => $row) {
            $row['users']   = SpaceModel::numSpaceSubscribers($row['space_id']);
            $result[$ind]   = $row;
        }

        Request::getHead()->addStyles('/assets/css/space.css');

        $data = [
            'h1'            => lang('I read space'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/space/my',
            'sheet'         => 'my-space',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'meta_title'    => lang('I read space') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('I read space') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/space/spaces', ['data' => $data, 'uid' => $uid, 'spaces' => $result, 'add_space_button' => $add_space_button]);
    }

    // Посты по пространству
    public function posts($sheet)
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');
        $page   = \Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $space = SpaceModel::getSpace($slug, 'slug');
        Base::PageError404($space);

        $limit = 25;

        $data = ['space_id' => $space['space_id']];
        $posts      = FeedModel::feed($page, $limit, $uid, $sheet, 'space', $data);
        $pagesCount = FeedModel::feedCount($uid, 'space', $data);

        $space['space_date']        = lang_date($space['space_date']);
        $space['space_cont_post']   = count($posts);
        $space['space_text']        = Content::text($space['space_text'], 'text');

        $result = array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $space['users'] = SpaceModel::numSpaceSubscribers($space['space_id']);

        // Отписан участник от пространства или нет
        $space_signed = SpaceModel::getMyFocus($space['space_id'], $uid['id']);

        if ($sheet == 'feed') {
            $s_title = lang('space-feed-title');
        } else {
            $s_title = lang('space-top-title');
        }

        Request::getHead()->addStyles('/assets/css/space.css');


        $num = ' | ';
        if ($page > 1) {
            $num = sprintf(lang('page-number'), $page);
        }

        $data = [
            'h1'            => $space['space_name'],
            'canonical'     => Config::get(Config::PARAM_URL) . '/s/' . $space['space_slug'],
            'img'           => Config::get(Config::PARAM_URL) . '/uploads/spaces/logos/' . $space['space_img'],
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'sheet'         => $sheet,
            'meta_title'    => $space['space_name'] . $num . $s_title . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => $space['space_description'] . $num . $s_title . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        return view(PR_VIEW_DIR . '/space/space', ['data' => $data, 'uid' => $uid, 'posts' => $result, 'space_info' => $space, 'space_signed' => $space_signed]);
    }

    // Подписка / отписка от пространств
    public function focus()
    {
        $uid        = Base::getUid();
        $space_id   = \Request::getPostInt('space_id');

        // Запретим, если участник создал пространство
        $sp_info    = SpaceModel::getSpace($space_id, 'id');
        if ($sp_info['space_user_id'] == $uid['id']) {
            return false;
        }

        SpaceModel::focus($space_id, $uid['id']);

        return true;
    }
}
