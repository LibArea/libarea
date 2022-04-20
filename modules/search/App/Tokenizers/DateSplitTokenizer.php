<?php

namespace Modules\Search\App\Tokenizers;

class DateSplitTokenizer implements TokenizerInterface
{
    public static function tokenize($data)
    {
        return array_map(function ($date) {
            return [$date, substr($date, 0, 10), substr($date, 11, 8)];
        }, $data);
    }
}
