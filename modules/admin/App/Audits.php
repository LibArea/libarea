<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Models\{PostModel, AnswerModel, CommentModel};
use Modules\Admin\App\Models\LogModel;
use Translate, UserData, Meta, Tpl;

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
        $pageNumber = Tpl::pageNumber();

        $pagesCount = LogModel::getAuditsAllCount($sheet, $type);
        $audits     = LogModel::getAuditsAll($pageNumber, $this->limit, $sheet, $type);

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
                'meta'  => Meta::get(Translate::get($type)),
                'data' => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'audits'        => $result,
                ]
            ]
        );
    }

    // Log log
    // Журнал логов
    public function logs($sheet, $type)
    {
        $pageNumber = Tpl::pageNumber();

        $logs       = LogModel::getLogs($pageNumber, $this->limit);
        $pagesCount = LogModel::getLogsCount();

        return view(
            '/view/default/audit/logs',
            [
                'meta'  => Meta::get(Translate::get('logs')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $pageNumber,
                    'type'          => $type,
                    'sheet'         => $sheet,
                    'logs'          => $logs,
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
