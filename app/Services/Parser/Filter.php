<?php

declare(strict_types=1);

namespace App\Services\Parser;

use App\Services\Parser\Jevix;
use Parsedown;

class Filter
{
    // Content management (Parsedown and Jevix)
    public static function noHTML(string $content, int $lenght = 150)
    {
        $Parsedown = new Parsedown();
        $jevix = new Jevix();

        // Get html with minimal parsing (line = no formatting)
        // Получим html с минимальным парсингом (line = без форматирования)
        $content = $Parsedown->line($content);

        $content = str_replace(["\r\n", "\r", "\n", "#"], ' ', $content);

        // Tags to be stripped from the text along with the content.
        // Теги, которые необходимо вырезать из текста вместе с контентом.
        $jevix->cfgSetTagCutWithContent(['script', 'style', 'details', 'style', 'iframe', 'code', 'a', 'p', 'br', 'img', 'table']);

        $item = [];
        $text = $jevix->parse($content, $item);

        $str =  str_replace(['&gt;', '{cut}', '/'], '', $text);

        return self::fragment($str, $lenght);
    }

    public static function fragment(string $text, int $lenght = 150, string $charset = 'UTF-8')
    {
        if (mb_strlen($text, $charset) >= $lenght) {
            $wrap = wordwrap($text, $lenght, '~');
            $str_cut = mb_substr($wrap, 0, mb_strpos($wrap, '~', 0, $charset), $charset);
            return $str_cut .= '...';
        }

        return $text;
    }
}
