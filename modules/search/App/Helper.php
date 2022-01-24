<?php

namespace Modules\Search\App;

use Content;

class Helper
{
    public static function handler($query)
    {
        if (!$query) {
            return true;
        }


        $result = [];
        foreach ($query as $ind => $row) {


            $text = self::fragment($row['content']);
            $row['content']     = Content::text($text, 'line');
            $result[$ind]       = $row;
        }

        return $result;
    }

    public static function fragment($content, $maxlen = '220')
    {
        $text = explode("\n", $content);
        $words = preg_split('#[\s\r\n]+#um', $text[0]);

        if ($maxlen < count($words)) {
            $words = array_slice($words, 0, $maxlen);
            return join(' ', $words) . '...';
        }

        return $text[0];
    }
}
