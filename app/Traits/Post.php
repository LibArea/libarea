<?php

namespace App\Traits;

trait Post
{
    /**
     * Преобразует указанные значения в массиве по выбранным именам.
     * Нужно для того, чтобы в массиве не оказались поля с небезопасными символами.
     * @param array $list
     * @param array $names
     */
    private static function convertingToSpecialChar(&$list, $names)
    {
        foreach($names as $name) {
            if (isset($list[$name])) {
                $list[$name] = str_replace(['<', '>'], ['&lt;', '&gt;'], $list[$name]);
            }
        }
    }
}
