<?php

class Meta
{
    public static function get($title = '', $description = '', $m = [])
    {
        $title = ($title == '') ? config('meta.title') : $title;
        $description = ($description == '') ? config('meta.name') : $description;

        $output = '';
        $output .= (empty($m['main'])) ? '<title>' . $title . ' | ' . config('meta.name') . '</title>' : '<title>' . $title . '</title>';

        $output .= '<meta name="description" content="' . $description . '">';

        if (!empty($m['published_time'])) {
            $output .= '<meta property="article:published_time" content="' . $m['published_time'] . '">';
        }

        $output .= (empty($m['type'])) ? '<meta property="og:type" content="website">' : '<meta property="og:type" content="' . $m['type'] . '">';


        if (!empty($m)) {
            if ($m['url']) {
                $output .= '<link rel="canonical" href="' . config('meta.url') . $m['url'] . '">';
            }

            if (!empty($m['og'])) {
                $output .= '<meta property="og:title" content="' . $title . '">'
                    . '<meta property="og:description" content="' . $description . '">'
                    . '<meta property="og:url" content="' . config('meta.url') . $m['url'] . '">';

                if (!empty($m['imgurl'])) {
                    $output .= '<meta property="og:image" content="' . config('meta.url') . $m['imgurl'] . '">'
                        . '<meta property="og:image:width" content="820">'
                        . '<meta property="og:image:height" content="320">';
                }
                
                $output .= '<meta name="twitter:card" content="summary">'
                    . '<meta name="twitter:title" content="' . $title . '">'
                    . '<meta name="twitter:url" content="' . config('meta.url') . $m['url'] . '">'
                    . '<meta property="twitter:description" content="' . $description . '">';
                
                if (!empty($m['imgurl'])) { $output .= '<meta property="twitter:image" content="' . config('meta.url') . $m['imgurl'] . '">'; }
            }
            

        }

        return $output;
    }
}
