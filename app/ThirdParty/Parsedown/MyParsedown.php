<?php

// Расширим Parsedown
class MyParsedown extends Parsedown
{
        protected function inlineLink($Excerpt)
        {
            $link = parent::inlineLink($Excerpt);

            if ($this->urlIsExternal($link['element']['attributes']['href'])) {
                $link['element']['attributes'] += [
                    'target' => '_blank',
                    'rel'    => 'noopener nofollow ugc',
                ];
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