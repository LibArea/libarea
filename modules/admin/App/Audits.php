<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Models\{PostModel, AnswerModel, CommentModel, AuditModel};
use Translate, UserData;

class Audits
{
    private $user;

    public function __construct()
    {
        $this->user = UserData::get();
    }

    protected $limit = 55;

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $pagesCount = AuditModel::getAuditsAllCount($sheet);
        $audits     = AuditModel::getAuditsAll($page, $this->limit, $sheet);

        $result = [];
        foreach ($audits  as $ind => $row) {

            if ($row['audit_type'] == 'post') {
                $row['content'] = PostModel::getPost($row['audit_content_id'], 'id', $this->user);
            } elseif ($row['audit_type'] == 'answer') {
                $row['content'] = AnswerModel::getAnswerId($row['audit_content_id']);

                $row['post'] = PostModel::getPost($row['content']['answer_post_id'], 'id', $this->user);
            } elseif ($row['audit_type'] == 'comment') {
                $row['content'] = CommentModel::getCommentsId($row['audit_content_id']);
            }

            $result[$ind]   = $row;
        }

        return view(
            '/view/default/audit/audits',
            [
                'meta'  => meta($m = [], Translate::get('audit')),
                'data' => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $page,
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
        AuditModel::recoveryAudit($status[0], $status[1]);

        return true;
    }
}
