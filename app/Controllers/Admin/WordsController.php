<?php

namespace App\Controllers\Admin;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\Admin\WordsModel;
use Agouti\Base;

class WordsController extends MainController
{
    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $words = WordsModel::getStopWords();

        Request::getResources()->addBottomScript('/assets/js/admin.js');
        $meta = [
            'meta_title'    => lang('stop words'),
            'sheet'         => $sheet == 'all' ? 'words' : $sheet,
        ];

        $data = [
            'words'     => $words,
            'sheet'     => $sheet == 'all' ? 'words' : $sheet,
        ];

        return view('/admin/word/words', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    // Форма добавления стоп-слова
    public function addPage()
    {
        $meta = [
            'meta_title'    => lang('add a stop word'),
            'sheet'         => 'words',
        ];

        return view('/admin/word/add', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => []]);
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

        WordsModel::setStopWord($data);

        redirect('/admin/words');
    }

    // Удаление стоп-слова
    public function deletes()
    {
        $word_id    = Request::getPostInt('id');

        WordsModel::deleteStopWord($word_id);

        redirect('/admin/words');
    }
}
