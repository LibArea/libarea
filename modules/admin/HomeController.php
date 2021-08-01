<?php

namespace Modules\Admin;

use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\UserModel;
use App\Models\SpaceModel;
use App\Models\HomeModel;
use App\Models\AnswerModel;
use App\Models\CommentModel;
use App\Models\WebModel;
use App\Models\TopicModel;
use Lori\Base;

class HomeController extends \MainController
{
    public function index()
    {
        $uid    = Base::getUid();
        $size   = disk_total_space(HLEB_GLOBAL_DIRECTORY);
        $bytes  = number_format($size / 1048576, 2) . ' MB';

        $stats  = [
            'spaces_count'      => SpaceModel::getSpacesAllCount(),
            'topics_count'      => TopicModel::getTopicsAllCount(),
            'posts_count'       => HomeModel::feedCount([], $uid),
            'users_count'       => UserModel::getUsersListForAdminCount('all'),
            'answers_count'     => AnswerModel::getAnswersAllCount(),
            'comments_count'    => CommentModel::getCommentAllCount(),
            'links_count'       => WebModel::getLinksAllCount(),
        ];

        $data   = [
            'meta_title'    => lang('Admin'),
            'sheet'         => 'admin',
            'bytes'         => $bytes,
        ];

        return view('/templates/index', ['data' => $data, 'uid' => $uid, 'stats' => $stats]);
    }
}
