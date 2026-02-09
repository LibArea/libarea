<?php

declare(strict_types=1);

use LitEmoji\LitEmoji;

use Djot\DjotConverter;
use Djot\Node\Inline\Link;
use Djot\Node\Inline\Text;
use Djot\Event\RenderEvent;
use Djot\Node\Block\Div;

use Djot\Extension\AutolinkExtension;
use Djot\Extension\ExternalLinksExtension;
use Djot\Extension\SmartQuotesExtension;
use Djot\Extension\MentionsExtension;
use Djot\SafeMode;

use MediaEmbed\MediaEmbed;

class Parser
{
    public static function text(string $content, string $type)
    {
        $text = self::parse($content);
        $text = self::gif($text);
		$text = self::video($text);

        return LitEmoji::encodeUnicode($text);
    }

    public static function parse(string $content)
    {
        $content = str_replace('{cut}', '', $content);
		$content = str_replace('[^1]', '', $content);

		// https://github.com/php-collective/djot-php/tree/master
		$converter = new DjotConverter(safeMode: SafeMode::strict());
		
		$parser = $converter->getParser();	
		$parser->addBlockPattern('/^:::spoiler\s*$/', function($lines, $start, $parent, $parser) {
		      $endPattern = '/^:::\s*$/';
		      $content = [];
		      $i = $start + 1;
		      while ($i < count($lines) && !preg_match($endPattern, $lines[$i])) {
		          $content[] = $lines[$i];
		          $i++;
		      }
		      $div = new Div();
		      $div->setAttribute('class', 'spoiler');
		      // Parse content inside
		      $parser->parseBlockContent($div, $content);
		      $parent->appendChild($div);
		      return $i - $start + 1; // +1 for closing :::
		  });
		
		// Ссылка на темы, например: #libarea
		$parser = $converter->getParser()->getInlineParser();
		$parser->addInlinePattern('/#([a-zA-Z][a-zA-Z0-9_]*)/', function ($match, $groups, $p) {
			$tag = $groups[1];
			$link = new Link('/topic/' . strtolower($tag));
			$link->appendChild(new Text('#' . $tag));
			$link->setAttribute('class', 'green');
			return $link;
		});
		
		$text = $converter
			->addExtension(new AutolinkExtension()) 
			->addExtension(new ExternalLinksExtension())
			->addExtension(new SmartQuotesExtension(locale: config('general', 'lang')))
			->addExtension(new MentionsExtension(urlTemplate: '/@{username}', cssClass: 'green',))
			->convert($content);

        return $text;  
    }

    public static function gif($content)
    {
        preg_match_all('/\:(\w+)\:/mUs', strip_tags($content), $matchs);

        if (is_array($matchs[1])) {

            $match_name = [];
            foreach ($matchs[1] as $name) {
                if (in_array($name, $match_name)) {
                    continue;
                }

                $match_name[] = $name;
            }

            $match_name = array_unique($match_name);

            arsort($match_name);

            foreach ($match_name as $key => $name) {

                $img = '/assets/images/gif/' . $name . '.gif';
                if (file_exists('.' . $img)) {
                    $content = str_replace(':' . $name . ':', '<img class="gif" alt="' . $name . '" src="' . $img . '">', $content);
                }
            }
        }

        return  $content;
    }

    public static function video($content)
    {
		// TODO
        return $content;
	}	
		 
    // TODO: Let's check the simple version for now.
    public static function cut($text, $length = 800)
    {
        $charset = 'UTF-8';
        $beforeCut = $text;
        $afterCut = false;

        if (preg_match("#^(.*){cut([^}]*+)}(.*)$#Usi", $text, $match)) {
            $beforeCut  = $match[1];
            $afterCut   = $match[3];
        }

        if (!$afterCut) {
            $beforeCut = self::fragment($text, $length);
        }

        $button = false;
        if ($afterCut || mb_strlen($text, $charset) > $length) {
            $button = true;
        }

        return ['content' => $beforeCut, 'button' => $button];
    }

    // Content management
    public static function noHTML(string $content, int $lenght = 150)
    {
		$converter = new DjotConverter(safeMode: SafeMode::strict());
		$text = $converter->convert($content);

        $content = str_replace(["\r\n", "\r", "\n", "#"], ' ', $text);

        $str =  str_replace(['&gt;', '{cut}'], '', strip_tags($content));

        return self::fragment($str, $lenght);
    }

    public static function fragment(string $text, int $lenght = 150, string $charset = 'UTF-8'): string
    {
		$text = LitEmoji::encodeUnicode($text);
		
        if (mb_strlen($text, $charset) >= $lenght) {
            $wrap = wordwrap($text, $lenght, '~');
            $ret = mb_strpos($wrap, '~', 0, $charset);

            return  mb_substr($wrap, 0, (int)$ret, $charset) . '...';
        }

        if (empty($text)) $text = '...';

        return $text;
    }
}
