<?php

class Meta
{
    public static function get($title = '', $description = '', $m = [])
    {
        $output = '';
        if ($title == '') {
            $title = Config::get('meta.title');
        }
        if ($description == '') {
            $description = Config::get('meta.name');
        }

        if (!empty($m['main'])) {
            $output .= '<title>' . $title . '</title>';
        } else {
            $output .= '<title>' . $title . ' | ' . Config::get('meta.name') . '</title>';
        }

        $output .= '<meta name="description" content="' . $description . '">';

        if (!empty($m['date'])) {
            $output .= '<meta property="og:type" content="article" />'
                . '<meta property="article:published_time" content="' . $m['date'] . '" />';
        }

        if (!empty($m)) {
            if ($m['url']) {
                $output .= '<link rel="canonical" href="' . Config::get('meta.url') . $m['url'] . '">';
            }

            if (!empty($m['og'])) {
                $output .= '<meta property="og:title" content="' . $title . '"/>'
                    . '<meta property="og:description" content="' . $description . '"/>'
                    . '<meta property="og:url" content="' . Config::get('meta.url') . $m['url'] . '"/>';

                if (!empty($m['imgurl'])) {
                    $output .= '<meta property="og:image" content="' . Config::get('meta.url') . $m['imgurl'] . '"/>'
                        . '<meta property="og:image:width" content="820" />'
                        . '<meta property="og:image:height" content="320" />';
                }
            }
        }

        return $output;
    }
}
