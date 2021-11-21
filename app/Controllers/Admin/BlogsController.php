<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\FacetModel;
use Base, Translate;

class BlogsController extends MainController
{
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = FacetModel::getFacetsAllCount($uid['user_id'], $sheet);
        $blogs = FacetModel::getFacetsAll($page, $limit, $uid['user_id'], $sheet);

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return view(
            '/admin/blog/blogs',
            [
                'meta'  => meta($m = [], Translate::get('blogs')),
                'uid'   => $uid,
                'data'  => [
                    'sheet'         => $sheet == 'admin.blogs.all' ? 'blogs' : $sheet,
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'blogs'        => $blogs,
                ]
            ]
        );
    }
}
