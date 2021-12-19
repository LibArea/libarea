<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\{HomeModel, AnswerModel, CommentModel, WebModel, FacetModel};
use App\Models\Admin\UserModel;
use Base, Translate;

class HomeController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    public function index()
    {
        $size   = disk_total_space(HLEB_GLOBAL_DIRECTORY);
        $bytes  = number_format($size / 1048576, 2) . ' MB';

        return render(
            '/admin/index',
            [
                'meta'  => meta($m = [], Translate::get('admin')),
                'uid'   => $this->uid,
                'data'  => [
                    'topics_count'      => FacetModel::getFacetsAllCount($this->uid['user_id'], 'all'),
                    'posts_count'       => HomeModel::feedCount([], $this->uid, 'all'),
                    'posts_no_topic'    => FacetModel::getNoTopic(),
                    'users_count'       => UserModel::getUsersCount('all'),
                    'answers_count'     => AnswerModel::getAnswersAllCount('all'),
                    'comments_count'    => CommentModel::getCommentsAllCount('all'),
                    'items_count'       => WebModel::getItemsAllCount(),
                    'last_visit'        => UserModel::getLastVisit(),
                    'bytes'             => $bytes,
                    'type'              => 'admin',
                    'sheet'             => 'admin',
                ]
            ]
        );
    }
}
