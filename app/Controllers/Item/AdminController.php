<?php

namespace App\Controllers\Item;

use App\Controllers\Controller;
use App\Models\Item\{WebModel, UserAreaModel};
use Meta;

class AdminController extends Controller
{
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
        return $this->render(
            '/item/admin/status',
            [
                'meta'  => Meta::get(__('web.status')),
                'data'  => ['status'  => []]
            ],
            'item',
        );
    }
    
    public static function httpCode($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);

        $http_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        return $http_code;
    }
}
