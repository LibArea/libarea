<?php

namespace Modules\Search\App\Tokenizers;

use Modules\Search\App\Wamania\Romanian;

class RomanianStemmingTokenizer implements TokenizerInterface
{

    public static function tokenize($data)
    {
        $stemmer = new Romanian();
        return array_map(function ($value) use ($stemmer) {
            return array_unique([$stemmer->stem(mb_convert_encoding($value, 'UTF-8')), $value]);
        }, $data);
    }
}
