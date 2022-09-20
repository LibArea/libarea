<?php

declare(strict_types=1);

namespace App\Services\Parser;

use App\Services\Parser\Jevix;
use Parsedown;

class Filter
{
    // Работа с контентом (Parsedown and Jevix)
    public static function noHTML(string $content, int $lenght)
    {
        $Parsedown = new Parsedown();
        $jevix = new Jevix();

        $content = str_replace(["\r\n", "\r", "\n"], '', $content);

        // Получим html с минимальным парсингом (line = без экранирования)
        $content = $Parsedown->line($content);

        // Теги, которые необходимо вырезать из текста вместе с контентом.
        $jevix->cfgSetTagCutWithContent(['script', 'style', 'details', 'style', 'iframe', 'code', 'a', 'p', 'br', 'img', 'table']);

        // Ин. Jevix с условиями выше
        $item = [];
        $text = $jevix->parse($content, $item);

        // Откорректируем конечный результат
        $str =  str_replace(['&gt;'], '', $text);

        return self::fragment($str, $lenght);
    }

    public static function fragment(string $text, int $lenght = 100, string $charset = 'UTF-8')
    {
        if (mb_strlen($text, $charset) >= $lenght) {
            $wrap = wordwrap($text, $lenght, '~');
            $str_cut = mb_substr($wrap, 0, mb_strpos($wrap, '~', 0, $charset), $charset);
            return $str_cut .= '...';
        }

        return $text;
    }
}
