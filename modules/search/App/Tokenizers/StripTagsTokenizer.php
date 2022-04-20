<?php

namespace Modules\Search\App\Tokenizers;

class StripTagsTokenizer implements TokenizerInterface
{
    public static function tokenize($data)
    {
        return array_map("strip_tags", $data);
    }
}
