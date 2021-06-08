<?php

/*
 * Copyright 2018 Bert Maurau.
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * Description of URLScraper
 *
 * @author Bert Maurau
 */
class URLScraper
{

    /**
     * The initial URL
     * @var string
     */
    public static $url;

    /**
     * The domain that the URL is hosted on
     * @var string
     */
    public static $domain;

    /**
     * Holds the fetched content (html data)
     * @var string
     */
    public static $contents;

    /**
     * Will hold the title of the page
     * @var string
     */
    public static $title;

    /**
     * Will hold the Description of the page
     * @var string
     */
    public static $description;

    /**
     * Will hold the URL for the image
     * @var string
     */
    public static $image;

    /**
     * Will hold all the Meta tags
     * @var array
     */
    public static $tags_meta;

    /**
     * Will hold all the OG tags
     * @var array
     */
    public static $tags_og;

    /**
     * Fetch the HTML contents from the URL
     * @param string $url
     * @return string
     */
    private static function loadContents($url)
    {
        $curl_options = array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER         => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING       => "", // handle all encodings
            CURLOPT_USERAGENT      => "spider", // who am i
            CURLOPT_AUTOREFERER    => true, // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 60, // timeout on connect
            CURLOPT_TIMEOUT        => 120, // timeout on response
            CURLOPT_MAXREDIRS      => 5, // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $curl_options);

        // Fetch the contents
        self::$contents = curl_exec($ch);

        curl_close($ch);

        return self::$contents;
    }

    /**
     * Send a second request to just fetch the meta tags
     * @param string $url
     * @return array
     */
    private static function getMetaTags($url)
    {
        // If this fails, the OG tags (if available) gets parsed from the content first
        self::$tags_meta = get_meta_tags($url);
        return self::$tags_meta;
    }

    /**
     * Parse the OG tags fron the HTML contents
     * @param string $url
     * @return array
     */
    private static function getOgTags()
    {
        preg_match_all('~<\s*meta\s+property="(og:[^"]+)"\s+content="([^"]*)~i', self::$contents, $matches);
        $tags_og = array();
        for ($i = 0; $i < count($matches[1]); $i++) {
            $tags_og[trim(substr($matches[1][$i], 3))] = $matches[2][$i];
        }
        return self::$tags_og = $tags_og;
    }

    /**
     * Parse the domain from the URL
     * @param string $url
     * @return string
     */
    private static function getDomain($url)
    {
        $domain = parse_url($url, PHP_URL_HOST);
        $domain = $domain ? $domain : parse_url($url, PHP_URL_PATH);

        return self::$domain = $domain;
    }

    /**
     * Parse the Title from the HTML contents
     * @return string
     */
    private static function getTitle()
    {
        if (!self::$contents) {
            self::loadContents(self::$url);
        }

        return preg_match('/<title[^>]*>(.*?)<\/title>/ims', self::$contents, $matches) ? $matches[1] : null;
    }

    /**
     * Get the description of the page (first from OG then from Meta)
     * @return string
     */
    private static function getDescription()
    {
        if (isset(self::$tags_og['description'])) {
            return self::$tags_og['description'];
        } else {
            // Check for Meta tags
            if (isset(self::$tags_meta['description'])) {
                return self::$tags_meta['description'];
            } else {
                // set url as description
                return self::$url;
            }
        }
    }

    /**
     * Get the image of the page (only from OG)
     * @return string
     */
    private static function getImage()
    {
        if (isset(self::$tags_og['image'])) {
            return self::$tags_og['image'];
        } else {
            // set url as description
            return null;
        }
    }

    /**
     * Main function to get all the information
     * @param string $url
     * @return array
     */
    public static function get($url)
    {

        // Set the URL
        self::$url = $url;

        // Parse the Domain
        self::getDomain($url);

        // Fetch the HTML contents
        self::loadContents($url);

        // Fetch the Meta tags
        self::getMetaTags($url);

        // Parse the OG Tags
        self::getOgTags($url);

        // Set the main values
        self::$title = self::getTitle();
        self::$description = self::getDescription();
        self::$image = self::getImage();

        // Return everything
        return array(
            'url'         => self::$url,
            'domain'      => self::$domain,
            'title'       => self::$title,
            'description' => self::$description,
            'image'       => self::$image,
            'tags_meta'   => self::$tags_meta,
            'tags_og'     => self::$tags_og
        );
    }

}
