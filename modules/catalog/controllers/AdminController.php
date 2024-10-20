<?php

declare(strict_types=1);

namespace Modules\Catalog\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Module;
use Modules\Catalog\Models\{WebModel, UserAreaModel};
use Meta, Msg, Html;

class AdminController extends Module
{
    protected const LIMIT = 15;
    protected const USER_COUNT = 0;

    public function audits()
    {
        return view(
            '/admin/audits',
            [
                'meta'  => Meta::get(__('web.audits')),
                'data'  => [
                    'sheet'             => 'audits',
                    'items'             => WebModel::feedItem(false, false, Html::pageNumber(), 'audits', false),
                    'user_count_site'   => self::USER_COUNT,
                    'audit_count'       => UserAreaModel::auditCount(),
                ]
            ],
        );
    }

    public function deleted()
    {
        return view(
            '/admin/deleted',
            [
                'meta'  => Meta::get(__('web.deleted')),
                'data'  => ['items' => WebModel::feedItem(false, false, Html::pageNumber(), 'deleted', false)]
            ],
        );
    }

    public function comments()
    {
        return view(
            '/admin/comments',
            [
                'meta'  => Meta::get(__('web.comments')),
                'data'  => ['comments'  => WebModel::getComments(20)]
            ],
        );
    }

    public function status()
    {
        $code = Request::param('code')->asInt();

        $pagesCount = 0; // TODO

        return view(
            '/admin/status',
            [
                'meta'  => Meta::get(__('web.status')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / self::LIMIT),
                    'pNum'          => Html::pageNumber(),
                    'status'        => WebModel::getStatus(Html::pageNumber(), $code),
                    'code'          => $code
                ]
            ],
        );
    }

    public static function httpCode($url)
    {
        stream_context_set_default([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $headers = get_headers($url);

        return (empty($headers[0])) ? 404 : substr($headers[0], 9, 3);
    }

    /**
     * Once a month
     *
     * @return void
     */
    public static function updateStatus()
    {
        $items = WebModel::getForStatus();
        foreach ($items as $row) {
            WebModel::statusUpdate($row['item_id'], self::httpCode($row['item_url']));
        }

        Msg::add(__('admin.completed'), 'success');
    }
}
