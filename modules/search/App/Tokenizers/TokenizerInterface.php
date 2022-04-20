<?php

namespace Modules\Search\App\Tokenizers;

interface TokenizerInterface
{
    public static function tokenize($data);
}
