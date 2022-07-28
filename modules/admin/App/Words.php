<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Admin\App\Models\WordModel;
use Meta, Validation;

class Words
{
    protected $type = 'words';

    public function index()
    {
        return view(
            '/view/default/word/words',
            [
                'meta'  => Meta::get(__('admin.words')),
                'data'  => [
                    'words' => WordModel::getStopWords(),
                    'type'  => $this->type,
                ]
            ]
        );
    }

    // Форма добавления стоп-слова
    public function add()
    {
        return view(
            '/view/default/word/add',
            [
                'meta'  => Meta::get(__('admin.word')),
                'data'  => [
                    'type'  => $this->type,
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

        WordModel::setStopWord($data);

        is_return(__('msg.change_saved'), 'success', url('admin.words'));
    }

    // Удаление стоп-слова
    public function deletes()
    {
        $word_id = Request::getPostInt('id');

        WordModel::deleteStopWord($word_id);
    }
}
