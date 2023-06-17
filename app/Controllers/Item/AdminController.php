<?php

namespace App\Controllers\Item;

use App\Controllers\Controller;
use Hleb\Constructor\Handlers\Request;
use App\Models\Item\{WebModel, UserAreaModel};
use Meta, Msg;

class AdminController extends Controller
{
    protected $limit = 15;
    protected $user_count = 0;

    public function audits()
    {
        return $this->render(
            '/item/admin/audits',
            [
                'meta'  => Meta::get(__('web.audits')),
                'data'  => [
                    'sheet'             => 'audits',
                    'items'             => WebModel::feedItem(false, false, 'audits', false),
                    'user_count_site'   => $this->user_count,
                    'audit_count'       => UserAreaModel::auditCount(),
                ]
            ],
            'item',
        );
    }
    
    public function deleted()
    {
        return $this->render(
            '/item/admin/deleted',
            [
                'meta'  => Meta::get(__('web.deleted')),
                'data'  => ['items' => WebModel::feedItem(false, false, 'deleted', false)]
            ],
            'item',
        );
    }
    
    public function comments()
    {
        return $this->render(
            '/item/admin/comments',
            [
                'meta'  => Meta::get(__('web.comments')),
                'data'  => ['comments'  => WebModel::getComments(20)]
            ],
            'item',
        );
    }
    
    public function status()
    {
        Request::getResources()->addBottomScript('/assets/js/catalog.js');
        
        $code = Request::get('code');
        
        return $this->render(
            '/item/admin/status',
            [
                'meta'  => Meta::get(__('web.status')),
                'data'  => [
                    'pagesCount'    => ceil($pagesCount / $this->limit),
                    'pNum'          => $this->pageNumber,
                    'status'        => WebModel::getStatus($this->pageNumber, $code), 
                    'code'          => $code
                ]
            ],
            'item',
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
    
    // Once a month
    public static function updateStatus()
    {
        $items = WebModel::getForStatus();
        foreach ($items as $row) {
            WebModel::statusUpdate($row['item_id'], self::httpCode($row['item_url']));
        }

        Msg::add(__('admin.completed'), 'success');
    }
}
