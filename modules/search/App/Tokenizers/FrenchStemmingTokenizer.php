<?php

namespace Modules\Search\App\Tokenizers;

use Modules\Search\App\Wamania\French;

class FrenchStemmingTokenizer implements TokenizerInterface
{

    public static function tokenize($data)
    {
        $stemmer = new French();
        return array_map(function ($value) use ($stemmer) {
            return array_unique([$stemmer->stem(mb_convert_encoding($value, 'UTF-8')), $value]);
        }, $data);
    }
}
