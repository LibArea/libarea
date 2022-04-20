<?php

namespace Modules\Search\App\Tokenizers;

class IntegerSeparatorTokenizer implements TokenizerInterface
{
    /**
     * Similar from DecimalSeparatorTokenizer
     * but doesn't support dots and commas
     * input = "4.1"
     * output = [ "4.1", "4", ".", "1" ]
     * @param $data
     * @return array
     */
    public static function tokenize($data)
    {
        return array_map(function ($elem) {
            preg_match_all('/([0-9]+|[^0-9]+)/', $elem, $output);
            $output = $output[0]; // we keep only the full pattern match
            array_unshift($output, $elem); // we also insert the original value
            return $output;
        }, $data);
    }
}
