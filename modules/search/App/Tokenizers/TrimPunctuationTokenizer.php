<?php

namespace Modules\Search\App\Tokenizers;

class TrimPunctuationTokenizer implements TokenizerInterface
{
    public static function tokenize($data)
    {
        return array_map(function($elem){
            return trim($elem, ",?;.:!()/\\-_'\""); // TODO : preg_replace
        }, $data);
    }
}
