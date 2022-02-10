<?php

function sumbit($text)
{
    $html  = '<button type="submit" name="action" class="btn btn-primary mt15" value="submit">' . $text . '</button>';

    return $html;
}

function remove($text)
{
    $html  = '<button type="submit" name="action" class="btn btn-outline-primary right mr10" value="delete">' . $text . '</button>';

    return $html;
}
