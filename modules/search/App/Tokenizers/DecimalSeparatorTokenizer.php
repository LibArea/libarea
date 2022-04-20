<?php

namespace Modules\Search\App\Tokenizers;

class DecimalSeparatorTokenizer implements TokenizerInterface
{
    /**
     * example : input = "484,3ddzd8,.1dz..dz4d4,4,4d15.8@@"
     * output = [
     *     "484,3ddzd8,.1dz..dz4d4,4,4d15.8@@",
     *     "484,3",
     *     "ddzd",
     *     "8",
     *     ",.",
     *     "1",
     *     "dz..dz",
     *     "4",
     *     "d",
     *     "4,4,4",
     *     "d",
     *     "15.8",
     *     "@@"
     * ]
     *
     * @param $data
     * @return array
     */
    public static function tokenize($data)
    {
        return array_map(function ($elem) {
            preg_match_all('/([0-9]+([.,]?[0-9]+)*|[^0-9]+)/', $elem, $output);
            $output = $output[0]; // we keep only the full pattern match
            array_unshift($output, $elem); // we also insert the original value
            return $output;
        }, $data);
    }
}
