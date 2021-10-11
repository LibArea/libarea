<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\ContentModel;
use Base;

class WordsController extends MainController
{
    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $words = ContentModel::getStopWords();

        Request::getResources()->addBottomScript('/assets/js/admin.js');

        $meta = meta($m = [], lang('stop words'));
        $data = [
            'words'     => $words,
            'sheet'     => $sheet == 'all' ? 'words' : $sheet,
        ];

        return view('/admin/word/words', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    // Форма добавления стоп-слова
    public function addPage()
    {
        $meta = meta($m = [], lang('add a stop word'));
        $data = [
            'sheet'         => 'words',
        ];

        return view('/admin/word/add', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    // Добавление стоп-слова
    public function add()
    {
        $word = Request::getPost('word');
        $data = [
            'stop_word'     => $word,
            'stop_add_uid'  => 1,
            'stop_space_id' => 0, // Глобально
        ];

        ContentModel::setStopWord($data);

        redirect('/admin/words');
    }

    // Удаление стоп-слова
    public function deletes()
    {
        $word_id    = Request::getPostInt('id');

        ContentModel::deleteStopWord($word_id);

        redirect('/admin/words');
    }
}
