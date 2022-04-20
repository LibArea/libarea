<?php

namespace Modules\Search\App\Tokenizers;

use Modules\Search\App\Wamania\Spanish;

class SpanishStemmingTokenizer implements TokenizerInterface
{

    public static function tokenize($data)
    {
        $stemmer = new Spanish();
        return array_map(function ($value) use ($stemmer) {
            return array_unique([$stemmer->stem(mb_convert_encoding($value, 'UTF-8')), $value]);
        }, $data);
    }
}
