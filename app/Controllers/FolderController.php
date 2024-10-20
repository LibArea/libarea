<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\FolderModel;
use Msg;

class FolderController extends Controller
{
    public function get($type)
    {
        FolderModel::get($type);
    }

    public function add()
    {
        $cat    = Request::allPost() ?? [];
        $arr    = $cat['cat-outside'] ?? [];

        $url    = url('favorites.folders');
        if (empty($arr)) {
            Msg::redirect(__('app.necessarily'), 'success', $url);
        }

        $folders = json_decode($arr, true);

        FolderModel::create($folders, 'favorite');

        redirect($url);
    }

    /**
     * Deleting the linked content folder
     * Удаление папки привязанному контенту
     */
    public function delFolderContent()
    {
        $tid    = Request::post('tid')->asInt();
        $type   = Request::post('type')->value();

        FolderModel::deletingFolderContent($tid, $type);

        return true;
    }

    /**
     * Link folder to content 
     * Привязываем папку к контенту
     *
     * @return void
     */
    public function addFolderContent()
    {
        $id     = Request::post('id')->asInt();
        $tid    = Request::post('tid')->asInt();
        $type   = Request::post('type')->value();

        $allowed = ['favorite', 'blog'];
        if (!in_array($type, $allowed, true)) return;

        FolderModel::saveFolderContent($id, $tid, $type);
    }

    /**
     * Delete the folder itself
     * Удаляем саму папку
     */
    public function delFolder(): true
    {
        $id     = Request::post('id')->asInt();
        $type   = Request::post('type')->value();

        FolderModel::deletingFolder($id, $type);

        return true;
    }
}
