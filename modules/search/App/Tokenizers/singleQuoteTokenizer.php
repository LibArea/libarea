<?php

namespace Modules\Search\App\Tokenizers;

class singleQuoteTokenizer implements TokenizerInterface
{
    public static function tokenize($data)
    {
        return array_map(function ($elem) {
            $elem = str_replace('’', '\'', $elem);
            return explode("'", $elem); // TODO : preg_replace single quotes
        }, $data);
    }
}
