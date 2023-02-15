<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\FolderModel;

class FolderController extends Controller
{
    public function get($type)
    {
        FolderModel::get($type);
    }

    public function create()
    {
        $cat    = Request::getPost() ?? [];
        $arr    = $cat['cat-outside'] ?? [];

        $url    = url('favorites.folders');
        if (empty($arr)) {
            is_return(__('app.necessarily'), 'success', $url);
        }

        $folders = json_decode($arr, true);

        FolderModel::create($folders, 'favorite');

        redirect($url);
    }

    // Deleting the linked content folder
    // Удаление папки привязанному контенту
    public function delFolderContent()
    {
        $tid    = Request::getPostInt('tid');
        $type   = Request::getPost('type');

        return FolderModel::deletingFolderContent($tid, $type);
    }

    // Link folder to content 
    // Привязываем папку к контенту
    public function addFolderContent()
    {
        $id     = Request::getPostInt('id');
        $tid    = Request::getPostInt('tid');
        $type   = Request::getPost('type');

        $allowed = ['favorite', 'blog'];
        if (!in_array($type, $allowed)) return false;

        FolderModel::saveFolderContent($id, $tid, $type);
    }

    // Delete the folder itself
    // Удаляем саму папку
    public function delFolder()
    {
        $id     = Request::getPostInt('id');
        $type   = Request::getPost('type');

        return FolderModel::deletingFolder($id, $type);
    }
}
