<?php

namespace Modules\Search\App\Tokenizers;

class AlphaNumericTokenizer implements TokenizerInterface
{
    public static function tokenize($data)
    {
        return array_map(function ($elem) {
            return preg_replace('/[^A-Za-za-яА-Я0-9 ]/', '', $elem);
        }, $data);
    }
}
