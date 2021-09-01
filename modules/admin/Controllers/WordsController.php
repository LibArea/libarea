<?php

namespace Modules\Admin\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\WordsModel;
use Lori\Base;

class WordsController extends MainController
{
    public function index($sheet)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $words = WordsModel::getStopWords();

        $meta = [
            'meta_title'    => lang('Stop words'),
            'sheet'         => $sheet == 'all' ? 'words' : $sheet,
        ];

        $data = [
            'words'     => $words,
        ];

        return view('/word/words', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => $data]);
    }

    // Форма добавления стоп-слова
    public function addPage()
    {
        $meta = [
            'meta_title'    => lang('Add a stop word'),
            'sheet'         => 'words',
        ];

        return view('/word/add', ['meta' => $meta, 'uid' => Base::getUid(), 'data' => []]);
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
