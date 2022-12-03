<?php

// MIT License
// Copyright 2018 Bert Maurau
// https://github.com/tommyku/php-html-meta-parser
// FIX: UTF (test: https://lenta.ru/news/2021/09/09/fantastic_russians/)
class URLScraper
{
	private static $STATUS = [
		"initialized" => 0,
		"success" => 1,
		"fail" => 2
	];

	private $metaData;
	private $url;
	private $html;
	private $status;
	private $meta;

	/**
	 * __construct
	 * 
	 * Require the url of the webpage for meta data extraction
	 *
	 * @access public
	 * @param  string $url
	 */
	public function __construct($url)
	{
		$this->url = $url;
		$this->metaData = new StdClass;
		$this->metaData->title = "";
		$this->metaData->description = "";
		$this->metaData->image = "";
		$this->initialized();
	}

	/**
	 * parse
	 * 
	 * Parse the meta data from the given url and populate data member $metaData
	 *
	 * @access public
	 * @return integer
	 */
	public function parse()
	{
		// load HTML as DOMDocument object for parsing
		$this->html = new DOMDocument;
		libxml_use_internal_errors(true);
		// Recoding
		$source = mb_convert_encoding(file_get_contents($this->url), 'HTML-ENTITIES', 'utf-8');
		$this->html->loadHTML($source);

		// php built-in get_meta_tags() only read those with name "title", "description" and so on
		// so I wrote my own version supporting twitter:title, og:title, etc.
		$this->meta = $this->my_get_meta_tags($this->url);

		$this->success(); // assume successful

		// possible to add more method such as getAuthor()
		$this->getTitle();
		$this->getDescription();
		$this->getImage();

		return $this->status;
	}


	/**
	 * finalize
	 * 
	 * Export the meta data parsed
	 *
	 * @access public
	 * @return StdClass
	 */
	public function finalize()
	{
		$tmp = new StdClass;
		$tmp->url = $this->url;
		$tmp->title = $this->metaData->title;
		$tmp->description = $this->metaData->description;
		$tmp->image = $this->metaData->image;
		$tmp->status = $this->status;
		return $tmp;
	}

	/**
	 * my_get_meta_tags
	 * 
	 * Require the url to be parsed, read every meta tags found
	 *
	 * @access private
	 * @param  string $url
	 * @return array
	 */
	private function my_get_meta_tags($url)
	{
		$metatags = $this->html->getElementsByTagName("meta");
		$tmeta = array();
		for ($i = 0; $i < $metatags->length; ++$i) {
			$item = $metatags->item($i);
			$name = $item->getAttribute('name');

			if (empty($name)) {
				// og meta tags, or twitter meta tags
				$tmeta[$item->getAttribute('property')] = $item->getAttribute('content');
			} else {
				// conventional meta tags
				$tmeta[$name] = $item->getAttribute('content');
			}
		}
		return $tmeta;
	}

	/**
	 * initizlized
	 * 
	 * Set the state of the object to be initizlied
	 *
	 * @access private
	 */
	private function initialized()
	{
		$this->status = self::$STATUS["initialized"];
	}

	/**
	 * success
	 * 
	 * Set the state of the object to be successful
	 *
	 * @access private
	 */
	private function success()
	{
		$this->status = self::$STATUS["success"];
	}

	/**
	 * fail
	 * 
	 * Set the state of the object to be failed
	 *
	 * @access private
	 */
	private function fail()
	{
		$this->status = self::$STATUS["fail"];
	}

	/**
	 * getTitle
	 * 
	 * Read meta title based on priorities of the tag name/property, 
	 * fallback to reading <title> and <h1> if meta title not present
	 *
	 * @access private
	 */
	private function getTitle()
	{
		if (isset($this->meta["og:title"])) {
			$this->metaData->title = $this->meta["og:title"];
			return;
		}

		if (isset($this->meta["twitter:title"])) {
			$this->metaData->title = $this->meta["twitter:title"];
			return;
		}

		if (isset($this->meta["title"])) {
			$this->metaData->title = $this->meta["title"];
			return;
		}

		$title = $this->html->getElementsByTagName("title") or $title = $this->html->getElementsByTagName("h1");
		// taking either the title or h1 tag
		if (!$title->length) {
			// if no h1 tag, nothing good enough to be the site title
			$this->fail();
			return;
		} else {
			$this->metaData->title = ($title->length) ? $title->item(0)->nodeValue : "";
		}
	}

	/**
	 * getDescription
	 * 
	 * Read meta description based on priorities of the tag name/property. 
	 * No fallback, it doesn't read anything except for the meta tag
	 *
	 * @access private
	 */
	private function getDescription()
	{
		if (isset($this->meta["og:description"])) {
			$this->metaData->description = $this->meta["og:description"];
			return;
		}

		if (isset($this->meta["twitter:description"])) {
			$this->metaData->description = $this->meta["twitter:description"];
			return;
		}

		if (isset($this->meta["description"])) {
			$this->metaData->description = $this->meta["description"];
			return;
		}

		$this->fail();
		return;
	}

	/**
	 * getImage
	 * 
	 * Read meta image url based on priorities of the tag name/property. 
	 * No fallback, it doesn't read anything except for the meta tag
	 *
	 * @access private
	 */
	private function getImage()
	{
		if (isset($this->meta["og:image"])) {
			$this->metaData->image = $this->meta["og:image"];
			return;
		}

		if (isset($this->meta["twitter:image"])) {
			$this->metaData->image = $this->meta["twitter:image"];
			return;
		}

		if (isset($this->meta["image"])) {
			$this->metaData->image = $this->meta["image"];
			return;
		}

		$this->fail();
	}
};
