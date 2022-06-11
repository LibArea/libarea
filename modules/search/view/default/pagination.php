<?php

if ($pNum > $pagesCount) {
    return null;
}

$url = empty($url) ? '' : $url;
$first = empty($url) ? '/' : $url;

$html = '<div class="flex gap">';

if ($pNum != 1) {
    if (($pNum - 1) == 1) {
        $html .= '<a class="pr5" href="' . $first . '"><< ' . ($pNum - 1) . '</a>';
    } else {
        $html .= '<a class="pr5" href="' . $url . '&page=' . ($pNum - 1) . '"><< ' . ($pNum - 1) . '</a>';
    }
}

if ($pagesCount > $pNum) {
    $html .= '<div class="bg-green p5-10 white">' . ($pNum) . '</div>';
}

if ($pagesCount > $pNum) {
    if ($pagesCount > $pNum + 1) {
        $html .= '<a class="p5" href="' . $url . '&page=' . ($pNum + 1) . '"> ' . ($pNum + 1) . ' </a>';
    }

    if ($pagesCount > $pNum + 2) {
        $html .= '<a class="p5" href="' . $url . '&page=' . ($pNum + 2) . '"> ' . ($pNum + 2) . '</a>';
    }

    if ($pagesCount > $pNum + 3) {
        $html .= '...';
    }

    $html .= '<a class="p5 lowercase gray-600" href="' . $url . '&page=' . ($pNum + 1) . '">' . __('app.page') . ' ' . ($pNum + 1) . ' >></a>';
}

$html .= '</div>';

echo $html;
