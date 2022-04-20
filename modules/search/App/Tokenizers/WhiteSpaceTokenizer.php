<?php

namespace Modules\Search\App\Tokenizers;

class WhiteSpaceTokenizer implements TokenizerInterface
{

    public static function tokenize($data)
    {
        return array_map(function ($elem) {
            return preg_split("/\s/", $elem);
        }, $data);
    }
}
