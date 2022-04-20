<?php

declare(strict_types=1);

/*
 * Matching names for the functions used.
 *
 * Сопоставление названий для используемых функций.
 */

if (! function_exists('__')) 
{
    // @param  string|null  $key
    function __(string $key = null, array $params = []) {
        if (is_null($key)) {
            return $key;
        }

        return Translate::get($key, $params);
    }
}
