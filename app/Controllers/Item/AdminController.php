<?php

namespace App\Controllers\Item;

use App\Controllers\Controller;
use App\Models\Item\{WebModel, UserAreaModel};
use Meta;

class AdminController extends Controller
{
    protected $user_count = 0;

    public function index($sheet)
    {
        return $this->render(
            '/item/admin/index',
            [
                'meta'  => Meta::get(__('web.' . $sheet)),
                'data'  => [
                    'sheet'             => $sheet,
                    'items'             => WebModel::feedItem($this->user, false, false, $sheet, false),
                    'user_count_site'   => $this->user_count,
                    'audit_count'       => UserAreaModel::auditCount(),
                ]
            ],
            'item',
        );
    }
}
