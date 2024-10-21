<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Constructor\Data\View;
use Hleb\Static\Request;
use Hleb\Base\Module;
use Modules\Admin\Models\WordModel;
use Meta, Msg;

class WordsController extends Module
{
    protected $type = 'words';

    public function index()
    {
        return view(
            '/word/words',
            [
                'meta'  => Meta::get(__('admin.words')),
                'data'  => [
                    'words' => WordModel::getStopWords(),
                    'type'  => $this->type,
                ]
            ]
        );
    }

    /**
     * The form for adding a stop word
     * Форма добавления стоп-слова
     */
    public function add(): View
    {
        return view(
            '/word/add',
            [
                'meta'  => Meta::get(__('admin.word')),
                'data'  => [
                    'type'  => $this->type,
                ]
            ]
        );
    }

    /**
     * Adding a stop word
     * Добавление стоп-слова
     *
     * @return void
     */
    public function create()
    {
        $word = Request::post('word')->asString();

        if (!$word) Msg::redirect(__('msg.change_saved'), 'error', url('words.add'));

        $data = [
            'stop_word'     => Request::post('word')->asString(),
            'stop_add_uid'  => 1,
            'stop_space_id' => 0, // Глобально
        ];

        WordModel::setStopWord($data);

        Msg::redirect(__('msg.change_saved'), 'success', url('admin.words'));
    }

    /**
     * Deleting a stop word
     * Удаление стоп-слова
     *
     * @return void
     */
    public function deletes()
    {
        $word_id = Request::post('id')->asInt();

        WordModel::deleteStopWord($word_id);
    }
}
