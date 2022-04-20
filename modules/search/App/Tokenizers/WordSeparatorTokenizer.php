<?php

namespace Modules\Search\App\Tokenizers;

class WordSeparatorTokenizer implements TokenizerInterface
{
    public static function tokenize($data)
    {
        return array_map(function ($elem) {
            return preg_split("/(\s|-|_|\/|'|’)/", $elem);
        }, $data);
    }
}
