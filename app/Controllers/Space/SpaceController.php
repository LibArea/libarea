<?php

namespace App\Controllers\Space;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{SubscriptionModel, SpaceModel, FeedModel};
use Content, Config, Base, Validation;

class SpaceController extends MainController
{
    // Все пространства сайта
    public function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 25;
        $pagesCount = SpaceModel::getSpacesAllCount();
        $spaces     = SpaceModel::getSpacesAll($page, $limit, $uid['user_id'], 'all');

        Base::PageError404($spaces);

        // Введем ограничение на количество создаваемых пространств (кроме tl5)
        $sp                 = SpaceModel::getUserCreatedSpaces($uid['user_id']);
        $count_space        = count($sp);
        $total_allowed      = $uid['user_trust_level'] == 5 ? 999 : 3;
        $add_space_button   = Validation::validTl($uid['user_trust_level'], Config::get('trust-levels.tl_add_space'), $count_space, $total_allowed);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('spaces'),
        ];
        $meta = meta($m, lang('all space'), lang('all-space-desc'));

        $data = [
            'h1'                => lang('all space'),
            'sheet'             => 'spaces',
            'pagesCount'        => ceil($pagesCount / $limit),
            'pNum'              => $page,
            'spaces'            => $spaces,
            'add_space_button'  => $add_space_button
        ];

        return view('/space/spaces', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Пространства участника
    public function spaseUser()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
        $limit  = 25;

        $pagesCount = SpaceModel::getSpacesAllCount();
        $space      = SpaceModel::getSpacesAll($page, $limit, $uid['user_id'], 'subscription');

        // Введем ограничение на количество создаваемых пространств
        $all_space          = SpaceModel::getUserCreatedSpaces($uid['user_id']);
        $add_space_button   = Validation::validTl($uid['user_trust_level'], Config::get('trust-levels.tl_add_space'), count($all_space), 3);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => false,
        ];
        $meta = meta($m, lang('i read space'), $desc = '');

        $data = [
            'h1'                => lang('i read space'),
            'sheet'             => 'my-space',
            'pagesCount'        => ceil($pagesCount / $limit),
            'pNum'              => $page,
            'spaces'            => $space,
            'add_space_button'  => $add_space_button,
        ];

        return view('/space/spaces', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Посты по пространству
    public function posts($sheet)
    {
        $uid    = Base::getUid();
        $slug   = Request::get('slug');
        $page   = Request::getInt('page');
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

        $post_result = array();
        foreach ($posts as $ind => $row) {
            $text = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('answer'), lang('answers-m'), lang('answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $post_result[$ind]              = $row;
        }

        // Отписан участник от пространства или нет
        $space_signed = SubscriptionModel::getFocus($space['space_id'], $uid['user_id'], 'space');

        $num = $page > 1 ? sprintf(lang('page-number'), $page) : '';

        $writers = [];
        if ($sheet == 'writers') {
            $writers = SpaceModel::getWriters($space['space_id']);
        }

        $m = [
            'og'         => true,
            'twitter'    => true,
            'imgurl'     => '/uploads/spaces/logos/' . $space['space_img'],
            'url'        => getUrlByName('space', ['slug' => $space['space_slug']]),
        ];
        $meta = meta(
            $m,
            $title = $space['space_name'] . ' — ' . lang('space-' . $sheet . '-title') . ' ' . $num,
            $title . $space['space_description']
        );

        $data = [
            'sheet'         => $sheet,
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'posts'         => $post_result,
            'space'         => $space,
            'signed'        => $space_signed,
            'writers'       => $writers,
        ];

        return view('/space/space', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    public function wiki()
    {
        // Under development
        $wiki = [];

        $slug   = Request::get('slug');
        $space = SpaceModel::getSpace($slug, 'slug');
        Base::PageError404($space);

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName('space.wiki', ['slug' => $space['space_slug']]),
        ];
        $meta = meta($m, lang('wiki space'), lang('wiki-space-desc'));

        $data = [
            'h1'            => lang('wiki space'),
            'sheet'         => 'wiki',
            'wiki'          => $wiki,
        ];

        return view('/space/wiki', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }
}
