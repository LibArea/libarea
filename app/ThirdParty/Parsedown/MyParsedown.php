<?php

// + Parsedown
// https://github.com/erusev/parsedown/wiki/Tutorial:-Create-Extensions#add-multi-line-element
class MyParsedown extends Parsedown
{
    
    function __construct()
    {
        $this->InlineTypes['{'][]= 'ColoredText';
        $this->inlineMarkerList .= '{';
    }
    
    protected function inlineColoredText($excerpt)
    {
        if (preg_match('/^{c:([#\w]\w+)}(.*?){\/c}/', $excerpt['text'], $matches))
        {
            return array(

                // How many characters to advance the Parsedown's
                // cursor after being done processing this tag.
                'extent' => strlen($matches[0]), 
                'element' => array(
                    'name' => 'span',
                    'text' => $matches[2],
                    'attributes' => array(
                        'style' => 'color: ' . $matches[1],
                    ),
                ),

            );
        }
    }
    
    protected function inlineLink($Excerpt)
    {
        $link = parent::inlineLink($Excerpt);
        if($link) {
            if ($this->urlIsExternal($link['element']['attributes']['href'])) {
                $link['element']['attributes'] += [
                    'target' => '_blank',
                    'rel'    => 'noopener nofollow ugc',
                ];
            }
        }
        return $link;
    }

    protected function urlIsExternal($url)
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $host   = parse_url($url, PHP_URL_HOST);

        if (!$scheme || !$host) {
            return false;
        }

        if (strpos(strtolower($scheme), 'http') !== 0) {
            return false;
        }

        // проверим хост и введем TL

        return true;
    }
    
}