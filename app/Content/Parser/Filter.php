<?php

declare(strict_types=1);

namespace App\Content\Parser;

use Parsedown;
use LitEmoji\LitEmoji;

class Filter
{
    // Content management
    public static function noHTML(string $content, int $lenght = 150)
    {
        $Parsedown = new Parsedown();

        // Get html with minimal parsing (line = no formatting)
        // Получим html с минимальным парсингом (line = без форматирования)
        $content = $Parsedown->line($content);

        $content = str_replace(["\r\n", "\r", "\n", "#"], ' ', $content);

        $str =  str_replace(['&gt;', '{cut}'], '', strip_tags($content));

        return self::fragment($str, $lenght);
    }

    public static function fragment(string $text, int $lenght = 150, string $charset = 'UTF-8')
    {
		$text = LitEmoji::encodeUnicode($text);
		
        if (mb_strlen($text, $charset) >= $lenght) {
            $wrap = wordwrap($text, $lenght, '~');
            $ret = mb_strpos($wrap, '~', 0, $charset);

            return  mb_substr($wrap, 0, (int)$ret, $charset) . '...';
        }

        if (empty($text)) $text = '...';

        return $text ?? false;
    }
}
