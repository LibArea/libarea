<?php

class Meta
{
    public static function get($title = '', $description = '', $m = [])
    {
        $output = '';
        if ($title == '') {
            $title = config('meta.title');
        }
        if ($description == '') {
            $description = config('meta.name');
        }

        if (!empty($m['main'])) {
            $output .= '<title>' . $title . '</title>';
        } else {
            $output .= '<title>' . $title . ' | ' . config('meta.name') . '</title>';
        }

        $output .= '<meta name="description" content="' . $description . '">';

        if (!empty($m['published_time'])) {
            $output .= '<meta property="article:published_time" content="' . $m['published_time'] . '" />';
        } 
       
        if (!empty($m['type'])) {
            $output .= '<meta property="og:type" content="' . $m['type'] . '" />';
        }  else {
            $output .= '<meta property="og:type" content="website" />';
        }

        if (!empty($m)) {
            if ($m['url']) {
                $output .= '<link rel="canonical" href="' . config('meta.url') . $m['url'] . '">';
            }

            if (!empty($m['og'])) {
                $output .= '<meta property="og:title" content="' . $title . '"/>'
                    . '<meta property="og:description" content="' . $description . '"/>'
                    . '<meta property="og:url" content="' . config('meta.url') . $m['url'] . '"/>';

                if (!empty($m['imgurl'])) {
                    $output .= '<meta property="og:image" content="' . config('meta.url') . $m['imgurl'] . '"/>'
                        . '<meta property="og:image:width" content="820" />'
                        . '<meta property="og:image:height" content="320" />';
                }
            }
        }

        return $output;
    }
}
