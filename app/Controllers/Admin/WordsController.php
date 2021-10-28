<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\ContentModel;
use Base, Translate;

class WordsController extends MainController
{
    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        return view(
            '/admin/word/words',
            [
                'meta'  => meta($m = [], Translate::get('stop words')),
                'uid'   => Base::getUid(),
                'data'  => [
                    'words' => ContentModel::getStopWords(),
                    'sheet' => $sheet == 'all' ? 'words' : $sheet,
                ]
            ]
        );
    }

    // Форма добавления стоп-слова
    public function addPage()
    {
        return view(
            '/admin/word/add',
            [
                'meta'  => meta($m = [], Translate::get('add a stop word')),
                'uid'   => Base::getUid(),
                'data'  => [
                    'sheet' => 'words',
                ]
            ]
        );
    }

    // Добавление стоп-слова
    public function add()
    {
        $data = [
            'stop_word'     => Request::getPost('word'),
            'stop_add_uid'  => 1,
            'stop_space_id' => 0, // Глобально
        ];

        ContentModel::setStopWord($data);

        redirect(getUrlByName('admin.words'));
    }

    // Удаление стоп-слова
    public function deletes()
    {
        $word_id = Request::getPostInt('id');

        ContentModel::deleteStopWord($word_id);

        redirect(getUrlByName('admin.words'));
    }
}
