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
    
    protected function element(array $Element)
    {
        if ($this->safeMode) {
            $Element = $this->sanitiseElement($Element);
        }

        $markup = '<' . $Element['name'];

        if (isset($Element['name']) && $Element['name'] == 'a') {
            $server_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
            $href_host = isset($Element['attributes']['href']) ? parse_url($Element['attributes']['href'], PHP_URL_HOST) : null;

        // Add a list of allowed urls to the config?
        if ($server_host != $href_host) {
                $Element['attributes']['target'] = '_blank';
                $Element['attributes']['rel'] = 'noopener nofollow ugc';
            }
        }

        if (isset($Element['attributes'])) {
            foreach ($Element['attributes'] as $name => $value) {
                if ($value === null) {
                    continue;
                }

                $markup .= ' ' . $name . '="' . self::escape($value) . '"';
            }
        }

        if (isset($Element['text'])) {
            $markup .= '>';

            if (!isset($Element['nonNestables'])) {
                $Element['nonNestables'] = array();
            }

            if (isset($Element['handler'])) {
                $markup .= $this->{$Element['handler']}($Element['text'], $Element['nonNestables']);
            }
            else {
                $markup .= self::escape($Element['text'], true);
            }

            $markup .= '</' . $Element['name'] . '>';
        }
        else {
            $markup .= ' />';
        }

        return $markup;
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
    
    
}