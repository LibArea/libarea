<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Module;
use Modules\Admin\Models\LogModel;
use App\Models\{CommentModel, PostModel};
use Meta, Html;

class AuditsController extends Module
{
    protected $limit = 55;

    public function index()
    {
		
		$sheet = '';
		$type = '';
		
		// Route::get('/audits')->module('admin', 'App\Audits', ['all', 'audits'])->name('admin.audits');
        $pagesCount = LogModel::getAuditsAllCount($sheet);
        $audits     = LogModel::getAuditsAll(Html::pageNumber(), $this->limit, $sheet, $type);

        $result = [];
        foreach ($audits  as $ind => $row) {

            if ($row['action_type'] == 'post') {
                $row['content'] = PostModel::getPost($row['content_id'], 'id', $this->container->user()->get());
            } elseif ($row['action_type'] == 'comment') {
                $row['content'] = CommentModel::getCommentId($row['content_id']);

                $row['post'] = PostModel::getPost($row['content']['comment_post_id'], 'id', $this->container->user()->get());
            } 

            $result[$ind]   = $row;
        }

        return view(
            '/audit/audits',
            [
                'meta'  => Meta::get(__('admin.' . $type)),
                'data' => [
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => Html::pageNumber(),
                    'audits'        => $result,
                ]
            ]
        );
    }

    // Approve audit 
    // Одобрить аудит
    public function statusApproved()
    {
        $st     = Request::post('status')->value();
        $status = preg_split('/(@)/', $st);
        // id, type
        LogModel::recoveryAudit($status[0], $status[1]);

        return true;
    }

    // Ознакомился
    public function saw()
    {
        $id  = Request::post('id')->asInt();

        LogModel::setSaw($id);

        return true;
    }
}
