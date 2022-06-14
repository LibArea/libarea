<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\{PostModel, AnswerModel, CommentModel};
use Modules\Admin\App\Models\LogModel;
use Meta;

class Audits extends Controller
{
    protected $limit = 55;

    public function index($sheet, $type)
    {
        $pagesCount = LogModel::getAuditsAllCount($sheet);
        $audits     = LogModel::getAuditsAll($this->pageNumber, $this->limit, $sheet, $type);

        $result = [];
        foreach ($audits  as $ind => $row) {

            if ($row['action_type'] == 'post') {
                $row['content'] = PostModel::getPost($row['content_id'], 'id', $this->user);
            } elseif ($row['action_type'] == 'answer') {
                $row['content'] = AnswerModel::getAnswerId($row['content_id']);

                $row['post'] = PostModel::getPost($row['content']['answer_post_id'], 'id', $this->user);
            } elseif ($row['action_type'] == 'comment') {
                $row['content'] = CommentModel::getCommentsId($row['content_id']);
            }

            $result[$ind]   = $row;
        }

        return view(
            '/view/default/audit/audits',
            [
                'meta'  => Meta::get(__('admin.' . $type)),
                'data' => [
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'audits'        => $result,
                ]
            ]
        );
    }

    // Approve audit 
    // Одобрить аудит
    public function status()
    {
        $st     = Request::getPost('status');
        $status = preg_split('/(@)/', $st);
        // id, type
        LogModel::recoveryAudit($status[0], $status[1]);

        return true;
    }

    // Ознакомился
    public function saw()
    {
        $id  = Request::getPostInt('id');

        LogModel::setSaw($id);

        return true;
    }
}
