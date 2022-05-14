<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\FolderModel;
use Validation;

class FolderController extends Controller
{
    public function get($type)
    {
        FolderModel::get($type, $this->user['id']);
    }

    public function set()
    {
        $cat    = Request::getPost() ?? [];
        $arr    = $cat['cat-outside'] ?? [];

        $url    = url('favorites.folders');
        if (empty($arr)) {
            Validation::ComeBack('app.necessarily', 'success', $url);
        }

        $folders = json_decode($arr, true);

        FolderModel::create($folders, 'favorite', $this->user['id']);

        redirect($url);
    }

    // Deleting the linked content folder
    // Удаление папки привязанному контенту
    public function delFolderContent()
    {
        $tid    = Request::getPostInt('tid');
        $type   = Request::getPost('type');

        return FolderModel::deletingFolderContent($tid, $type, $this->user['id']);
    }

    // Link folder to content 
    // Привязываем папку к контенту
    public function addFolderContent()
    {
        $id     = Request::getPostInt('id');
        $type   = Request::getPost('type');
        $tid    = Request::getPostInt('tid');

        $allowed = ['favorite', 'blog'];
        if (!in_array($type, $allowed)) return false;

        FolderModel::saveFolderContent($id, $tid, $type, $this->user['id']);
    }

    // Delete the folder itself
    // Удаляем саму папку
    public function delFolder()
    {
        $id     = Request::getPostInt('id');
        $type   = Request::getPost('type');

        return FolderModel::deletingFolder($id, $type, $this->user['id']);
    }
}
