<?php

class Validation
{
    public static function length($content, $min, $max)
    {
        if (Html::getStrlen($content) < $min || Html::getStrlen($content) > $max) {
            return false;
        }
        return true;
    }

    public static function ComeBack($text, $status, $redirect = '/')
    {
        Html::addMsg(__($text), $status);
        redirect($redirect);
    }
}
