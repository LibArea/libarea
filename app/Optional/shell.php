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
    function __($key = null) {
        if (is_null($key)) {
            return $key;
        }

        return Translate::get($key);
    }
}
