<?php

namespace App\Models;
use XdORM\XD;

class ContentModel extends \MainModel
{
    // Получим список запрещенных стоп-слов
    public static function getStopWords()
	{
        return XD::select('*')->from(['stop_words'])->getSelect();
	}

    // Добавить стоп-слово
    public static function setStopWord($data)
    {
        XD::insertInto(['stop_words'], '(',
            ['stop_word'], ',', 
            ['stop_add_uid'], ',',
            ['stop_space_id'], ')')->values( '(', 
        
        XD::setList([
            $data['stop_word'], 
            $data['stop_add_uid'],
            $data['stop_space_id']]), ')' )->run();

        return true;
    }
    
    // Удалить стоп-слово
    public static function deleteStopWord($word_id)
    {
        return XD::deleteFrom(['stop_words'])->where(['stop_id'], '=', $word_id)->run(); 
    } 
}
