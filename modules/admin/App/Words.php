<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use App\Models\ContentModel;
use Translate, Meta;

class Words
{
    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        return view(
            '/view/default/word/words',
            [
                'meta'  => Meta::get($m = [], Translate::get('words')),
                'data'  => [
                    'words' => ContentModel::getStopWords(),
                    'sheet' => $sheet,
                    'type'  => $type,
                ]
            ]
        );
    }

    // Форма добавления стоп-слова
    public function addPage($sheet, $type)
    {
        return view(
            '/view/default/word/add',
            [
                'meta'  => Meta::get($m = [], sprintf(Translate::get('add.option'), Translate::get('word'))),
                'data'  => [
                    'type'  => $type,
                    'sheet' => $sheet,
                ]
            ]
        );
    }

    // Добавление стоп-слова
    public function create()
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
