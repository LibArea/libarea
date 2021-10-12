<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\{SpaceModel, HomeModel, AnswerModel, CommentModel, TopicModel, AgentModel, WebModel};
use App\Models\User\UserModel;
use Base;

class HomeController extends MainController
{
    public function index()
    {
        $uid    = Base::getUid();
        $size   = disk_total_space(HLEB_GLOBAL_DIRECTORY);
        $bytes  = number_format($size / 1048576, 2) . ' MB';

        $meta = meta($m = [], lang('admin'));
        $data  = [
            'spaces_count'      => SpaceModel::getSpacesAllCount(),
            'topics_count'      => TopicModel::getTopicsAllCount(),
            'posts_count'       => HomeModel::feedCount([], $uid),
            'users_count'       => UserModel::getUsersAllCount('all'),
            'answers_count'     => AnswerModel::getAnswersAllCount('all'),
            'comments_count'    => CommentModel::getCommentsAllCount('all'),
            'links_count'       => WebModel::getLinksAllCount(),
            'last_visit'        => AgentModel::getLastVisit(),
            'bytes'             => $bytes,
            'sheet'             => 'admin',
        ];

        return view('/admin/index', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
