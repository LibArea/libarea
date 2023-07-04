<?php

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class URLScraper
{
    public static function get($url)
    {
        // Create a client to make the HTTP request
        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);
        $html = (string) $response->getBody();

        $crawler = new Crawler($html);

        $titleNode          = $crawler->filter('title');
        $descriptionNode    = $crawler->filter('meta[name="description"]');
        $imageNode          = $crawler->filter('meta[property="og:image"]');
        
        $title = $titleNode->count() ? $titleNode->first()->text() : null;
        $description = $descriptionNode->count() ? $descriptionNode->first()->attr('content') : null;
        $image = $imageNode->count() ? $imageNode->first()->attr('content') : null;

        return [
            'title'       => $title,
            'description' => $description,
            'image'       => $image
        ];
    }
}